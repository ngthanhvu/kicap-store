@extends('layouts.main')

@section('content')
    <style>
        .cart-table th,
        .cart-table td {
            vertical-align: middle;
        }

        .cart-table img {
            max-width: 80px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
        }

        .quantity-control button {
            width: 30px;
            height: 30px;
            padding: 0;
        }

        .quantity-control input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            padding: 0;
            font-size: 16px;
        }

        .quantity-control input:focus {
            outline: none;
        }

        .total-section {
            padding-top: 20px;
        }

        .steps {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 20px auto;
        }

        .step {
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .step i {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .step p {
            margin: 0;
            font-size: 1.1em;
        }

        .arrow {
            font-size: 1.5em;
            color: #666;
        }

        .step.active i {
            color: #28a745;
        }
    </style>
    <div class="container mt-5 mb-5">
        <div class="steps">
            <div class="step active">
                <i class="fas fa-shopping-cart"></i>
                <p class="text-success">Giỏ hàng</p>
            </div>
            <span class="arrow"><i class="fas fa-arrow-right"></i></span>
            <div class="step">
                <i class="fas fa-credit-card"></i>
                <p>Thanh toán</p>
            </div>
            <span class="arrow"><i class="fas fa-arrow-right"></i></span>
            <div class="step">
                <i class="fas fa-check-circle"></i>
                <p>Thành công</p>
            </div>
        </div>

        <h2 class="text-center mb-4">Giỏ Hàng Của Bạn</h2>

        <!-- Bảng giỏ hàng -->
        <div class="table-responsive">
            <table class="table cart-table text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sản Phẩm</th>
                        <th scope="col">Tên</th>
                        <th scope="col">Phiên Bản</th>
                        <th scope="col">Đơn Giá</th>
                        <th scope="col">Số Lượng</th>
                        <th scope="col">Tổng</th>
                        <th scope="col">Hành Động</th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    @forelse ($carts as $cart)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($cart->product->mainImage)
                                    <img src="{{ $cart->product->mainImage->image_url }}"
                                        alt="{{ $cart->product->name }}">
                                @else
                                    <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                        alt="Keycap Artisan Natra">
                                @endif
                            </td>
                            <td>{{ $cart->product->name }}</td>
                            <td>{{ $cart->variant ? $cart->variant->varriant_name : 'Không có phiên bản' }}</td>
                            <td data-price="{{ $cart->price }}">{{ number_format($cart->price) }}₫</td>
                            <td>
                                <div class="quantity-control">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity(this, -1)">-</button>
                                    <input type="number" class="quantity-input" value="{{ $cart->quantity }}"
                                        min="1" readonly>
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                            </td>
                            <td class="subtotal">{{ number_format($cart->price * $cart->quantity) }}₫</td>
                            <td>
                                <form action="{{ route('carts.delete', $cart->id) }}" method="POST"
                                    onsubmit="isDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <i class="bi bi-inbox tw-text-[40px]"></i><br>
                                Không có sản phẩm nào trong giỏ hàng
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row total-section">
            <div class="col-md-4 offset-md-8">
                @if ($carts->isEmpty())
                    <h4>Tổng Tiền: <span id="cartTotal">0₫</span></h4>
                @else
                    <h4>Tổng Tiền: <span
                            id="cartTotal">{{ number_format($carts->sum(fn($cart) => $cart->price * $cart->quantity)) }}₫</span>
                    </h4>
                @endif
                <a href="/checkout" class="btn btn-success w-100 mt-3"><i class="fa-solid fa-credit-card"></i> Thanh
                    Toán</a>
                <a href="/san-pham" class="btn btn-outline-dark w-100 mt-2"><i class="fa-solid fa-cart-plus"></i> Tiếp Tục
                    Mua Sắm</a>
            </div>
        </div>
    </div>

    <script>
        function isDelete(event, form) {
            event.preventDefault();
            const cartId = form.querySelector('input[name="cart_id"]').value;

            const result = Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa sản phẩm này?',
                text: "Bạn sẽ không thể khôi phục lại sản phẩm này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })

        }

        async function changeQuantity(button, change) {
            const row = button.closest('tr');
            const input = row.querySelector('.quantity-input');
            let quantity = parseInt(input.value) || 1;
            quantity += change;

            if (quantity < 1) quantity = 1;
            input.value = quantity;

            const cartId = row.querySelector('input[name="cart_id"]').value;

            try {
                const response = await fetch(`/gio-hang/${cartId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity
                    })
                });

                const data = await response.json();
                if (data.status === "success") {
                    updateTotal();
                    iziToast.success({
                        title: 'Thành công',
                        message: 'Cập nhật giỏ hàng thành công!',
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        title: 'Lỗi',
                        message: data.message,
                        position: 'topRight'
                    });
                    console.log("Lỗi: " + data.message);
                }
            } catch (error) {
                console.error("Lỗi khi cập nhật giỏ hàng:", error);
            }
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('tbody tr').forEach(row => {
                const price = parseInt(row.querySelector('[data-price]').dataset.price) || 0;
                const quantity = parseInt(row.querySelector('.quantity-input').value) || 1;
                const subtotal = price * quantity;

                row.querySelector('.subtotal').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + "₫";
                total += subtotal;
            });

            document.getElementById('cartTotal').textContent = new Intl.NumberFormat('vi-VN').format(total) + "₫";
        }
        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>
@endsection
