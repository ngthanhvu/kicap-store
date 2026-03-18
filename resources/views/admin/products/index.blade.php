@extends('layouts.admin')

@section('content')
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-3 bg-white tw-rounded-[15px] tw-pt-3 tw-pl-4">
        <div>
            <h3 class="tw-text-2xl tw-font-bold">Quản lý sản phẩm</h3>
            <p class="tw-text-gray-500 tw-mt-1">Danh sách các sản phẩm đang có!</p>
        </div>
        <button type="button" class="btn btn-outline-primary me-3" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">
            <i class="fa-solid fa-file-csv"></i> Upload Excel
        </button>
    </div>
    <!-- Modal Upload Excel -->
    <div class="modal fade" id="uploadExcelModal" tabindex="-1" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadExcelModalLabel">Upload File Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Chọn file Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls" required>
                        </div>
                        <p class="text-muted">File Excel cần có các cột: name, price, discount_price, quantity, category_id,
                            description.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif
    <div class="bg-white tw-p-5 tw-rounded-[15px]">
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.products.index') }}" id="entriesForm">
                    <label for="entriesPerPage" class="form-label">Hiển thị</label>
                    <select id="entriesPerPage" name="per_page" class="form-select d-inline w-auto"
                        style="width: auto; display: inline-block;"
                        onchange="document.getElementById('entriesForm').submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span> mục trên mỗi trang</span>
                    <input type="hidden" name="search" value="{{ $search }}">
                </form>
            </div>
            <div class="col-md-3 offset-md-3">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..."
                        value="{{ $search }}" aria-label="Search">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                </form>
            </div>
        </div>

        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên sản phẩm</th>
                    <th scope="col">Danh mục</th>
                    <th scope="col">Giá nhập</th>
                    <th scope="col">Giá bán</th>
                    <th scope="col">Giá khuyến mãi</th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Biến thể</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @php $index = 1; @endphp
                @foreach ($products as $product)
                    <tr>
                        <th scope="row">{{ $index++ }}</th>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                        <td>{{ number_format($product->original_price) }} đ</td>
                        <td>{{ number_format($product->price) }} đ</td>
                        <td>{{ number_format($product->discount_price) }} đ</td>
                        <td>
                            @if ($product->mainImage)
                                <img src="{{ $product->mainImage->image_url }}"
                                    alt="{{ $product->name }}" style="width: 100px;">
                            @else
                                Không có ảnh
                            @endif
                        </td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            @if ($product->variants->isEmpty())
                                Không có
                            @else
                                @foreach ($product->variants as $variant)
                                    <div>
                                        <strong>{{ $variant->varriant_name }}</strong>
                                        - Giá: {{ number_format($variant->varriant_price) }} đ
                                        - SL: {{ $variant->varriant_quantity }}
                                    </div>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $product->slug }}</td>
                        <td>
                            <a href="/admin/products/{{ $product->id }}/edit"
                                class="btn btn-outline-secondary btn-sm edit-btn"><i class="fa fa-edit"></i></a>
                            <form action="/admin/products/{{ $product->id }}" method="POST" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-secondary btn-sm delete-btn"><i
                                        class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center">
                            <i class="bi bi-inbox tw-text-[40px]"></i><br>
                            Không có sản phẩm nào
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="row">
            <div class="col-md-6">
                <p>Hiển thị {{ $products->firstItem() }} đến {{ $products->lastItem() }} trong {{ $products->total() }}
                    mục</p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $products->previousPageUrl() . '&per_page=' . $perPage . '&search=' . $search }}"
                                tabindex="-1">«</a>
                        </li>
                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $products->url($i) . '&per_page=' . $perPage . '&search=' . $search }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ $products->nextPageUrl() . '&per_page=' . $perPage . '&search=' . $search }}">»</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Xử lý nút Xóa
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let categoryId = this.getAttribute("data-id");
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Bạn có chắc chắn muốn xóa?",
                        text: "Dữ liệu này sẽ không thể khôi phục!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Xóa ngay!",
                        cancelButtonText: "Hủy"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Xử lý nút Sửa (Nếu muốn hiển thị cảnh báo khi bấm "Sửa")
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    let editUrl = this.getAttribute("href");

                    Swal.fire({
                        title: "Bạn có muốn chỉnh sửa?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Chỉnh sửa",
                        cancelButtonText: "Hủy"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                editUrl;
                        }
                    });
                });
            });
        });
    </script>
@endsection
