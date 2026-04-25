@extends('layouts.admin')

@section('content')
    <div class="tw-mb-5">
        <h3 class="tw-text-3xl tw-font-bold tw-text-center tw-mb-3">Thêm sản phẩm</h3>
    </div>

    <div class="container bg-white tw-p-5 tw-rounded-[15px]">
        <form action="/admin/products" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Cột trái -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nhập tên sản phẩm">
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá nhập</label>
                        <input type="number" min="0" class="form-control" id="original_price" name="original_price"
                            placeholder="Nhập giá sản phẩm">
                        @error('original_price')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá bán</label>
                        <input type="number" min="0" class="form-control" id="price" name="price"
                            placeholder="Nhập giá sản phẩm">
                        @error('price')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_price" class="form-label">Giá giảm</label>
                        <input type="number" min="0" class="form-control" id="discount_price" name="discount_price"
                            placeholder="Nhập giá giảm (nếu có)">
                        @error('discount_price')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng</label>
                        <input type="number" min="0" class="form-control" id="quantity" name="quantity"
                            placeholder="Nhập số lượng">
                        @error('quantity')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Cột phải -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="main_image" class="form-label">Ảnh chính</label>
                        <div class="custom-file-upload">
                            <input type="file" class="d-none" id="main_image" name="main_image" accept="image/*">
                            <div class="drop-area text-center p-5 border border-2 border-dashed rounded">
                                <div class="upload-icon mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up fa-2x text-primary"></i>
                                </div>
                                <p class="mb-1">Drop your file here or <span
                                        class="text-primary browse-link">browse</span></p>
                                <p class="text-muted small mb-0">Pick a file up to 2MB.</p>
                            </div>
                            <div class="file-info mt-2 d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-file me-2"></i>
                                    <span class="file-name me-2"></span>
                                    <span class="file-size text-muted small me-2"></span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 ms-2 remove-file">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="main_image_preview" class="mt-2"></div>
                        @error('main_image')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sub_images" class="form-label">Ảnh phụ</label>
                        <div class="custom-file-upload">
                            <input type="file" class="d-none" id="sub_images" name="sub_images[]" multiple
                                accept="image/*">
                            <div class="drop-area text-center p-5 border border-2 border-dashed rounded">
                                <div class="upload-icon mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up fa-2x text-primary"></i>
                                </div>
                                <p class="mb-1">Drop your file here or <span
                                        class="text-primary browse-link">browse</span></p>
                                <p class="text-muted small mb-0">Pick a file up to 2MB.</p>
                            </div>
                            <div class="file-info mt-2 d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-file me-2"></i>
                                    <span class="file-name me-2"></span>
                                    <span class="file-size text-muted small me-2"></span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 ms-2 remove-file">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="sub_images_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <!-- Mô tả (full width) -->
                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <div id="description-editor" style="height: 200px;"></div>
                        <input type="hidden" class="form-control" name="description" id="description">
                        @error('description')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Biến thể sản phẩm (full width) -->
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Thêm biến thể sản phẩm <span class="text-muted">(tuỳ
                                chọn)</span></label>
                        <div id="variant-container">
                            <div class="variant-item mb-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control mb-2" name="variants[0][name]"
                                            placeholder="Tên biến thể">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" min="0" class="form-control mb-2" name="variants[0][price]"
                                            placeholder="Giá">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" min="0" class="form-control mb-2" name="variants[0][quantity]"
                                            placeholder="Số lượng">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control mb-2" name="variants[0][sku]"
                                            placeholder="SKU">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" id="add-variant">Thêm biến thể</button>
                    </div>
                </div>

                <!-- Nút submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-success me-2">Tạo sản phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger">Huỷ</a>
                </div>
            </div>
        </form>
    </div>

    <!-- CSS -->
    <style>
        .custom-file-upload .drop-area {
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .custom-file-upload .drop-area:hover {
            background-color: #e9ecef;
        }

        .custom-file-upload .drop-area.dragover {
            background-color: #dee2e6;
        }

        .custom-file-upload .browse-link {
            text-decoration: underline;
        }

        .custom-file-upload .progress {
            height: 10px;
            margin-top: 5px;
        }

        .custom-file-upload .file-info {
            background-color: #f1f3f5;
            padding: 10px;
            border-radius: 5px;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.target.matches('input[type="number"][min="0"]') && event.key === '-') {
                event.preventDefault();
            }
        });

        document.addEventListener('input', function(event) {
            if (event.target.matches('input[type="number"][min="0"]') && Number(event.target.value) < 0) {
                event.target.value = 0;
            }
        });

        // Thêm biến thể
        document.getElementById('add-variant').addEventListener('click', function() {
            let container = document.getElementById('variant-container');
            let count = container.getElementsByClassName('variant-item').length;
            let html = `
                <div class="variant-item mb-2">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2" name="variants[${count}][name]" placeholder="Tên biến thể">
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="0" class="form-control mb-2" name="variants[${count}][price]" placeholder="Giá">
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="0" class="form-control mb-2" name="variants[${count}][quantity]" placeholder="Số lượng">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mb-2" name="variants[${count}][sku]" placeholder="SKU">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });

        // Xử lý kéo thả và chọn file
        document.querySelectorAll('.custom-file-upload').forEach(upload => {
            const input = upload.querySelector('input[type="file"]');
            const dropArea = upload.querySelector('.drop-area');
            const fileInfo = upload.querySelector('.file-info');
            const fileName = upload.querySelector('.file-name');
            const fileSize = upload.querySelector('.file-size');
            const progressBar = upload.querySelector('.progress-bar');
            const removeFileBtn = upload.querySelector('.remove-file');

            dropArea.addEventListener('click', () => input.click());

            dropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropArea.classList.add('dragover');
            });

            dropArea.addEventListener('dragleave', () => {
                dropArea.classList.remove('dragover');
            });

            dropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                dropArea.classList.remove('dragover');
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });

            input.addEventListener('change', (e) => {
                const files = e.target.files;
                if (files.length > 0) {
                    const file = files[0];
                    fileName.textContent = file.name;
                    fileSize.textContent = `(${(file.size / 1024).toFixed(2)} KB)`;
                    fileInfo.classList.remove('d-none');

                    let progress = 0;
                    const interval = setInterval(() => {
                        progress += 10;
                        progressBar.style.width = `${progress}%`;
                        progressBar.textContent = `${progress}%`;
                        progressBar.setAttribute('aria-valuenow', progress);
                        if (progress >= 100) clearInterval(interval);
                    }, 200);
                } else {
                    fileInfo.classList.add('d-none');
                }

                if (input.id === 'main_image') {
                    const preview = document.getElementById('main_image_preview');
                    preview.innerHTML = '';
                    const file = files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.style.maxWidth = '200px';
                            img.style.borderRadius = '5px';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                } else if (input.id === 'sub_images') {
                    const preview = document.getElementById('sub_images_preview');
                    preview.innerHTML = '';
                    if (files) {
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const img = document.createElement('img');
                                img.src = event.target.result;
                                img.style.maxWidth = '100px';
                                img.style.borderRadius = '5px';
                                preview.appendChild(img);
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                }
            });

            removeFileBtn.addEventListener('click', () => {
                input.value = '';
                fileInfo.classList.add('d-none');
                progressBar.style.width = '0%';
                progressBar.textContent = '0%';
                progressBar.setAttribute('aria-valuenow', 0);

                if (input.id === 'main_image') {
                    document.getElementById('main_image_preview').innerHTML = '';
                } else if (input.id === 'sub_images') {
                    document.getElementById('sub_images_preview').innerHTML = '';
                }
            });
        });

        // Quill Editor
        var quill = new Quill('#description-editor', {
            theme: 'snow',
            placeholder: 'Nhập mô tả sản phẩm...',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            }
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#description').value = quill.root.innerHTML;
        };
    </script>
@endsection
