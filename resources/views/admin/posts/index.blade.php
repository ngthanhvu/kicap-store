@extends('layouts.admin')

@section('content')
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-3 bg-white tw-rounded-[15px] tw-pt-3 tw-pl-4">
        <div>
            <h3 class="tw-text-2xl tw-font-bold">Quản lý bài viết</h3>
            <p class="tw-text-gray-500 tw-mt-1">Danh sách các bài viết đang có!</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-outline-secondary tw-mr-[15px]">
            <i class="fa-solid fa-plus tw-mr-1"></i> Tạo bài viết mới
        </a>
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
                <form method="GET" action="{{ route('admin.users.index') }}" id="entriesForm">
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
                <form method="GET" action="{{ route('admin.users.index') }}">
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
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Nội dung</th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Người viết</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @php
                    $index = 1;
                @endphp
                @foreach ($posts as $post)
                    <tr>
                        <th scope="row">{{ $index++ }}</th>
                        <td class="tw-whitespace-nowrap tw-text-left">
                            {{ $post->title }}
                        </td>
                        <td class="tw-max-w-[300px] tw-truncate tw-text-left" title="{{ strip_tags($post->content) }}">
                            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}
                        </td>
                        <td>
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="img-thumbnail"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </td>
                        <td>{{ $post->slug }}</td>
                        <td>{{ $post->user->name ?? 'Không rõ' }}</td>
                        <td>
                            @if ($post->status == 1)
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-danger">Chưa duyệt</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                    class="btn btn-outline-secondary btn-sm me-1"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                    onsubmit="return confirm('Xác nhận xoá?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if ($posts->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="bi bi-inbox tw-text-[40px]"></i><br>
                            Không có bài viết nào
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="row">
            <div class="col-md-6">
                <p>Hiển thị {{ $posts->firstItem() }} đến {{ $posts->lastItem() }} trong {{ $posts->total() }}
                    mục</p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <!-- Nút Previous -->
                        <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $posts->previousPageUrl() . '&per_page=' . $perPage . '&search=' . $search }}"
                                tabindex="-1">«</a>
                        </li>

                        <!-- Các trang -->
                        @for ($i = 1; $i <= $posts->lastPage(); $i++)
                            <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $posts->url($i) . '&per_page=' . $perPage . '&search=' . $search }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Nút Next -->
                        <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ $posts->nextPageUrl() . '&per_page=' . $perPage . '&search=' . $search }}">»</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
