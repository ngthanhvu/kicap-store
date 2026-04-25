<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Orders_item;
use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChanged;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index()
    {
        $title = 'Quản lý đơn hàng';
        $search = request()->input('search');
        $perPage = request()->input('per_page', 10);
        $sortBy = request()->input('sort_by', 'id');
        $sortOrder = request()->input('sort_order', 'desc');

        $query = Orders::query()->with('orderItems');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate($perPage);

        $orders->appends([
            'search' => $search,
            'per_page' => $perPage,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);

        return view('admin.orders.index', compact('title', 'orders', 'search', 'perPage', 'sortBy', 'sortOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required|in:cod,vnpay,momo,paypal',
            'shipping_fee' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $cartItems = Carts::where('user_id', $user->id)->get();

        Log::info('Checkout started', [
            'user_id' => $user->id,
            'payment_method' => $request->payment_method,
            'address_id' => $request->address_id,
            'shipping_fee' => $request->shipping_fee,
            'total_amount' => $request->total_amount,
            'discount' => $request->discount,
            'cart_count' => $cartItems->count(),
        ]);

        if ($cartItems->isEmpty()) {
            Log::warning('Checkout stopped because cart is empty', [
                'user_id' => $user->id,
            ]);
            return redirect()->back()->with('error', 'Giỏ hàng trống');
        }

        DB::beginTransaction();

        try {
            $shippingFee = $request->input('shipping_fee');
            $totalPrice = $request->input('total_amount');

            $order = Orders::create([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'shipping_fee' => $shippingFee,
                'discount' => $request->input('discount', 0),
                'status' => 'pending',
            ]);

            Log::info('Order created from checkout', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method' => $order->payment_method,
                'total_price' => $order->total_price,
            ]);

            foreach ($cartItems as $item) {
                Orders_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);
            }

            Carts::where('user_id', $user->id)->delete();

            DB::commit();

            $paymentController = new PaymentController();
            return $paymentController->processPayment($order, $request->payment_method, $totalPrice);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout failed', [
                'user_id' => $user->id ?? null,
                'payment_method' => $request->payment_method,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('alert.fail')->with('error', 'Có lỗi khi thêm đơn hàng: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $order = Orders::find($id);
        Orders_item::where('order_id', $order->id)->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Đơn hàng đã được xoá!');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Orders::with('user')->findOrFail($id); // lấy luôn quan hệ user

        $order->status = $request->input('status');
        $order->save();

        // Gửi mail nếu user có email
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatusChanged($order));
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function cancelOrder($id)
    {
        $order = Orders::find($id);
        if ($order) {
            $order->status = 'canceled';
            $order->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Đơn hàng đã được hủy!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại!',
            ]);
        }
    }

    public function reorder($id, Request $request)
    {
        $order = Orders::with('orderItems.product', 'orderItems.variant')->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại!'
            ], 404);
        }

        $userId = Auth::user()->id;
        $sessionId = session()->getId();

        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $variant = $item->variant;
            $availableQuantity = $variant ? $variant->varriant_quantity : $product->quantity;

            if ($item->quantity > $availableQuantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Sản phẩm {$product->name} không đủ số lượng tồn kho! Còn lại: $availableQuantity"
                ], 400);
            }

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $existingCart = Carts::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $item->quantity;
                if ($newQuantity > $availableQuantity) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Sản phẩm {$product->name} trong giỏ hàng vượt quá số lượng tồn kho! Còn lại: $availableQuantity"
                    ], 400);
                }
                $existingCart->update([
                    'quantity' => $newQuantity
                ]);
            } else {
                Carts::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
        }

        // Cập nhật số lượng sản phẩm trong giỏ hàng trong session
        $carts = Carts::where('user_id', $userId)->get();
        $count_cart = $carts->sum('quantity');
        session(['count_cart' => $count_cart]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm từ đơn hàng vào giỏ hàng!'
        ]);
    }

    public function printInvoice($id)
    {
        $order = Orders::with(['orderItems.product', 'user', 'address'])->findOrFail($id);

        if (Auth::check() && Auth::user()->role == 'user') {
            if ($order->user_id != Auth::user()->id) {
                return abort(403, 'Bạn không có quyền xem đơn hàng này.');
            }
        }

        $pdf = Pdf::loadView('orders.invoice-pdf', compact('order'));

        return $pdf->stream('hoa-don-' . $order->id . '.pdf');
    }
}
