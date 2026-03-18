@extends('layouts.admin')

@section('content')
    <div class="p-3 mb-4 rounded-3 bg-light">
        <h2>Chỉnh sửa danh mục: {{ $category->name }}</h2>
    </div>

    <div class="container">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $category->name) }}">
                @error('name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">Danh mục cha</label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="">Không có danh mục cha</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                        @if ($cat->children->isNotEmpty())
                            @include('admin.categories.partials.category-options', [
                                'categories' => $cat->children,
                                'level' => 1,
                                'currentCategoryId' => $category->id,
                                'selectedCategoryId' => $category->parent_id,
                            ])
                        @endif
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" accept="image/*" id="image" name="image">
                <small>Hình ảnh hiện tại: <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                        width="50"></small>
                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
        </form>
    </div>
@endsection
