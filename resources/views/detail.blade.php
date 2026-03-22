@extends('layouts.main')

@section('content')
    <style>
        .product-detail-page {
            padding-top: 10px;
        }

        .detail-breadcrumb {
            margin-bottom: 18px;
        }

        .detail-breadcrumb .breadcrumb {
            margin-bottom: 0;
            padding: 12px 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 999px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
        }

        .detail-shell {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 28px;
            box-shadow: 0 24px 55px rgba(15, 23, 42, 0.08);
            padding: 26px;
        }

        .gallery-panel {
            position: sticky;
            top: 100px;
        }

        .main-image-frame {
            position: relative;
            overflow: hidden;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.86), transparent 32%),
                linear-gradient(180deg, #eef2f7 0%, #e5ebf3 100%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
            cursor: zoom-in;
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            display: block;
            border-radius: 0;
        }

        .thumb-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(56px, 72px));
            gap: 10px;
            margin-top: 16px;
        }

        .thumbnail {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 18px;
            cursor: pointer;
            border: 2px solid transparent;
            background: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #111827;
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.14);
        }

        .detail-info {
            padding-left: 12px;
        }

        .product-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
        }

        .detail-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid rgba(148, 163, 184, 0.16);
            font-size: 0.82rem;
            font-weight: 700;
            color: #475569;
        }

        .detail-title {
            font-size: clamp(2rem, 3vw, 2.75rem);
            line-height: 1.05;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 14px;
        }

        .price-box {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
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
            font-size: 1.1rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-right: 4px;
        }

        .discount-price {
            font-size: clamp(1.8rem, 3vw, 2.35rem);
            color: #ea580c;
            font-weight: 900;
        }

        .stock-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .stock-pill.in-stock {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.16);
        }

        .stock-pill.out-stock {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.16);
        }

        .variant-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .variant-btn {
            min-width: 120px;
            padding: 10px 14px;
            border-radius: 14px !important;
            font-weight: 700;
        }

        .quantity-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid rgba(148, 163, 184, 0.16);
            margin-bottom: 18px;
        }

        .quantity-box .input-group {
            width: 140px !important;
        }

        .quantity-box .btn,
        .quantity-box .form-control {
            min-height: 44px;
            border-radius: 14px !important;
        }

        .detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .primary-buy-btn {
            flex: 1 1 280px;
            min-height: 54px;
            border-radius: 18px;
            background: #111827;
            border: 0;
            color: #fff;
            font-weight: 800;
            box-shadow: 0 18px 35px rgba(15, 23, 42, 0.18);
        }

        .primary-buy-btn:hover,
        .primary-buy-btn:focus {
            background: #000;
            color: #fff;
        }

        .favorite-btn {
            min-height: 54px;
            border-radius: 18px;
            padding: 0 18px;
            font-weight: 800;
        }

        .detail-panel {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
        }

        .detail-section-title {
            font-size: 1.15rem;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .detail-section-title::after {
            content: "";
            display: block;
            width: 64px;
            height: 3px;
            margin-top: 10px;
            border-radius: 999px;
            background: linear-gradient(90deg, #111827 0%, #ea580c 100%);
        }

        .rating-filter-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .rating-filter-wrap .btn {
            border-radius: 999px;
            font-weight: 700;
            padding: 10px 14px;
        }

        .review-form-card,
        .ratings-list .rating-item {
            border-radius: 22px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
        }

        .review-form-card {
            padding: 20px;
            background: #fff;
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
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 22px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
        }

        .card-product:hover {
            box-shadow: 0 22px 42px rgba(15, 23, 42, 0.14);
            transform: translateY(-6px);
        }

        .card-product img {
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }

        .card-product .card-body {
            padding: 16px;
        }

        @media (max-width: 768px) {
            .detail-shell {
                padding: 16px;
                border-radius: 22px;
            }

            .gallery-panel {
                position: static;
                margin-bottom: 18px;
            }

            .detail-info {
                padding-left: 0;
            }

            .thumb-strip {
                grid-template-columns: repeat(auto-fit, minmax(48px, 60px));
                gap: 8px;
            }

            .thumbnail {
                border-radius: 14px;
            }

            .variant-btn {
                width: 100% !important;
                margin-bottom: 0.5rem;
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

            .quantity-box {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity-box .input-group {
                width: 100% !important;
            }

            .detail-actions {
                flex-direction: column;
            }

            .primary-buy-btn,
            .favorite-btn {
                width: 100%;
            }
        }

        .image-lightbox {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(15, 23, 42, 0.82);
            backdrop-filter: blur(8px);
            z-index: 1080;
        }

        .image-lightbox.show {
            display: flex;
        }

        .image-lightbox-dialog {
            position: relative;
            max-width: min(92vw, 1100px);
            max-height: 90vh;
        }

        .image-lightbox-img {
            display: block;
            max-width: 100%;
            max-height: 90vh;
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
            background: #fff;
        }

        .image-lightbox-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.78);
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
        }
    </style>

    <div class="container product-detail-page">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb detail-breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">{{ $product->category->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="container mb-5">
        <div class="detail-shell">
        <div class="row">
            <div class="col-md-6">
                <div class="gallery-panel">
                <div class="main-image-frame" id="mainImageFrame">
                    @if ($product->mainImage)
                        <img id="mainImage" src="{{ $product->mainImage->image_url }}"
                            class="product-image img-fluid" alt="{{ $product->name }}">
                    @else
                        <img id="mainImage"
                            src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                            class="product-image img-fluid" alt="Không có ảnh">
                @endif
                </div>
                <div class="thumb-strip">
                    @forelse ($product->images as $image)
                        <img src="{{ $image->image_url }}"
                            class="thumbnail {{ $image->is_main ? 'active' : '' }}" onclick="changeImage(this)"
                            alt="{{ $product->name }}">
                    @empty
                        <p>Không có ảnh phụ nào.</p>
                    @endforelse
                </div>
                </div>
            </div>

            <div class="col-md-6 detail-info">
                <div class="product-meta-row">
                    <span class="detail-pill"><i class="bi bi-grid"></i> {{ $product->category->name }}</span>
                    <span class="detail-pill"><i class="bi bi-box-seam"></i> {{ $product->variants->count() ?: 1 }} phiên bản</span>
                </div>
                <h2 class="detail-title">{{ $product->name }}</h2>
                <div class="price-box" id="productPrice">
                    @if (isset($product->discount_price) && $product->discount_price < $product->price)
                        <span class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        <span class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                    @else
                        <span class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @endif
                </div>
                <div id="stockStatus" class="stock-pill {{ $product->quantity > 0 ? 'in-stock' : 'out-stock' }}">
                    <i class="bi {{ $product->quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                </div>

                <div class="mb-2 fw-bold text-uppercase small text-muted">Phiên bản</div>
                <div class="variant-wrap">
                @forelse ($product->variants as $variant)
                    <button class="btn btn-outline-dark btn-sm variant-btn" data-variant-id="{{ $variant->id }}"
                        data-price="{{ $variant->varriant_price }}" data-quantity="{{ $variant->varriant_quantity }}"
                        onclick="selectVariant('{{ $variant->varriant_name }}', {{ $variant->varriant_price }}, {{ $variant->varriant_quantity }}, {{ $variant->id }})">
                        {{ $variant->varriant_name }}
                    </button>
                @empty
                    <p>Không có phiên bản nào.</p>
                @endforelse
                </div>

                <div class="quantity-box">
                    <div>
                        <div class="fw-bold mb-1">Số lượng khả dụng</div>
                        <div class="text-muted">Hiện còn <strong id="stockQuantity">{{ $product->quantity }}</strong> sản phẩm</div>
                    </div>
                    <div class="input-group">
                        <button class="btn btn-outline-dark border me-2" onclick="changeQuantity(-1)">-</button>
                        <input type="text" class="form-control text-center me-2" id="quantity" value="1"
                            oninput="updateQuantity()">
                        <button class="btn btn-outline-dark border" onclick="changeQuantity(1)">+</button>
                    </div>
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

                    <div class="detail-actions">
                    @if ($product->quantity > 0)
                        <button type="submit" class="btn primary-buy-btn"><i class="fa-solid fa-cart-shopping"></i> THÊM
                            VÀO GIỎ HÀNG</button>
                    @else
                        <button type="button" class="btn primary-buy-btn" disabled><i
                                class="fa-solid fa-cart-shopping"></i> HẾT HÀNG</button>
                    @endif

                    <button type="button" id="toggleFavorite" data-product-id="{{ $product->id }}"
                        data-favorite-id="{{ $favoriteId ?? '' }}"
                        class="btn favorite-btn {{ isset($favoriteId) ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="fa-solid fa-heart"></i>
                        <span class="favorite-text">{{ isset($favoriteId) ? 'Bỏ yêu thích' : 'Yêu thích' }}</span>
                    </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-5 detail-panel">
            <h4 class="detail-section-title">Mô tả</h4>
            @if ($product->description == null)
                <p>Không có mô tả nào.</p>
            @else
                <div>{!! $product->description !!}</div>
            @endif
        </div>

        <!-- Phần đánh giá sản phẩm -->
        <div class="mt-5 detail-panel">
            <h4 class="detail-section-title">Đánh giá sản phẩm</h4>
            <div class="rating-filter-wrap" id="rating-filters">
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
                        <form action="{{ route('ratings.store', $product->id) }}" method="POST" class="mb-4 review-form-card">
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
            <h4 class="detail-section-title">Sản phẩm liên quan</h4>
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
    </div>

    <div class="image-lightbox" id="imageLightbox" aria-hidden="true">
        <div class="image-lightbox-dialog">
            <button type="button" class="image-lightbox-close" id="lightboxClose" aria-label="Đóng preview">×</button>
            <img src="" alt="Preview ảnh sản phẩm" class="image-lightbox-img" id="lightboxImage">
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
