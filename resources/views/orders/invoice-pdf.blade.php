<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .invoice-box {
            padding: 30px;
        }

        .logo {
            width: 150px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <img src="https://bizweb.dktcdn.net/100/436/596/themes/980306/assets/logo.png?1741705947617" class="logo"
            alt="Logo Shop">

        <h2>Hóa đơn #{{ $order->id }}</h2>

        <p><strong>Khách hàng:</strong> {{ $order->user->name }}</p>
        <p><strong>Địa chỉ:</strong>
            {{ implode(', ', array_filter([$order->address->street, $order->address->ward, $order->address->district, $order->address->province])) }}
        </p>
        <p><strong>Số điện thoại:</strong> {{ $order->address->phone }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>

        <table width="100%" border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price) }}₫</td>
                        <td>{{ number_format($item->price * $item->quantity) }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="text-align:right;">Tổng tiền: {{ number_format($order->total_price) }}₫</h4>

        <div style="text-align: center; margin-top: 30px;">
            {!! QrCode::format('svg')->size(150)->generate('Order #' . $order->id) !!}
            <p>Mã đơn: #{{ $order->id }}</p>
        </div>
    </div>
</body>

</html>
