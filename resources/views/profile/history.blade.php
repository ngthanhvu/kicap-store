@extends('layouts.main')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                @include('profile.includes.sidebar')
            </div>

            <div class="col-md-9 col-lg-10 profile-content">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title">Lịch sử đơn hàng</h4>
                            <form method="GET" action="{{ route('history') }}" class="mb-4 d-flex">
                                <select name="status" class="form-select w-auto me-2" onchange="this.form.submit()">
                                    <option value="">-- Tất cả trạng thái --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa
                                        thanh
                                        toán</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                                    </option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy
                                    </option>
                                    <option value="fail" {{ request('status') == 'fail' ? 'selected' : '' }}>Thất bại
                                    </option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã
                                        giao
                                        hàng</option>
                                </select>
                                <noscript><button type="submit" class="btn btn-primary">Lọc</button></noscript>
                            </form>
                        </div cla>

                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Mã Đơn Hàng</th>
                                    <th scope="col">Trạng Thái</th>
                                    <th scope="col">Tổng Tiền</th>
                                    <th scope="col">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $index = 1; @endphp
                                {{-- @dd($orders) --}}
                                @forelse ($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $index++ }}</th>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span
                                                    class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-yellow-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-yellow-800 tw-ring-1 tw-ring-yellow-600/20 tw-ring-inset">
                                                    Chưa thanh toán
                                                </span>
                                            @elseif($order->status == 'paid')
                                                <span
                                                    class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-green-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-green-800 tw-ring-1 tw-ring-green-600/20 tw-ring-inset">
                                                    Đã thanh toán
                                                </span>
                                            @elseif($order->status == 'canceled')
                                                <span
                                                    class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-red-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-red-800 tw-ring-1 tw-ring-red-600/20 tw-ring-inset">
                                                    Đã hủy
                                                </span>
                                            @elseif($order->status == 'fail')
                                                <span
                                                    class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-gray-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-gray-800 tw-ring-1 tw-ring-gray-600/20 tw-ring-inset">
                                                    Thất bại
                                                </span>
                                            @elseif($order->status == 'delivered')
                                                <span
                                                    class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-green-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-green-800 tw-ring-1 tw-ring-green-600/20 tw-ring-inset">
                                                    Đã giao hàng
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->total_price) }}₫</td>
                                        <td>
                                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#orderDetailModal{{ $order->id }}"><i
                                                    class="fa-solid fa-eye"></i> Chi tiết</button>
                                            @if ($order->status == 'pending')
                                                <button onclick="cancelOrder({{ $order->id }})"
                                                    class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-ban"></i>
                                                    Huỷ đơn</button>
                                            @else
                                                <button onclick="reorder({{ $order->id }})"
                                                    class="btn btn-outline-secondary btn-sm"><i
                                                        class="fa-solid fa-rotate-left"></i> Mua lại</button>
                                            @endif
                                            <a href="{{ route('orders.printInvoice', $order->id) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm no-loading">
                                                <i class="fa fa-print"></i> In hóa đơn
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="bi bi-inbox tw-text-[40px]"></i><br>
                                            Không có đơn hàng nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>

                        @foreach ($orders as $order)
                            <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
                                aria-labelledby="orderDetailModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng
                                                #{{ $order->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="tw-flex tw-items-center tw-justify-between">
                                                <!-- 1. Đã đặt hàng -->
                                                <div class="tw-flex-1 tw-flex tw-flex-col tw-items-center">
                                                    <div class="tw-relative tw-mb-2">
                                                        <div
                                                            class="tw-w-10 tw-h-10 tw-bg-blue-500 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white">
                                                            <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="tw-text-center">
                                                        <h3 class="tw-text-[17px] tw-text-gray-900">Đã đặt hàng</h3>
                                                        <p class="tw-text-xs tw-text-gray-500">Tiếp nhận đơn</p>
                                                    </div>
                                                </div>
                                                <!-- Mũi tên 1 -->
                                                <div class="tw-flex-1 tw-flex tw-items-center tw-justify-center">
                                                    <svg class="tw-w-6 tw-h-6" fill="none"
                                                        stroke="{{ $order->status == 'pending' || $order->status == 'paid' || $order->status == 'delivered' ? '#3B82F6' : '#D1D5DB' }}"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                                <!-- 2. Đang thanh toán -->
                                                <div class="tw-flex-1 tw-flex tw-flex-col tw-items-center">
                                                    <div class="tw-relative tw-mb-2">
                                                        <div
                                                            class="tw-w-10 tw-h-10 {{ $order->status == 'pending' || $order->status == 'paid' || $order->status == 'delivered' ? 'tw-bg-yellow-500' : 'tw-bg-gray-300' }} tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white">
                                                            <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="tw-text-center">
                                                        <h3 class="tw-text-[17px] tw-text-gray-900">Đang thanh toán</h3>
                                                        <p class="tw-text-xs tw-text-gray-500">Chờ xử lý</p>
                                                    </div>
                                                </div>
                                                <!-- Mũi tên 2 -->
                                                <div class="tw-flex-1 tw-flex tw-items-center tw-justify-center">
                                                    <svg class="tw-w-6 tw-h-6" fill="none"
                                                        stroke="{{ $order->status == 'paid' || $order->status == 'delivered' ? '#EAB308' : '#D1D5DB' }}"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                                <!-- 3. Thanh toán thành công -->
                                                <div class="tw-flex-1 tw-flex tw-flex-col tw-items-center">
                                                    <div class="tw-relative tw-mb-2">
                                                        <div
                                                            class="tw-w-10 tw-h-10 {{ $order->status == 'paid' || $order->status == 'delivered' ? 'tw-bg-green-500' : 'tw-bg-gray-300' }} tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white">
                                                            <svg class="tw-w-6 tw-h-6" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="tw-text-center">
                                                        <h3
                                                            class="tw-text-[17px] {{ $order->status == 'paid' || $order->status == 'delivered' ? 'tw-text-gray-900' : 'tw-text-gray-400' }}">
                                                            Thanh toán</h3>
                                                        <p
                                                            class="tw-text-xs {{ $order->status == 'paid' || $order->status == 'delivered' ? 'tw-text-gray-500' : 'tw-text-gray-400' }}">
                                                            Hoàn tất thanh toán</p>
                                                    </div>
                                                </div>
                                                <!-- Mũi tên 3 -->
                                                <div class="tw-flex-1 tw-flex tw-items-center tw-justify-center">
                                                    <svg class="tw-w-6 tw-h-6" fill="none"
                                                        stroke="{{ $order->status == 'delivered' ? '#10B981' : '#D1D5DB' }}"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                                <!-- 4. Giao hàng thành công -->
                                                <div class="tw-flex-1 tw-flex tw-flex-col tw-items-center">
                                                    <div class="tw-relative tw-mb-2">
                                                        <div
                                                            class="tw-w-10 tw-h-10 {{ $order->status == 'delivered' ? 'tw-bg-green-500' : 'tw-bg-gray-300' }} tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white">
                                                            <svg class="tw-w-6 tw-h-6" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="tw-text-center">
                                                        <h3
                                                            class="tw-text-[17px] {{ $order->status == 'delivered' ? 'tw-text-gray-900' : 'tw-text-gray-400' }}">
                                                            Giao hàng</h3>
                                                        <p
                                                            class="tw-text-xs {{ $order->status == 'delivered' ? 'tw-text-gray-500' : 'tw-text-gray-400' }}">
                                                            Hoàn tất giao hàng</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
                                            <p><strong>Trạng thái:</strong>
                                                @if ($order->status == 'pending')
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-yellow-50 tw-px-2 tw-py-1 tw-text-xs tw-font-small tw-text-yellow-800 tw-ring-1 tw-ring-yellow-600/20 tw-ring-inset">Chưa
                                                        thanh toán</span>
                                                @elseif($order->status == 'paid')
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-green-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-green-800 tw-ring-1 tw-ring-green-600/20 tw-ring-inset">Đã
                                                        thanh toán</span>
                                                @elseif($order->status == 'canceled')
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-red-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-red-800 tw-ring-1 tw-ring-red-600/20 tw-ring-inset">Đã
                                                        hủy</span>
                                                @elseif($order->status == 'fail')
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-gray-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-gray-800 tw-ring-1 tw-ring-gray-600/20 tw-ring-inset">Thất
                                                        bại</span>
                                                @elseif($order->status == 'delivered')
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-green-50 tw-px-2 tw-py-1 tw-text-xs tw-font-medium tw-text-green-800 tw-ring-1 tw-ring-green-600/20 tw-ring-inset">Đã
                                                        giao hàng</span>
                                                @endif
                                            </p>
                                            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }}₫</p>

                                            <h5>Sản phẩm trong đơn hàng:</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->orderItems as $item)
                                                        <tr>
                                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>{{ number_format($item->price) }}₫</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function cancelOrder(orderId) {
            const result = await Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ hủy đơn hàng này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, hủy đơn!',
                cancelButtonText: 'Không, giữ lại'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/profile/cancel-order/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        iziToast.success({
                            title: 'Thành công',
                            message: 'Đơn hàng đã được hủy thành công!',
                            position: 'topRight'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        iziToast.error({
                            title: 'Lỗi',
                            message: 'Có lỗi xảy ra, vui lòng thử lại!',
                            position: 'topRight'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    iziToast.error({
                        title: 'Lỗi',
                        message: 'Đã xảy ra lỗi hệ thống!',
                        position: 'topRight'
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            }
        }

        async function reorder(orderId) {
            const result = await Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ thêm các sản phẩm từ đơn hàng này vào giỏ hàng!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, thêm vào!',
                cancelButtonText: 'Không, hủy bỏ'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/profile/reorder/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        iziToast.success({
                            title: 'Thành công',
                            message: 'Đơn hàng đã được thêm vào giỏ hàng!',
                            position: 'topRight'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        iziToast.error({
                            title: 'Lỗi',
                            message: data.message || 'Có lỗi xảy ra, vui lòng thử lại!',
                            position: 'topRight'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    iziToast.error({
                        title: 'Lỗi',
                        message: 'Đã xảy ra lỗi hệ thống!',
                        position: 'topRight'
                    });
                }
            }
        }
    </script>
@endsection
