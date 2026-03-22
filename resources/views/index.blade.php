@extends('layouts.main')

@section('content')
    <style>
        .shop-product-card {
            position: relative;
            transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 22px;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
            margin: 0 5px;
        }

        .shop-product-card:hover {
            transform: translateY(-7px);
            border-color: rgba(15, 23, 42, 0.15);
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.14);
        }

        .shop-product-media {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.78), transparent 36%),
                linear-gradient(180deg, #eef2f7 0%, #e5ebf3 100%);
        }

        .shop-product-media img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .shop-product-card:hover .shop-product-media img {
            transform: scale(1.04);
        }

        .shop-product-chip {
            position: absolute;
            top: 14px;
            left: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
            color: #0f172a;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        .shop-product-body {
            padding: 16px 16px 18px;
        }

        .shop-product-category {
            display: inline-block;
            margin-bottom: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #64748b;
        }

        .shop-product-title {
            min-height: 56px;
            margin-bottom: 10px;
            font-size: 1.02rem;
            font-weight: 800;
            line-height: 1.35;
            color: #1e293b;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .shop-product-price {
            display: flex;
            align-items: baseline;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .original-price {
            font-size: 0.88rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-right: 2px;
        }

        .discount-price {
            font-size: 1.05rem;
            color: #ea580c;
            font-weight: 800;
        }

        /* CSS cho danh sách danh mục */
        .category-card {
            width: 100%;
            padding-top: 100%;
            position: relative;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e9ecef;
        }

        .category-card img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 1px solid #e9ecef;
        }

        .category-card p {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            transform: translateY(-50%);
            margin: 0;
            padding: 15px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            font-weight: bold;
        }

        .blog-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .hero-slider-wrap {
            width: 100%;
            max-width: 1320px;
            margin: 16px auto 0;
            padding: 0 0.75rem;
        }

        .hero-slider {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.14);
            background: #0f172a;
        }

        .hero-slider .carousel-item {
            position: relative;
        }

        .hero-slide-image {
            width: 100%;
            height: clamp(260px, 38vw, 430px);
            object-fit: cover;
            display: block;
            filter: brightness(0.72);
        }

        .hero-caption {
            position: absolute;
            inset: auto auto 0 0;
            width: min(520px, 100%);
            padding: 28px 28px 34px;
            color: #fff;
            background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.82) 55%, rgba(15, 23, 42, 0.95) 100%);
        }

        .hero-caption h2 {
            font-size: clamp(28px, 4vw, 48px);
            line-height: 1.05;
            margin-bottom: 10px;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .hero-caption p {
            margin-bottom: 16px;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.84);
        }

        .hero-caption .btn {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .hero-slider .carousel-control-prev,
        .hero-slider .carousel-control-next {
            width: 8%;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .category-card p {
                font-size: 16px;
                padding: 5px;
            }

            .blog-img {
                height: 200px;
            }

            .hero-slider-wrap {
                margin-top: 12px;
                padding: 0 0.75rem;
            }

            .hero-slide-image {
                height: 240px;
            }

            .hero-caption {
                width: 100%;
                padding: 18px 18px 22px;
            }

            .hero-caption h2 {
                font-size: 24px;
            }

            .hero-caption p {
                font-size: 13px;
                margin-bottom: 12px;
            }

            .swiper-button-prev,
            .swiper-button-next {
                display: none !important;
            }

            /* Mobile product swiper */
            .mobile-product-swiper {
                padding: 0 10px;
            }

            .mobile-product-swiper .swiper-slide {
                width: 50% !important;
                /* Show 2 cards at a time */
                /* padding: 0 5px; */
            }

            .mobile-product-swiper .card {
                margin: 0;
            }

            .shop-product-media img {
                height: 210px;
            }

            .shop-product-title {
                min-height: 48px;
                font-size: 0.96rem;
            }

            .discount-price,
            .original-price {
                font-size: 13px;
            }
        }
    </style>

    <div class="container hero-slider-wrap">
        <div id="carouselExample" class="carousel slide hero-slider" data-bs-ride="carousel">
            @if (count($sliders) > 1)
                <div class="carousel-indicators">
                    @foreach ($sliders as $index => $slide)
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @foreach ($sliders as $index => $slide)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $slide['image'] }}" class="hero-slide-image"
                            alt="{{ $slide['title'] ?: 'Slider ' . ($index + 1) }}">
                        @if (!empty($slide['title']) || !empty($slide['subtitle']) || !empty($slide['button_text']))
                            <div class="hero-caption">
                                @if (!empty($slide['title']))
                                    <h2>{{ $slide['title'] }}</h2>
                                @endif
                                @if (!empty($slide['subtitle']))
                                    <p>{{ $slide['subtitle'] }}</p>
                                @endif
                                @if (!empty($slide['button_text']))
                                    <a href="{{ $slide['button_link'] ?: '#' }}" class="btn btn-light">
                                        {{ $slide['button_text'] }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if (count($sliders) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </div>

    <div class="container mt-4">
        @if (count($products) > 0)
            <section class="new-products p-4 rounded-2 mb-4" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                <div class="container">
                    <h3 class="text-center text-uppercase mb-4"
                        style="color: #2c3e50; position: relative; padding-bottom: 10px;">
                        Sản phẩm mới
                        <span
                            style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background-color: #e74c3c;"></span>
                    </h3>
                    @if (count($products) > 4)
                        <!-- Desktop view (unchanged) -->
                        <div class="d-none d-md-block">
                            <div class="swiper product-swiper-new">
                                <div class="swiper-wrapper">
                                    @php
                                        $chunks = $products->chunk(4);
                                    @endphp
                                    @foreach ($chunks as $chunk)
                                        <div class="swiper-slide">
                                            <div class="row">
                                                @foreach ($chunk as $product)
                                                    <div class="col-md-3 col-6">
                                                        <a href="/chi-tiet/{{ $product->slug }}"
                                                            class="text-decoration-none text-dark">
                                                            <div class="shop-product-card">
                                                                <div class="shop-product-media">
                                                                    <span class="shop-product-chip">New in</span>
                                                                    @if ($product->mainImage)
                                                                        <img src="{{ $product->mainImage->image_url }}"
                                                                            alt="{{ $product->name }}">
                                                                    @else
                                                                        <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                                            alt="Keycap Artisan Natra">
                                                                    @endif
                                                                </div>
                                                                <div class="shop-product-body text-center">
                                                                    <span
                                                                        class="shop-product-category">{{ $product->category['name'] }}</span>
                                                                    <h5 class="shop-product-title">{{ $product->name }}
                                                                    </h5>
                                                                    <div class="shop-product-price">
                                                                        @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                                            <span
                                                                                class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                                            <span
                                                                                class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                                        @else
                                                                            <span
                                                                                class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-prev" style="color: #e74c3c;"></div>
                                <div class="swiper-button-next" style="color: #e74c3c;"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <!-- Mobile view -->
                        <div class="d-md-none mobile-product-swiper">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($products as $product)
                                        <div class="swiper-slide">
                                            <a href="/chi-tiet/{{ $product->slug }}" class="text-decoration-none text-dark">
                                                <div class="shop-product-card">
                                                    <div class="shop-product-media">
                                                        <span class="shop-product-chip">New in</span>
                                                        @if ($product->mainImage)
                                                            <img src="{{ $product->mainImage->image_url }}"
                                                                alt="{{ $product->name }}">
                                                        @else
                                                            <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                                alt="Keycap Artisan Natra">
                                                        @endif
                                                    </div>
                                                    <div class="shop-product-body text-center">
                                                        <span
                                                            class="shop-product-category">{{ $product->category['name'] }}</span>
                                                        <h5 class="shop-product-title">{{ $product->name }}</h5>
                                                        <div class="shop-product-price">
                                                            @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                                <span
                                                                    class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                                <span
                                                                    class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                            @else
                                                                <span
                                                                    class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-3">
                            @foreach ($products as $product)
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="/chi-tiet/{{ $product->slug }}" class="text-decoration-none text-dark">
                                        <div class="shop-product-card">
                                            <div class="shop-product-media">
                                                <span class="shop-product-chip">New in</span>
                                                @if ($product->mainImage)
                                                    <img src="{{ $product->mainImage->image_url }}"
                                                        alt="{{ $product->name }}">
                                                @else
                                                    <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                        alt="Keycap Artisan Natra">
                                                @endif
                                            </div>
                                            <div class="shop-product-body text-center">
                                                <span class="shop-product-category">{{ $product->category['name'] }}</span>
                                                <h5 class="shop-product-title">{{ $product->name }}</h5>
                                                <div class="shop-product-price">
                                                    @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                        <span
                                                            class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                        <span
                                                            class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                    @else
                                                        <span
                                                            class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @else
            <div class="text-center">
                <p>Không có sản phẩm nào!</p>
            </div>
        @endif

        {{-- hiển thị các danh mục --}}
        <section>
            @if ($list_category->count() > 0)
                <div class="row mb-3">
                    <h4 class="text-start text-uppercase mb-3"><span class="me-2" style="color: #e74c3c;">|</span>Danh
                        sách danh mục
                    </h4>
                    @foreach ($list_category as $list)
                        <div class="col-6 col-md-3 mb-3">
                            <a href="/san-pham?category_id={{ $list->id }}" class="text-decoration-none text-dark">
                                <div class="category-card">
                                    <img src="{{ $list->image_url }}" alt="{{ $list->name }}">
                                    <p class="text-center tw-text-[25px] text-uppercase">{{ $list->name }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Phần sản phẩm theo danh mục -->
        @foreach ($categories as $category)
            <section class="new-products p-3 rounded-2" style="min-height: 500px;">
                <h4 class="text-start text-uppercase mb-3"><span class="me-2"
                        style="color: #e74c3c;">|</span>{{ $category->name }}
                </h4>
                @if ($category->products->count() > 4)
                    <!-- Desktop view -->
                    <div class="d-none d-md-block">
                        <div class="swiper product-swiper-{{ $category->id }}">
                            <div class="swiper-wrapper">
                                @php
                                    $chunks = $category->products->chunk(4);
                                @endphp
                                @foreach ($chunks as $chunk)
                                    <div class="swiper-slide">
                                        <div class="row">
                                            @foreach ($chunk as $product)
                                                <div class="col-md-3 col-6 mb-3">
                                                    <a href="/chi-tiet/{{ $product->slug }}"
                                                        class="text-decoration-none text-dark">
                                                        <div class="shop-product-card">
                                                            <div class="shop-product-media">
                                                                <span class="shop-product-chip">New in</span>
                                                                @if ($product->mainImage)
                                                                    <img src="{{ $product->mainImage->image_url }}"
                                                                        alt="{{ $product->name }}">
                                                                @else
                                                                    <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                                        alt="Keycap Artisan Natra">
                                                                @endif
                                                            </div>
                                                            <div class="shop-product-body text-center">
                                                                <span class="shop-product-category">{{ $category->name }}</span>
                                                                <h5 class="shop-product-title">{{ $product->name }}
                                                                </h5>
                                                                <div class="shop-product-price">
                                                                    @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                                        <span
                                                                            class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                                        <span
                                                                            class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                                    @else
                                                                        <span
                                                                            class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-prev" style="color: #333;"></div>
                            <div class="swiper-button-next" style="color: #333;"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <!-- Mobile view -->
                    <div class="d-md-none mobile-product-swiper">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @foreach ($category->products as $product)
                                    <div class="swiper-slide">
                                        <a href="/chi-tiet/{{ $product->slug }}" class="text-decoration-none text-dark">
                                            <div class="shop-product-card">
                                                <div class="shop-product-media">
                                                    <span class="shop-product-chip">New in</span>
                                                    @if ($product->mainImage)
                                                        <img src="{{ $product->mainImage->image_url }}"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                            alt="Keycap Artisan Natra">
                                                    @endif
                                                </div>
                                                <div class="shop-product-body text-center">
                                                    <span class="shop-product-category">{{ $category->name }}</span>
                                                    <h5 class="shop-product-title">{{ $product->name }}</h5>
                                                    <div class="shop-product-price">
                                                        @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                            <span
                                                                class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                            <span
                                                                class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                        @else
                                                            <span
                                                                class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                @else
                    <div class="row mt-3">
                        @foreach ($category->products as $product)
                            <div class="col-md-3 col-6 mb-3">
                                <a href="/chi-tiet/{{ $product->slug }}" class="text-decoration-none text-dark">
                                    <div class="shop-product-card">
                                        <div class="shop-product-media">
                                            <span class="shop-product-chip">New in</span>
                                            @if ($product->mainImage)
                                                <img src="{{ $product->mainImage->image_url }}"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                    alt="Keycap Artisan Natra">
                                            @endif
                                        </div>
                                        <div class="shop-product-body text-center">
                                            <span class="shop-product-category">{{ $category->name }}</span>
                                            <h5 class="shop-product-title">{{ $product->name }}</h5>
                                            <div class="shop-product-price">
                                                @if (isset($product->discount_price) && $product->discount_price < $product->price)
                                                    <span
                                                        class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                    <span
                                                        class="discount-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                                @else
                                                    <span
                                                        class="discount-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="text-center">
                    <a href="/san-pham?category_id={{ $category->id }}" class="btn btn-dark mx-auto mt-3 mb-3">Xem
                        thêm</a>
                    </di>
            </section>
        @endforeach
        <section>
            <div class="container">
                <div class="row">
                    @if ($posts->count() > 0)
                        <h3 class="text-center text-uppercase mb-4"
                            style="color: #2c3e50; position: relative; padding-bottom: 10px;">
                            Tin tức mới
                            <span
                                style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background-color: #e74c3c;"></span>
                        </h3>
                    @endif
                    @foreach ($posts as $post)
                        <div class="col-12 col-md-4 mb-4">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                <div class="tw-card border-0">
                                    <img src="{{ $post->image_url }}" class="card-img-top blog-img" alt="Blog Image"
                                        width="400" height="300" style="object-fit: cover;">
                                    <div class="mt-2">
                                        <h5>{{ $post->title }}</h5>
                                        <p>{{ $post->description ?? '' }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    @if ($posts->count() < 0)
                        <div class="col-md-12 text-center">
                            <p>Không có bài viết nào.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    @if (count($products) > 4 || $categories->pluck('products')->flatten()->count() > 4)
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (count($products) > 4)
                    // Desktop swiper
                    var swiperNew = new Swiper('.product-swiper-new', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        pagination: {
                            clickable: true,
                        },
                    });

                    // Mobile swiper
                    var swiperNewMobile = new Swiper('.new-products .mobile-product-swiper .swiper', {
                        slidesPerView: 'auto',
                        spaceBetween: 10,
                        centeredSlides: false,
                        pagination: {
                            el: '.new-products .mobile-product-swiper .swiper-pagination',
                            clickable: true,
                        },
                    });
                @endif

                @foreach ($categories as $category)
                    @if ($category->products->count() > 4)
                        // Desktop swiper
                        var swiper{{ $category->id }} = new Swiper('.product-swiper-{{ $category->id }}', {
                            slidesPerView: 1,
                            spaceBetween: 20,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            pagination: {
                                clickable: true,
                            },
                        });

                        // Mobile swiper
                        var swiper{{ $category->id }}Mobile = new Swiper(
                            '.new-products:has(h4:contains("{{ $category->name }}")) .mobile-product-swiper .swiper', {
                                slidesPerView: 'auto',
                                spaceBetween: 10,
                                centeredSlides: false,
                                pagination: {
                                    el: '.new-products:has(h4:contains("{{ $category->name }}")) .mobile-product-swiper .swiper-pagination',
                                    clickable: true,
                                },
                            });
                    @endif
                @endforeach
            });
        </script>
    @endif
@endsection
