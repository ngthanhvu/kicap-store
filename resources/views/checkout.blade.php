@extends('layouts.main')

@section('content')
    <style>
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

        .address-form .d-flex {
            flex-wrap: wrap;
            gap: 10px;
        }

        .address-form .w-50 {
            flex: 1;
            min-width: 48%;
        }
    </style>
    <div class="container tw-mt-5">
        <div class="steps">
            <div class="step active">
                <i class="fas fa-shopping-cart"></i>
                <p class="text-success">Giỏ hàng</p>
            </div>
            <span class="arrow text-success"><i class="fas fa-arrow-right"></i></span>
            <div class="step active">
                <i class="fas fa-credit-card"></i>
                <p class="text-success">Thanh toán</p>
            </div>
            <span class="arrow"><i class="fas fa-arrow-right"></i></span>
            <div class="step">
                <i class="fas fa-check-circle"></i>
                <p>Thành công</p>
            </div>
        </div>
        <h2 class="tw-mb-4 tw-text-[30px] tw-font-bold text-center">Thanh toán</h2>
        <div class="row">
            <!-- Thông tin khách hàng -->
            <div class="col-md-6">
                <h4 class="tw-text-lg tw-font-semibold">Thông tin khách hàng</h4>
                @if (!empty($addresses) && count($addresses) > 0)
                    <div id="existing-addresses" class="tw-mt-3 tw-border tw-p-3 tw-rounded">
                        @foreach ($addresses as $address)
                            <div class="form-check mb-2 rounded-3 d-flex align-items-center"
                                style="border: 1px solid #ccc; padding-left: 30px; padding-top: 10px; padding-bottom: 10px;">
                                <input class="form-check-input me-2" type="radio" name="address_id_temp"
                                    id="address{{ $address->id }}" value="{{ $address->id }}"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="address{{ $address->id }}">
                                    <strong>{{ $address->name }} - {{ $address->phone }}</strong> <br>
                                    {{ $address->street }} - {{ $address->ward }} - {{ $address->district }} -
                                    {{ $address->province }}
                                </label>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-link tw-mt-2 tw-p-0" id="toggle-address-form">Thêm địa chỉ
                            khác</button>
                    </div>
                @endif
                @if (empty($addresses) || count($addresses) == 0)
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i> Bạn chưa có địa chỉ nào. Vui lòng thêm địa chỉ giao
                        hàng.
                    </div>
                    <button type="button" class="btn btn-link tw-mt-2 tw-p-0" id="toggle-address-form">Thêm địa chỉ
                        khác</button>
                @endif

                <!-- Form thêm địa chỉ mới -->
                <form action="{{ route('address.store') }}" method="POST" id="new-address-form"
                    style="display: {{ empty($addresses) ? 'block' : 'none' }};" class="tw-mt-3 address-form">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control tw-border-gray-300" name="name"
                            placeholder="Nhập họ và tên">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control tw-border-gray-300" name="phone"
                            placeholder="Nhập số điện thoại">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <div class="d-flex">
                            <div class="w-50">
                                <label for="province">Tỉnh/Thành phố</label>
                                <select class="form-control form-select tw-border-gray-300" id="province">
                                    <option value="">Chọn tỉnh/thành phố</option>
                                </select>
                            </div>
                            <div class="w-50">
                                <label for="district">Quận/Huyện</label>
                                <select class="form-control form-select tw-border-gray-300" id="district">
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="w-50">
                                <label for="ward">Xã/Phường</label>
                                <select class="form-control form-select tw-border-gray-300" id="ward">
                                    <option value="">Chọn xã/phường</option>
                                </select>
                            </div>
                            <div class="w-50">
                                <label for="street">Số nhà, tên đường</label>
                                <input type="text" class="form-control tw-border-gray-300" id="street" name="street"
                                    placeholder="Nhập số nhà, tên đường">
                            </div>
                        </div>
                    </div>

                    <!-- Các input ẩn để gửi tên thay vì ID -->
                    <input type="hidden" name="province" id="province_name">
                    <input type="hidden" name="district" id="district_name">
                    <input type="hidden" name="ward" id="ward_name">
                    <input type="hidden" name="user_id" value="{{ optional(Auth::user())->id }}">
                    <input type="hidden" name="address" id="full_address">

                    <button type="submit" class="btn btn-dark">Thêm địa chỉ</button>
                </form>
            </div>

            <!-- Form Checkout -->
            <div class="col-md-6">
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <h4 class="tw-text-lg tw-font-semibold">Phương thức thanh toán</h4>
                    <div class="form-check tw-d-flex tw-align-items-center tw-mb-2"
                        style="border: 1px solid #ccc; padding-left: 30px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                        <input class="form-check-input tw-me-2" type="radio" name="payment_method" id="cod"
                            value="cod" checked>
                        <label class="form-check-label tw-d-flex tw-align-items-center" for="cod">
                            <img src="https://cdn-icons-png.flaticon.com/512/2897/2897832.png" alt="COD"
                                class="tw-w-5 tw-h-5 tw-me-2">
                            Thanh toán khi nhận hàng (COD)
                        </label>
                    </div>
                    <div class="form-check tw-d-flex tw-align-items-center tw-mb-2"
                        style="border: 1px solid #ccc; padding-left: 30px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                        <input class="form-check-input tw-me-2" type="radio" name="payment_method" id="vnpay"
                            value="vnpay">
                        <label class="form-check-label tw-d-flex tw-align-items-center" for="vnpay">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTp1v7T287-ikP1m7dEUbs2n1SbbLEqkMd1ZA&s"
                                alt="VNPay" class="tw-w-5 tw-h-5 tw-me-2">
                            VNPay
                        </label>
                    </div>
                    <div class="form-check tw-d-flex tw-align-items-center tw-mb-2"
                        style="border: 1px solid #ccc; padding-left: 30px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                        <input class="form-check-input tw-me-2" type="radio" name="payment_method" id="momo"
                            value="momo">
                        <label class="form-check-label tw-d-flex tw-align-items-center" for="momo">
                            <img src="https://play-lh.googleusercontent.com/uCtnppeJ9ENYdJaSL5av-ZL1ZM1f3b35u9k8EOEjK3ZdyG509_2osbXGH5qzXVmoFv0"
                                alt="MOMO" class="tw-w-5 tw-h-5 tw-me-2">
                            MOMO
                        </label>
                    </div>
                    <div class="form-check tw-d-flex tw-align-items-center tw-mb-2"
                        style="border: 1px solid #ccc; padding-left: 30px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                        <input class="form-check-input tw-me-2" type="radio" name="payment_method" id="paypal"
                            value="paypal">
                        <label class="form-check-label tw-d-flex tw-align-items-center" for="paypal">
                            <img src="https://cdn6.aptoide.com/imgs/7/3/6/7361965ab73672c20315b5e0f39f7ab4_icon.png"
                                alt="Paypal" class="tw-w-5 tw-h-5 tw-me-2">
                            PAYPAL
                        </label>
                    </div>

                    <h4 class="tw-mt-4 tw-text-lg tw-font-semibold">Tóm tắt đơn hàng</h4>
                    <ul class="list-group tw-mb-3">
                        @forelse ($carts as $cart)
                            <li class="list-group-item tw-d-flex tw-justify-between tw-align-items-center">
                                <div class="tw-d-flex tw-align-items-center tw-gap-3">
                                    @if ($cart->product->mainImage)
                                        <img src="{{ $cart->product->mainImage->image_url }}"
                                            alt="{{ $cart->product->name }}" class="product-image" width="50"
                                            height="50" style="border: 1px solid #ccc">
                                    @else
                                        <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                            alt="Default" class="product-image" width="50">
                                    @endif
                                    <span>{{ $cart->product->name }} (x{{ $cart->quantity }})</span>
                                </div>
                                <strong
                                    class="tw-absolute tw-right-4 tw-top-1/2 tw-transform -tw-translate-y-1/2">{{ number_format($cart->price * $cart->quantity) }}₫</strong>
                            </li>
                        @empty
                            <li class="list-group-item">Không có sản phẩm nào trong giỏ hàng</li>
                        @endforelse
                        <li class="list-group-item tw-d-flex tw-justify-between">
                            <label for="coupon_code" class="tw-mb-1">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="coupon_code" class="form-control tw-border-gray-300"
                                    placeholder="Nhập mã giảm giá">
                                <button type="button" id="apply_coupon"
                                    class="btn btn-success tw-bg-green-600 hover:tw-bg-green-700">Áp dụng</button>
                            </div>
                            <small id="coupon_message" class="tw-text-red-500"></small>
                        </li>
                        <li class="list-group-item tw-d-flex tw-justify-between">
                            <strong>Phí vận chuyển</strong>
                            <strong id="shipping_fee_display">20.000₫</strong>
                        </li>
                        <li class="list-group-item tw-d-flex tw-justify-between">
                            <strong>Tổng cộng</strong>
                            <strong
                                id="total_amount_display">{{ number_format($carts->sum(fn($cart) => $cart->price * $cart->quantity) + 20000) }}₫</strong>
                        </li>
                    </ul>

                    <!-- Hidden inputs để gửi dữ liệu -->
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="address_id" id="address_id"
                        value="{{ $addresses->first()->id ?? '' }}">
                    <input type="hidden" name="shipping_fee" id="shipping_fee" value="20000">
                    <input type="hidden" name="total_amount" id="total_amount"
                        value="{{ $carts->sum(fn($cart) => $cart->price * $cart->quantity) + 20000 }}">
                    <input type="hidden" name="discount" id="discount" value="0">
                    <button type="submit"
                        class="btn btn-primary tw-w-full tw-bg-blue-600 tw-text-white hover:tw-bg-blue-700">
                        Đặt hàng <i class="fa-solid fa-bag-shopping tw-ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        window.checkoutConfig = {
            isFormVisible: {{ empty($addresses) ? 'true' : 'false' }},
            originalSubtotal: {{ $carts->sum(fn($cart) => $cart->price * $cart->quantity) }},
            couponApplyUrl: "{{ route('coupon.apply') }}",
            addressStoreUrl: "{{ route('address.store') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection
