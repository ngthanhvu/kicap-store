@extends('layouts.main')

@section('content')
    <style>
        .product-image {
            width: 100%;
            border-radius: 5px;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: black;
        }

        .color-option {
            width: 40px;
            height: 40px;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .color-option.active {
            border: 2px solid black;
        }

        .star-rating .fa-star {
            color: #ddd;
            cursor: pointer;
        }

        .star-rating .fa-star.checked {
            color: #f5c518;
        }

        .checked {
            color: #f5c518;
        }

        .rating-item {
            position: relative;
        }

        .like-btn {
            position: absolute;
            right: 40px;
        }

        .more-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .original-price {
            font-size: 24px;
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .discount-price {
            font-size: 26px;
            color: #e74c3c;
            font-weight: bold;
        }

        .original-price-related {
            font-size: 14px;
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .discount-price-related {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
        }

        .card-product {
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        .card-product:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            .thumbnail {
                width: 45px;
                height: 45px;
            }

            .variant-btn {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            .btn.w-50 {
                width: 100% !important;
            }

            .like-btn {
                width: 100%;
                position: static;
                margin-top: 10px;
            }

            .related-products .col-md-3 {
                width: 50%;
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    </style>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">{{ $product->category->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-md-6">
                @if ($product->mainImage)
                    <img id="mainImage" src="{{ $product->mainImage->image_url }}"
                        class="product-image img-fluid" alt="{{ $product->name }}">
                @else
                    <img id="mainImage"
                        src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                        class="product-image img-fluid" alt="Không có ảnh">
                @endif
                <div class="mt-3 d-flex">
                    @forelse ($product->images as $image)
                        <img src="{{ $image->image_url }}"
                            class="thumbnail mx-1 {{ $image->is_main ? 'active' : '' }}" onclick="changeImage(this)"
                            alt="{{ $product->name }}">
                    @empty
                        <p>Không có ảnh phụ nào.</p>
                    @endforelse
                </div>
            </div>

            <div class="col-md-6">
                <h2>{{ $product->name }}</h2>
                <p class="text-muted">Danh mục: {{ $product->category->name }}</p>
                <h3 class="text-danger" id="productPrice">
                    @if (isset($product->discount_price) && $product->discount_price < $product->price)
                        <span class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        <span class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                    @else
                        <span class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @endif
                </h3>
                <p><strong>Tình trạng:</strong>
                    <span id="stockStatus" class="{{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </span>
                </p>

                <p><strong>Phiên bản:</strong></p>
                @forelse ($product->variants as $variant)
                    <button class="btn btn-outline-dark btn-sm variant-btn" data-variant-id="{{ $variant->id }}"
                        data-price="{{ $variant->varriant_price }}" data-quantity="{{ $variant->varriant_quantity }}"
                        onclick="selectVariant('{{ $variant->varriant_name }}', {{ $variant->varriant_price }}, {{ $variant->varriant_quantity }}, {{ $variant->id }})">
                        {{ $variant->varriant_name }}
                    </button>
                @empty
                    <p>Không có phiên bản nào.</p>
                @endforelse

                <p class="mt-3"><strong>Số lượng:</strong> <span id="stockQuantity">{{ $product->quantity }}</span></p>
                <div class="input-group" style="width: 120px;">
                    <button class="btn btn-outline-dark" onclick="changeQuantity(-1)">-</button>
                    <input type="text" class="form-control text-center" id="quantity" value="1"
                        oninput="updateQuantity()">
                    <button class="btn btn-outline-dark" onclick="changeQuantity(1)">+</button>
                </div>

                <form id="addToCartForm" action="{{ route('carts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="quantityInput" value="1">
                    <input type="hidden" name="user_id" value="{{ optional(auth()->user())->id }}">
                    <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                    {{-- <input type="hidden" name="price" id="priceInput" value="{{ $product->price }}"> --}}
                    <input type="hidden" name="price" id="priceInput"
                        value="{{ $product->discount_price && $product->discount_price < $product->price ? $product->discount_price : $product->price }}">
                    <input type="hidden" name="variant_id" id="variantIdInput" value="">

                    @if ($product->quantity > 0)
                        <button type="submit" class="btn btn-dark w-50 mt-3"><i class="fa-solid fa-cart-shopping"></i> THÊM
                            VÀO GIỎ HÀNG</button>
                    @else
                        <button type="button" class="btn btn-dark w-50 mt-3" disabled><i
                                class="fa-solid fa-cart-shopping"></i> HẾT HÀNG</button>
                    @endif

                    <button type="button" id="toggleFavorite" data-product-id="{{ $product->id }}"
                        data-favorite-id="{{ $favoriteId ?? '' }}"
                        class="btn {{ isset($favoriteId) ? 'btn-danger' : 'btn-outline-danger' }} mt-3">
                        <i class="fa-solid fa-heart"></i>
                        <span class="favorite-text">{{ isset($favoriteId) ? 'Bỏ yêu thích' : 'Yêu thích' }}</span>
                    </button>


                </form>
            </div>
        </div>

        <div class="mt-5" style="border-top: 1px solid #ccc; padding-top: 20px;">
            <h4>MÔ TẢ</h4>
            @if ($product->description == null)
                <p>Không có mô tả nào.</p>
            @else
                <div>{!! $product->description !!}</div>
            @endif
        </div>

        <!-- Phần đánh giá sản phẩm -->
        <div class="mt-5" style="border-top: 1px solid #ccc; padding-top: 20px;">
            <h4>ĐÁNH GIÁ SẢN PHẨM</h4>
            <div class="mb-3" id="rating-filters">
                <button class="btn btn-outline-danger me-2 active" data-star="all">Tất Cả
                    ({{ $ratings->count() }})</button>
                @for ($i = 5; $i >= 1; $i--)
                    <button class="btn btn-outline-secondary me-2" data-star="{{ $i }}">{{ $i }}
                        Sao
                        ({{ $ratings->where('rating', $i)->count() }})</button>
                @endfor
            </div>

            <div class="row">
                <!-- Cột bên trái: Form đánh giá -->
                <div class="col-md-4">
                    @auth
                        <form action="{{ route('ratings.store', $product->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><strong>Đánh giá của bạn:</strong></label>
                                <div class="star-rating">
                                    <i class="fa fa-star" data-rating="1" onclick="setRating(1)"></i>
                                    <i class="fa fa-star" data-rating="2" onclick="setRating(2)"></i>
                                    <i class="fa fa-star" data-rating="3" onclick="setRating(3)"></i>
                                    <i class="fa fa-star" data-rating="4" onclick="setRating(4)"></i>
                                    <i class="fa fa-star" data-rating="5" onclick="setRating(5)"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="0">
                                @error('rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Nhận xét:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Nhập nhận xét của bạn..."></textarea>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    @else
                        <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá.</p>
                    @endauth
                </div>

                <!-- Cột bên phải: Danh sách đánh giá -->
                <div class="col-md-8">
                    <div class="ratings-list" id="ratings-list">
                        @forelse ($ratings as $rating)
                            <div class="card mb-4 shadow-sm rating-item" data-star="{{ $rating->rating }}"
                                id="rating-{{ $rating->id }}">
                                <div class="card-body position-relative">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-0">
                                                <strong>{{ $rating->user->name }}</strong>
                                                <span class="text-warning ms-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fa fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </span>
                                            </h6>
                                            <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                        </div>

                                        @auth
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-primary me-2 like-btn"
                                                    data-rating-id="{{ $rating->id }}"
                                                    onclick="toggleLike(this, {{ $rating->id }})">
                                                    <i class="fa fa-thumbs-up me-1"></i>
                                                    <span class="like-text">Thích</span>
                                                    (<span class="like-count">{{ $rating->likes->count() }}</span>)
                                                </button>

                                                @if (auth()->id() === $rating->user_id)
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                                            id="dropdownMenuButton{{ $rating->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton{{ $rating->id }}">
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="deleteRating({{ $rating->id }})"
                                                                    style="padding: 0;">
                                                                    <i class="fas fa-trash-alt me-2"></i> Xóa
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fa fa-thumbs-up me-1"></i> {{ $rating->likes->count() }} lượt thích
                                            </span>
                                        @endauth
                                    </div>

                                    <p class="mb-2">{{ $rating->comment ?? 'Không có nhận xét.' }}</p>

                                    @if ($rating->admin_reply)
                                        <div class="bg-light border-start border-4 border-primary p-3 rounded mt-3">
                                            <p class="mb-1">
                                                <strong class="text-primary">
                                                    <i class="fas fa-user-shield me-1"></i>Admin phản hồi:
                                                </strong>
                                            </p>
                                            <p class="mb-0">{{ $rating->admin_reply }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này.</div>
                        @endforelse


                        <!-- Phân trang -->
                        <div class="mt-3">
                            {{ $ratings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sản phẩm liên quan --}}
        <div class="mt-3">
            <h4 class="text-start text-uppercase mb-3"><span class="me-2" style="color: #e74c3c;">|</span>Sản phẩm liên
                quan
            </h4>
            <div class="row">
                @foreach ($related_products as $product)
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="card card-product h-100">
                                {{-- <img src="{{ asset('storage/' . $product->mainImage->sub_image) }}" alt="{{ $product->name }}" class="card-img-top"> --}}
                                @if ($product->mainImage)
                                    <img src="{{ $product->mainImage->image_url }}"
                                        alt="{{ $product->name }}" class="card-img-top">
                                @else
                                    <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                        alt="Không có ảnh" class="card-img-top">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                    <p class="card-text text-center">
                                        @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                            <span
                                                class="original-price-related">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                            <span
                                                class="discount-price-related">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                        @else
                                            <span
                                                class="discount-price-related">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @if ($related_products->isEmpty())
                    <div class="col-md-12 text-center">
                        <i class="bi bi-inbox tw-text-[40px]"></i><br>
                        <p>Không có sản phẩm liên quan nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        window.maxQuantity = {{ $product->quantity }};
        window.selectedPrice = {{ $product->price }};
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/detail.js') }}"></script>
    <script>
        const btn = document.getElementById('toggleFavorite');
        const textEl = btn.querySelector('.favorite-text');
        const productId = btn.dataset.productId;

        let currentFavoriteId = btn.dataset.favoriteId || '';

        btn.addEventListener('click', async function() {
            if (currentFavoriteId) {
                const url = `/favorite/${currentFavoriteId}`;

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        console.log('Đã bỏ yêu thích');

                        currentFavoriteId = '';
                        btn.dataset.favoriteId = '';
                        btn.setAttribute('data-favorite-id', '');

                        textEl.textContent = 'Yêu thích';
                    } else {
                        alert('Lỗi khi bỏ yêu thích');
                    }
                } catch (error) {
                    console.error('Lỗi khi gửi DELETE:', error);
                    alert('Lỗi khi bỏ yêu thích');
                }
            } else {
                const url = "{{ route('favorite.store') }}";

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.favorite?.id) {
                        console.log('Đã thêm vào yêu thích');

                        currentFavoriteId = data.favorite.id;
                        btn.dataset.favoriteId = currentFavoriteId;
                        btn.setAttribute('data-favorite-id', currentFavoriteId);

                        textEl.textContent = 'Bỏ yêu thích';
                    } else if (response.status === 409) {
                        alert(data.message);
                    } else {
                        alert('Lỗi khi thêm vào yêu thích');
                    }
                } catch (error) {
                    console.error('Lỗi khi thêm yêu thích:', error);
                    alert('Lỗi kết nối');
                }
            }
        });
    </script>
@endsection
