@extends('layouts.admin')

@section('content')
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-4 bg-white tw-rounded-[15px] tw-p-4">
        <div>
            <h3 class="tw-text-2xl tw-font-bold">Cài đặt slider trang chủ</h3>
            <p class="tw-text-gray-500 tw-mt-1">Thay ảnh, tiêu đề và nút cho slider ngoài trang chủ.</p>
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

    <div class="bg-white tw-p-5 tw-rounded-[15px]">
        <form action="{{ route('admin.settings.home-slider.update') }}" method="POST">
            @csrf
            @method('PUT')

            @foreach ($slides as $index => $slide)
                <div class="tw-border tw-border-slate-200 tw-rounded-xl tw-p-4 tw-mb-4">
                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                        <h4 class="tw-text-lg tw-font-semibold">Slide {{ $index + 1 }}</h4>
                        <span class="tw-text-sm tw-text-slate-500">Khuyên dùng ảnh ngang 1400x520</span>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ảnh URL</label>
                            <input type="text" name="slides[{{ $index }}][image]" class="form-control"
                                value="{{ old("slides.$index.image", $slide['image'] ?? '') }}"
                                placeholder="https://...">
                            @error("slides.$index.image")
                                <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Link nút</label>
                            <input type="text" name="slides[{{ $index }}][button_link]" class="form-control"
                                value="{{ old("slides.$index.button_link", $slide['button_link'] ?? '') }}"
                                placeholder="/san-pham">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="slides[{{ $index }}][title]" class="form-control"
                                value="{{ old("slides.$index.title", $slide['title'] ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nút CTA</label>
                            <input type="text" name="slides[{{ $index }}][button_text]" class="form-control"
                                value="{{ old("slides.$index.button_text", $slide['button_text'] ?? '') }}"
                                placeholder="Xem ngay">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Mô tả ngắn</label>
                            <input type="text" name="slides[{{ $index }}][subtitle]" class="form-control"
                                value="{{ old("slides.$index.subtitle", $slide['subtitle'] ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Xem nhanh</label>
                            <img src="{{ $slide['image'] ?? 'https://placehold.co/1400x520?text=Preview' }}"
                                alt="Preview slide {{ $index + 1 }}" class="img-fluid rounded border"
                                style="max-height: 220px; object-fit: cover; width: 100%;">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="tw-flex tw-justify-end">
                <button type="submit" class="btn btn-outline-secondary">Lưu cài đặt slider</button>
            </div>
        </form>
    </div>
@endsection
