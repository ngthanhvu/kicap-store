@extends('layouts.main')

@section('content')
    <div class="container my-5">
        <!-- Tiêu đề bài viết -->
        <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>

        <!-- Thông tin phụ -->
        <div class="text-muted mb-4">
            <small>
                <i class="bi bi-person-circle"></i> Tác giả: <strong>{{ $post->user->name }}</strong> |
                <i class="bi bi-calendar-event"></i> Ngày đăng: {{ $post->created_at }} |
                <i class="bi bi-eye"></i> Lượt xem: 1234
            </small>
        </div>

        <!-- Ảnh đại diện -->
        <div class="text-center mb-2">
            <img src="{{ $post->image_url }}" class="img-fluid rounded mb-4" alt="Ảnh bài viết">

        </div>
        <!-- Nội dung chính -->
        <article class="fs-5 lh-lg">
            {!! $post->content !!}
        </article>

        <!-- Tags -->
        <div class="mt-3">
            <span class="badge bg-primary">Tin tức</span>
            <span class="badge bg-secondary">Công nghệ</span>
            <span class="badge bg-info text-dark">Blog</span>
        </div>

        <!-- Phần bình luận (tuỳ chọn) -->
        <div class="mt-5">
            <h4 class="mb-3">Bình luận</h4>
            <form>
                <div class="mb-3">
                    <label for="comment" class="form-label">Viết bình luận:</label>
                    <textarea class="form-control" id="comment" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi</button>
            </form>
        </div>
    </div>
@endsection
