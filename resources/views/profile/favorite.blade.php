@extends('layouts.main')

@section('content')
    <style>
        .product-card {
            transition: transform 0.2s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .original-price {
            font-size: 14px;
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .discount-price {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                @include('profile.includes.sidebar')
            </div>

            <div class="col-md-9 col-lg-10 profile-content">
                @if ($favorites->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-heart tw-text-[40px]"></i><br>
                        Chưa có sản phẩm yêu thích
                    </div>
                @else
                    <div class="row">
                        @foreach ($favorites as $favorite)
                            <div class="col-md-3 mb-3">
                                <a href="/chi-tiet/{{ $favorite->product->slug }}" class="text-decoration-none text-dark">
                                    <div class="card product-card border-0">
                                        @if ($favorite->product->mainImage)
                                            <img src="{{ $favorite->product->mainImage->image_url }}"
                                                class="card-img-top" alt="{{ $favorite->product->name }}"
                                                style="width: 225px; height: 225px; object-fit: cover; border: 1px solid #ccc">
                                        @else
                                            <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                class="card-img-top" alt="Default Image">
                                        @endif
                                        <div class="card-body text-center">
                                            <h5 class="card-subtitle mb-2">{{ $favorite->product->name }}</h5>
                                            <p class="card-text">
                                                @if (isset($favorite->product->discount_price) && $favorite->product->discount_price < $favorite->product->price)
                                                    <span
                                                        class="original-price">{{ number_format($favorite->product->price, 0, ',', '.') }}₫</span>
                                                    <span
                                                        class="discount-price">{{ number_format($favorite->product->discount_price, 0, ',', '.') }}₫</span>
                                                @else
                                                    <span
                                                        class="discount-price">{{ number_format($favorite->product->price, 0, ',', '.') }}₫</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
