@extends('layouts.admin')

@section('content')
    <h3 class="tw-text-2xl tw-font-bold tw-mb-6">Trang chủ admin</h3>



    <!-- Stats Cards -->
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-4 tw-mb-6">
        <!-- Card 1: Tổng người dùng -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold">Tổng người dùng</h5>
                <p class="card-text tw-text-3xl tw-font-bold tw-text-blue-600"><i class="fa-solid fa-users"></i>
                    {{ $totalUsers }} người</p>
            </div>
        </div>
        <!-- Card 2: Tổng doanh thu -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold">Tổng doanh thu</h5>
                <p class="card-text tw-text-3xl tw-font-bold tw-text-green-600">
                    <i class="fa-solid fa-chart-pie"></i> {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ
                </p>
            </div>
        </div>
        <!-- Card 3: Doanh thu thực tế -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold">Doanh thu thực tế</h5>
                <p class="card-text tw-text-3xl tw-font-bold tw-text-teal-600">
                    <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($actualRevenue, 0, ',', '.') }} VNĐ
                </p>
            </div>
        </div>
        <!-- Card 4: Tổng đơn hàng -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold">Số đơn hàng</h5>
                <p class="card-text tw-text-3xl tw-font-bold tw-text-purple-600"><i class="fa-brands fa-dropbox"></i>
                    {{ $totalOrders }} đơn</p>
            </div>
        </div>
        <!-- Card 5: Tổng tồn kho -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold">Tổng tồn kho</h5>
                <p class="card-text tw-text-3xl tw-font-bold tw-text-orange-600"><i class="fa-solid fa-database"></i>
                    {{ $totalStock }} sản phẩm</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="tw-mb-8 tw-p-4 tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200">
        <h4 class="tw-text-xl tw-font-semibold tw-mb-4 tw-text-gray-800">🔍 Bộ lọc dữ liệu chart</h4>

        <div class="tw-flex tw-flex-col md:tw-flex-row tw-flex-wrap tw-gap-4 tw-items-center">
            <!-- Loại bộ lọc -->
            <div class="tw-flex tw-items-center tw-gap-2">
                <label for="filter_type" class="tw-font-medium tw-text-gray-700">Loại:</label>
                <select id="filter_type"
                    class="tw-border tw-border-gray-300 tw-rounded-md tw-px-3 tw-py-2 tw-text-sm tw-bg-white tw-shadow-sm">
                    <option value="month" {{ $filterType === 'month' ? 'selected' : '' }}>Theo tháng</option>
                    <option value="day" {{ $filterType === 'day' ? 'selected' : '' }}>Theo ngày</option>
                </select>
            </div>

            <!-- Bộ lọc theo tháng -->
            <div id="month_filter" class="{{ $filterType === 'day' ? 'tw-hidden' : '' }} tw-flex tw-items-center tw-gap-2">
                <label for="year" class="tw-font-medium tw-text-gray-700">Năm:</label>
                <select id="year"
                    class="tw-border tw-border-gray-300 tw-rounded-md tw-px-3 tw-py-2 tw-text-sm tw-bg-white tw-shadow-sm">
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>

                <label for="month" class="tw-font-medium tw-text-gray-700">Tháng:</label>
                <select id="month"
                    class="tw-border tw-border-gray-300 tw-rounded-md tw-px-3 tw-py-2 tw-text-sm tw-bg-white tw-shadow-sm">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Bộ lọc theo ngày -->
            <div id="day_filter" class="{{ $filterType === 'month' ? 'tw-hidden' : '' }} tw-flex tw-items-center tw-gap-2">
                <label for="start_date" class="tw-font-medium tw-text-gray-700">Từ ngày:</label>
                <input type="date" id="start_date" value="{{ $startDate ?? '' }}"
                    class="tw-border tw-border-gray-300 tw-rounded-md tw-px-3 tw-py-2 tw-text-sm tw-bg-white tw-shadow-sm">

                <label for="end_date" class="tw-font-medium tw-text-gray-700">Đến ngày:</label>
                <input type="date" id="end_date" value="{{ $endDate ?? '' }}"
                    class="tw-border tw-border-gray-300 tw-rounded-md tw-px-3 tw-py-2 tw-text-sm tw-bg-white tw-shadow-sm">
            </div>

            <!-- Nút áp dụng -->
            <div>
                <button id="apply_filter"
                    class="tw-text-blue-700 hover:tw-text-white tw-border tw-border-blue-700 hover:tw-bg-blue-800 tw-focus:ring-4 tw-focus:outline-none tw-focus:ring-blue-300 tw-font-medium tw-rounded-lg tw-text-sm tw-px-5 tw-py-2.5 tw-text-center tw-me-2 tw-mb-2 dark:tw-border-blue-500 dark:tw-text-blue-500 dark:hover:tw-text-white dark:hover:tw-bg-blue-500 dark:tw-focus:ring-blue-800">
                    Áp dụng
                </button>
            </div>
        </div>
    </div>


    <!-- Chart Section -->
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4 tw-mb-6">
        <!-- Biểu đồ đường -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold tw-mb-4">Doanh thu</h5>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
        <!-- Biểu đồ cột -->
        <div class="card tw-shadow-md">
            <div class="card-body">
                <h5 class="card-title tw-text-lg tw-font-semibold tw-mb-4">Đơn hàng, Sản phẩm, Người dùng</h5>
                <canvas id="usersChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Bảng sản phẩm bán chạy -->
    <div class="tw-mb-6">
        <h3 class="tw-text-xl tw-font-semibold tw-mb-3">Sản phẩm bán chạy</h3>
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @foreach ($topProducts as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ $product->product->mainImage?->image_url }}"
                                alt="{{ $product->product->name }}" class="tw-w-16 tw-h-16 tw-object-cover">
                        </td>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td>{{ number_format($product->total_revenue, 0, ',', '.') }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        let revenueChartInstance = null;
        let usersChartInstance = null;

        // Hàm khởi tạo biểu đồ
        function initializeCharts(labels, revenueData, actualRevenueData, orderData, productData, userData) {
            // Hủy biểu đồ cũ nếu tồn tại
            if (revenueChartInstance) {
                revenueChartInstance.destroy();
            }
            if (usersChartInstance) {
                usersChartInstance.destroy();
            }

            // Biểu đồ doanh thu (Biểu đồ đường)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChartInstance = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: revenueData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Doanh thu thực tế (VNĐ)',
                            data: actualRevenueData,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Biểu đồ người dùng (Biểu đồ cột)
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            usersChartInstance = new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Đơn hàng',
                            data: orderData,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Sản phẩm bán ra',
                            data: productData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Người dùng mới',
                            data: userData,
                            backgroundColor: 'rgba(153, 102, 255, 0.6)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Khởi tạo biểu đồ ban đầu
        initializeCharts(
            @json($labels),
            [
                @foreach ($monthlyRevenue as $data)
                    {{ $data->revenue }},
                @endforeach
            ],
            [
                @foreach ($monthlyRevenue as $data)
                    @php
                        $actual = $monthlyActualRevenue->firstWhere($filterType === 'day' ? 'date' : 'month', $data->month);
                    @endphp
                    {{ $actual ? $actual->actual_revenue : 0 }},
                @endforeach
            ],
            @json($orderData),
            @json($productData),
            @json($userData)
        );

        // Xử lý sự kiện thay đổi loại bộ lọc
        document.getElementById('filter_type').addEventListener('change', function() {
            const filterType = this.value;
            document.getElementById('month_filter').classList.toggle('tw-hidden', filterType === 'day');
            document.getElementById('day_filter').classList.toggle('tw-hidden', filterType === 'month');
        });

        // Xử lý sự kiện áp dụng bộ lọc
        document.getElementById('apply_filter').addEventListener('click', function() {
            const filterType = document.getElementById('filter_type').value;
            const params = new URLSearchParams();

            params.append('filter_type', filterType);
            if (filterType === 'month') {
                params.append('year', document.getElementById('year').value);
                params.append('month', document.getElementById('month').value);
            } else {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (startDate && endDate) {
                    params.append('start_date', startDate);
                    params.append('end_date', endDate);
                } else {
                    alert('Vui lòng chọn ngày bắt đầu và kết thúc.');
                    return;
                }
            }

            // Gửi yêu cầu AJAX
            fetch('{{ route('admin.index') }}?' + params.toString(), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    // Cập nhật biểu đồ
                    initializeCharts(
                        data.labels,
                        data.monthlyRevenue.map(item => item.revenue),
                        data.monthlyActualRevenue.map(item => item.actual_revenue),
                        data.orderData,
                        data.productData,
                        data.userData
                    );

                    // Cập nhật bảng sản phẩm bán chạy
                    const tableBody = document.querySelector('.table tbody');
                    tableBody.innerHTML = '';
                    data.topProducts.forEach((product, index) => {
                        const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td><img src="${product.product.mainImage.image_url ?? ''}" alt="${product.product.name}" class="tw-w-16 tw-h-16 tw-object-cover"></td>
                            <td>${product.product.name}</td>
                            <td>${product.total_quantity}</td>
                            <td>${new Intl.NumberFormat('vi-VN').format(product.total_revenue)} VNĐ</td>
                        </tr>
                    `;
                        tableBody.innerHTML += row;
                    });

                    // Cập nhật các thẻ thống kê
                    document.querySelector('.tw-text-green-600').textContent = new Intl.NumberFormat('vi-VN')
                        .format(data.totalRevenue) + ' VNĐ';
                    document.querySelector('.tw-text-teal-600').textContent = new Intl.NumberFormat('vi-VN')
                        .format(data.actualRevenue) + ' VNĐ';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
