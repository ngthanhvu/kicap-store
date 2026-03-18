@extends('layouts.admin')

@section('content')
    <div class="tw-mb-5">
        <h3 class="tw-text-3xl tw-font-bold text-center tw-mb-3">Chỉnh sửa bài viết</h3>
    </div>

    <div class="container tw-w-[70%] tw-bg-white tw-p-5 tw-rounded-[15px]">
        <form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data"
            onsubmit="syncQuillContent()">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="{{ old('title', $post->title) }}">
                @error('title')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description', $post->description) }}">
                @error('description')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh</label>
                <input class="form-control" type="file" id="image" name="image">
                @if ($post->image)
                    <div class="mt-2">
                        <img src="{{ $post->image_url }}" alt="Ảnh hiện tại" width="150">
                    </div>
                @endif
                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3 d-none">
                <input type="hidden" class="form-control" name="user_id" value="{{ Auth::user()->id }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <div id="quill-editor" style="height: 400px;">{!! old('content', $post->content) !!}</div>
                <textarea name="content" id="content" class="d-none">{!! old('content', $post->content) !!}</textarea>
                @error('content')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status">
                    <option value="0" {{ $post->status == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                    <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Đã duyệt</option>
                </select>
                @error('status')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-outline-success me-2">Cập nhật</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Huỷ</a>
        </form>
    </div>

    <script>
        const quill = new Quill('#quill-editor', {
            theme: 'snow'
        });

        function syncQuillContent() {
            document.querySelector('#content').value = quill.root.innerHTML;
        }
    </script>
@endsection
