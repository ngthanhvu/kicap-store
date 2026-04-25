<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Mail\PaymentConfirmation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private function sendPaymentConfirmation($order)
    {
        $email = $order->user->email ?? null;

        if (empty($email)) {
            Log::warning('Payment confirmation email skipped because user email is empty', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ]);
            return;
        }

        try {
            Mail::to($email)->send(new PaymentConfirmation($order));
        } catch (\Throwable $e) {
            Log::warning('Payment confirmation email failed', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'email' => $email,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    // Tạo URL thanh toán VNPay
    private function generateVnpayUrl($order, $amount)
    {
        $vnp_TmnCode = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_Returnurl = route('vnpay.callback');

        if (empty($vnp_TmnCode) || empty($vnp_HashSecret) || empty($vnp_Url)) {
            Log::error('VNPay config is missing', [
                'order_id' => $order->id,
                'has_tmn_code' => !empty($vnp_TmnCode),
                'has_hash_secret' => !empty($vnp_HashSecret),
                'has_url' => !empty($vnp_Url),
            ]);
            abort(500, 'Cau hinh VNPay khong hop le');
        }

        $vnp_TxnRef = $order->id;
        $vnp_OrderInfo = "Thanh toán đơn hàng #$vnp_TxnRef";
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "250000",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = $query . "&vnp_SecureHash=" . hash_hmac('sha512', $query, $vnp_HashSecret);

        return $vnp_Url . "?" . $hashdata;
    }

    // Tạo URL thanh toán MoMo
    private function generateMomoUrl($order, $amount)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        if (empty($partnerCode) || empty($accessKey) || empty($secretKey)) {
            Log::error('MoMo config is missing', [
                'order_id' => $order->id,
                'has_partner_code' => !empty($partnerCode),
                'has_access_key' => !empty($accessKey),
                'has_secret_key' => !empty($secretKey),
            ]);
            abort(500, 'Cấu hình MoMo không hợp lệ: partnerCode bị trống');
        }

        Log::info('MoMo partnerCode: ' . $partnerCode);

        $randomNumber = rand(10000, 99999);
        $orderId = $order->id . 'MOMOPAY' . $randomNumber;
        $orderInfo = "Thanh toán đơn hàng #$order->id";
        $requestId = $partnerCode . time();
        $requestType = "payWithATM";
        $extraData = "";

        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $returnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $returnUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawSignature, $secretKey);

        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $returnUrl,
            "ipnUrl" => $returnUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,
            "signature" => $signature,
            "lang" => "vi"
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        } else {
            Log::error('MoMo payment URL creation failed', ['response' => $result]);
            abort(500, 'Không thể tạo URL thanh toán MoMo');
        }
    }

    //Tạo URL thanh toán Paypal
    private function generatePaypalUrl($order, $amount)
    {
        $apiUrl = env('PAYPAL_API_URL', 'https://api-m.sandbox.paypal.com');
        $clientId = env('PAYPAL_CLIENT_ID', 'YourPayPalClientIDHere');
        $clientSecret = env('PAYPAL_SECRET', 'YourPayPalSecretHere');
        $returnUrl = route('paypal.callback');
        $cancelUrl = route('paypal.cancel');

        if (empty($clientId) || empty($clientSecret) || $clientId === 'YourPayPalClientIDHere' || $clientSecret === 'YourPayPalSecretHere') {
            Log::error('PayPal config is missing', [
                'order_id' => $order->id,
                'has_client_id' => !empty($clientId) && $clientId !== 'YourPayPalClientIDHere',
                'has_client_secret' => !empty($clientSecret) && $clientSecret !== 'YourPayPalSecretHere',
            ]);
            abort(500, 'Cau hinh PayPal khong hop le');
        }

        // Lấy access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::error('PayPal access token request failed', ['response' => $response]);
            abort(500, 'Không thể lấy access token từ PayPal');
        }

        $tokenData = json_decode($response, true);
        $accessToken = $tokenData['access_token'];

        // Tạo thanh toán
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->id,
                'amount' => [
                    'currency_code' => env('PAYPAL_CURRENCY', 'USD'),
                    'value' => number_format($amount / 24000, 2, '.', '')
                ]
            ]],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => 'Your Store Name',
                'locale' => 'en-US',
                'landing_page' => 'BILLING',
                'user_action' => 'PAY_NOW'
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/v2/checkout/orders");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 201) {
            Log::error('PayPal payment creation failed', ['response' => $response]);
            abort(500, 'Không thể tạo thanh toán PayPal');
        }

        $result = json_decode($response, true);
        foreach ($result['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        Log::error('PayPal approve URL not found', ['response' => $result]);
        abort(500, 'Không tìm thấy URL thanh toán PayPal');
    }

    // Gửi POST request cho MoMo
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Callback cho VNPay
    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'];

        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . "=" . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $order = Orders::where('id', $inputData['vnp_TxnRef'])->first();

            if ($order) {
                $responseCode = $inputData['vnp_ResponseCode'];

                if ($responseCode === "00") {
                    $order->status = 'paid';
                    $order->save();

                    $orderItems = $order->orderItems;
                    foreach ($orderItems as $orderItem) {
                        if ($orderItem->variant_id) {
                            $variant = $orderItem->variant;
                            if ($variant) {
                                $variant->varriant_quantity -= $orderItem->quantity;
                                $variant->save();
                            }
                        } else {
                            $product = $orderItem->product;
                            if ($product) {
                                $product->quantity -= $orderItem->quantity;
                                $product->save();
                            }
                        }

                        Product::where('id', $orderItem->product_id)->update([
                            'count' => DB::raw('count + 1')
                        ]);
                    }


                    $user = $order->user;
                    if ($user && !empty($user->email)) {
                        $order->email = $user->email;
                        $this->sendPaymentConfirmation($order);
                    }

                    return redirect()->route('alert.success');
                } else {
                    $order->status = ($responseCode === "24") ? 'canceled' : 'fail';
                    $order->save();
                    return redirect()->route('alert.fail');
                }
            } else {
                return redirect()->route('alert.fail');
            }
        } else {
            return redirect()->route('alert.fail');
        }
    }

    // Callback cho MoMo
    public function momoCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');
        $accessKey = env('MOMO_ACCESS_KEY');

        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode'])) {
            Log::error('Invalid MoMo callback data', $data);
            return redirect()->route('alert.fail')->with('error', 'Dữ liệu callback không hợp lệ');
        }

        $orderIdParts = explode('MOMOPAY', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        $amount = $data['amount'];
        $extraData = $data['extraData'] ?? '';
        $message = $data['message'] ?? '';
        $orderInfo = $data['orderInfo'] ?? '';
        $orderType = $data['orderType'] ?? '';
        $partnerCode = $data['partnerCode'] ?? '';
        $payType = $data['payType'] ?? '';
        $requestId = $data['requestId'] ?? '';
        $responseTime = $data['responseTime'] ?? '';
        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? '';

        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&message=" . $message .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&partnerCode=" . $partnerCode .
            "&payType=" . $payType .
            "&requestId=" . $requestId .
            "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode .
            "&transId=" . $transId;

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature']) {
            $order = Orders::where('id', $originalOrderId)->first();

            if ($order) {
                if ($resultCode == '0') {
                    $order->status = 'paid';
                    $order->save();

                    $orderItems = $order->orderItems;
                    foreach ($orderItems as $orderItem) {
                        if ($orderItem->variant_id) {
                            $variant = $orderItem->variant;
                            if ($variant) {
                                $variant->varriant_quantity -= $orderItem->quantity;
                                $variant->save();
                            }
                        } else {
                            $product = $orderItem->product;
                            if ($product) {
                                $product->quantity -= $orderItem->quantity;
                                $product->save();
                            }
                        }

                        Product::where('id', $orderItem->product_id)->update([
                            'count' => DB::raw('count + 1')
                        ]);
                    }


                    $user = $order->user;
                    if ($user && !empty($user->email)) {
                        $order->email = $user->email;
                        $this->sendPaymentConfirmation($order);
                    }

                    return redirect()->route('alert.success');
                } else {
                    Log::error('MoMo payment failed', ['orderId' => $originalOrderId, 'resultCode' => $resultCode]);
                    $order->status = 'fail';
                    $order->save();
                    return redirect()->route('alert.fail')->with('error', 'Thanh toán thất bại');
                }
            } else {
                Log::error('Order not found', ['orderId' => $originalOrderId]);
                return redirect()->route('alert.fail')->with('error', 'Không tìm thấy đơn hàng');
            }
        } else {
            Log::error('Invalid MoMo signature', [
                'calculated' => $calculatedSignature,
                'received' => $data['signature'],
                'rawSignature' => $rawSignature
            ]);
            return redirect()->route('alert.fail')->with('error', 'Chữ ký không hợp lệ');
        }
    }

    //callback cho Paypal
    public function paypalCallback(Request $request)
    {
        $apiUrl = env('PAYPAL_API_URL', 'https://api-m.sandbox.paypal.com');
        $clientId = env('PAYPAL_CLIENT_ID', 'YourPayPalClientIDHere');
        $clientSecret = env('PAYPAL_SECRET', 'YourPayPalSecretHere');
        $orderId = $request->input('token');

        // Lấy access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::error('PayPal access token request failed', ['response' => $response]);
            return redirect()->route('alert.fail')->with('error', 'Không thể xác thực PayPal');
        }

        $tokenData = json_decode($response, true);
        $accessToken = $tokenData['access_token'];

        // Capture thanh toán
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/v2/checkout/orders/$orderId/capture");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 201) {
            Log::error('PayPal capture failed', ['response' => $response]);
            return redirect()->route('alert.fail')->with('error', 'Thanh toán PayPal thất bại');
        }

        $result = json_decode($response, true);
        if ($result['status'] === 'COMPLETED') {
            $order = Orders::where('id', $result['purchase_units'][0]['reference_id'])->first();
            if ($order) {
                $order->status = 'paid';
                $order->save();

                // Cập nhật số lượng sản phẩm
                $orderItems = $order->orderItems;
                foreach ($orderItems as $orderItem) {
                    if ($orderItem->variant_id) {
                        $variant = $orderItem->variant;
                        if ($variant) {
                            $variant->varriant_quantity -= $orderItem->quantity;
                            $variant->save();
                        }
                    } else {
                        $product = $orderItem->product;
                        if ($product) {
                            $product->quantity -= $orderItem->quantity;
                            $product->save();
                        }
                    }

                    Product::where('id', $orderItem->product_id)->update([
                        'count' => DB::raw('count + 1')
                    ]);
                }

                // Gửi email xác nhận
                $user = $order->user;
                if ($user && !empty($user->email)) {
                    $order->email = $user->email;
                    $this->sendPaymentConfirmation($order);
                }

                return redirect()->route('alert.success');
            }
        }

        return redirect()->route('alert.fail')->with('error', 'Thanh toán PayPal thất bại');
    }

    public function paypalCancel(Request $request)
    {
        $order = Orders::where('id', $request->input('token'))->first();
        if ($order) {
            $order->status = 'canceled';
            $order->save();
        }
        return redirect()->route('alert.fail')->with('error', 'Thanh toán PayPal đã bị hủy');
    }

    // IPN cho MoMo
    public function momoIpn(Request $request)
    {
        $secretKey = env('MOMO_SECRET_KEY');
        $accessKey = env('MOMO_ACCESS_KEY');

        $data = $request->all();
        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $data['amount'] .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . $data['message'] .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $data['orderInfo'] .
            "&orderType=" . $data['orderType'] .
            "&partnerCode=" . $data['partnerCode'] .
            "&payType=" . $data['payType'] .
            "&requestId=" . $data['requestId'] .
            "&responseTime=" . $data['responseTime'] .
            "&resultCode=" . $data['resultCode'] .
            "&transId=" . $data['transId'];

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature']) {
            $order = Orders::where('id', $data['orderId'])->first();
            if ($order && $data['resultCode'] == 0) {
                $order->status = 'paid';
                $order->save();
            }
        }
    }

    // Phương thức xử lý thanh toán chung
    public function processPayment($order, $paymentMethod, $totalPrice)
    {
        Log::info('Payment processing started', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method' => $paymentMethod,
            'total_price' => $totalPrice,
        ]);

        switch ($paymentMethod) {
            case 'cod':
                $orderItems = $order->orderItems;
                foreach ($orderItems as $orderItem) {
                    if ($orderItem->variant_id) {
                        $variant = $orderItem->variant;
                        if ($variant) {
                            $variant->varriant_quantity -= $orderItem->quantity;
                            $variant->save();
                        }
                    } else {
                        $product = $orderItem->product;
                        if ($product) {
                            $product->quantity -= $orderItem->quantity;
                            $product->save();
                        }
                    }
                    Product::where('id', $orderItem->product_id)->update([
                        'count' => DB::raw('count + 1')
                    ]);
                }

                $this->sendPaymentConfirmation($order);
                Log::info('COD payment completed', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ]);
                return redirect()->route('alert.success', $order->id)->with('success', 'Thêm đơn hàng thành công!');
                break;
            case 'vnpay':
                Log::info('Generating VNPay payment URL', [
                    'order_id' => $order->id,
                    'total_price' => $totalPrice,
                ]);
                $vnpayUrl = $this->generateVnpayUrl($order, $totalPrice);
                return redirect()->away($vnpayUrl);
                break;
            case 'momo':
                Log::info('Generating MoMo payment URL', [
                    'order_id' => $order->id,
                    'total_price' => $totalPrice,
                ]);
                $momoUrl = $this->generateMomoUrl($order, $totalPrice);
                return redirect()->away($momoUrl);
                break;
            case 'paypal':
                Log::info('Generating PayPal payment URL', [
                    'order_id' => $order->id,
                    'total_price' => $totalPrice,
                ]);
                $paypalUrl = $this->generatePaypalUrl($order, $totalPrice);
                return redirect()->away($paypalUrl);
                break;
            default:
                throw new \Exception('Phương thức thanh toán không hợp lệ');
        }
    }
}
