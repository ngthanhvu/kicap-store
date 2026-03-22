@extends('layouts.main')

@section('content')
    <style>
        .products-page {
            padding-top: 8px;
        }

        .sidebar {
            padding: 24px 20px;
            position: sticky;
            top: 96px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .sidebar-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .sidebar-title h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .sidebar-title span {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .category-tree,
        .category-subtree {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-item {
            margin: 5px 0;
        }

        .category-link {
            display: block;
            padding: 10px 14px;
            text-decoration: none;
            color: #334155;
            border-radius: 14px;
            transition: background-color 0.25s, color 0.25s, transform 0.2s;
        }

        .category-link:hover {
            background-color: #eef4ff;
            color: #1d4ed8;
            transform: translateX(2px);
        }

        .category-link.active {
            background: #111827;
            color: white;
        }

        .category-name {
            font-size: 14px;
            font-weight: 600;
        }

        .form-range::-webkit-slider-runnable-track {
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(90deg, #dbeafe 0%, #cbd5e1 100%);
        }

        .form-range::-webkit-slider-thumb {
            background: #0f172a;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .form-range::-moz-range-track {
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(90deg, #dbeafe 0%, #cbd5e1 100%);
        }

        .form-range::-moz-range-thumb {
            background: #0f172a;
            border: 0;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 16px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }

        .filter-section h5 {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 14px;
            color: #0f172a;
        }

        .filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-btn {
            padding: 9px 14px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: #fff;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            border-color: #1d4ed8;
            color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.1);
        }

        .filter-btn.active {
            background: #111827;
            color: #fff;
            border-color: transparent;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
        }

        .price-range-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            gap: 10px;
        }

        .price-label {
            font-size: 0.86rem;
            font-weight: 700;
            color: #475569;
        }

        .price-pill {
            padding: 8px 12px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .price-helper {
            margin-top: 8px;
            font-size: 0.8rem;
            color: #64748b;
        }

        .product-card {
            position: relative;
            transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
            cursor: pointer;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
        }

        .product-card:hover {
            transform: translateY(-7px);
            border-color: rgba(15, 23, 42, 0.15);
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.14);
        }

        .product-card-media {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.78), transparent 36%),
                linear-gradient(180deg, #eef2f7 0%, #e5ebf3 100%);
        }

        .product-card-media img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
            border: 0 !important;
        }

        .product-card:hover .product-card-media img {
            transform: scale(1.04);
        }

        .product-chip {
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

        .product-card-body {
            padding: 16px 16px 18px;
        }

        .product-card-category {
            display: inline-block;
            margin-bottom: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #64748b;
        }

        .product-card-title {
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

        .product-price-row {
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

        .toolbar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border-radius: 24px;
            padding: 18px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
        }

        .toolbar-search {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .toolbar-search input {
            flex: 1 1 auto;
            min-width: 0;
            width: 100%;
            border: 0;
            box-shadow: none !important;
            background: transparent;
            margin-right: 0 !important;
        }

        .toolbar-search .btn {
            border-radius: 999px;
            padding: 5px 12px;
            font-weight: 700;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .toolbar-sort {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            min-height: 45px;
            box-shadow: none;
        }

        .toolbar-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 45px;
            padding: 0 16px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            color: #475569;
            font-weight: 700;
        }

        .accordion-item {
            border: 0;
            background: transparent;
        }

        .accordion-button {
            border-radius: 14px !important;
            background: #f8fafc;
            box-shadow: none !important;
            font-weight: 700;
            color: #0f172a;
            padding: 12px 14px;
        }

        .accordion-button:not(.collapsed) {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .accordion-body {
            padding-top: 12px !important;
        }

        .filter-apply-btn {
            border-radius: 14px;
            min-height: 46px;
            font-weight: 800;
            background: #111827;
            color: #fff;
            border: 0;
            box-shadow: 0 14px 26px rgba(15, 23, 42, 0.18);
        }

        .filter-apply-btn:hover,
        .filter-apply-btn:focus {
            background: #000;
            color: #fff;
        }

        .toolbar-search .btn,
        .toolbar-search .btn:hover,
        .toolbar-search .btn:focus {
            background: #111827;
            border-color: #111827;
            color: #fff;
        }

        @media (max-width: 768px) {
            .sidebar {
                border-right: none;
                border-top: 1px solid #ddd;
                height: auto;
                position: relative;
                top: 0;
                padding: 18px 16px;
            }

            .toolbar-search {
                padding: 8px;
                flex-wrap: nowrap;
                gap: 8px;
            }

            .toolbar-search .btn {
                padding: 10px 14px;
            }

            .toolbar-count {
                width: 100%;
            }

            .product-card-media img {
                height: 210px;
            }

            .product-card-title {
                min-height: 48px;
                font-size: 0.96rem;
            }
        }
    </style>

    <div class="container mt-3 mb-3 products-page">
        <div class="row">
            <div class="col-12 col-md-3 mb-3 sidebar">
                <div class="sidebar-title">
                    <h5><i class="bi bi-sliders2"></i> Bộ lọc</h5>
                </div>

                <!-- Danh mục -->
                <div class="filter-section">
                    <h5><i class="bi bi-folder"></i> Danh mục</h5>
                    <div class="accordion" id="categoryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCategories">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseCategories" aria-expanded="false"
                                    aria-controls="collapseCategories">
                                    Chọn danh mục
                                </button>
                            </h2>
                            <div id="collapseCategories" class="accordion-collapse collapse" aria-labelledby="headingCategories"
                                data-bs-parent="#categoryAccordion">
                                <div class="accordion-body p-0">
                                    @include('partials.category-accordion', [
                                        'categories' => $categories,
                                        'categoryId' => $categoryId,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khoảng giá -->
                <div class="filter-section">
                    <h5><i class="bi bi-currency-dollar"></i> Khoảng giá</h5>
                    <form id="priceFilterForm" method="GET" action="{{ route('products') }}">
                        <div class="price-range-head">
                            <div class="price-label">Mức giá tối đa</div>
                            <div class="price-pill">
                                <span id="priceValue">{{ number_format($priceMax, 0, ',', '.') }}</span> VND
                            </div>
                        </div>
                        <input type="range" class="form-range" min="0" max="10000000" step="100000"
                            id="priceRange" name="price_max" value="{{ $priceMax }}"
                            oninput="updatePrice(this.value)">
                        <div class="price-helper">Kéo thanh để giới hạn mức giá phù hợp với ngân sách của bạn.</div>
                        <input type="hidden" name="category_id" value="{{ $categoryId }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <button type="submit" class="btn filter-apply-btn w-100 mt-3">
                            <i class="bi bi-check-lg"></i> Áp dụng
                        </button>
                    </form>
                </div>

                <!-- Lọc nhanh -->
                <div class="filter-section">
                    <h5><i class="bi bi-lightning"></i> Lọc nhanh</h5>
                    <div class="filter-group">
                        <a href="{{ route('products', array_merge(request()->except('price_max'), ['price_max' => 500000])) }}" 
                           class="filter-btn {{ $priceMax <= 500000 ? 'active' : '' }}">
                            Dưới 500k
                        </a>
                        <a href="{{ route('products', array_merge(request()->except('price_max'), ['price_max' => 1000000])) }}" 
                           class="filter-btn {{ $priceMax > 500000 && $priceMax <= 1000000 ? 'active' : '' }}">
                            Dưới 1tr
                        </a>
                        <a href="{{ route('products', array_merge(request()->except('price_max'), ['price_max' => 3000000])) }}" 
                           class="filter-btn {{ $priceMax > 1000000 && $priceMax <= 3000000 ? 'active' : '' }}">
                            Dưới 3tr
                        </a>
                        <a href="{{ route('products', array_merge(request()->except('price_max'), ['price_max' => 10000000])) }}" 
                           class="filter-btn {{ $priceMax > 3000000 ? 'active' : '' }}">
                            Tất cả
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9">
                <!-- Toolbar -->
                <div class="toolbar mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-6">
                            <form id="searchForm" method="GET" action="{{ route('products') }}" class="toolbar-search">
                                <i class="bi bi-search text-muted ms-2"></i>
                                <input type="text" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..."
                                    id="searchInput" name="search" value="{{ $search }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Tìm
                                </button>
                                <input type="hidden" name="category_id" value="{{ $categoryId }}">
                                <input type="hidden" name="price_max" value="{{ $priceMax }}">
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            </form>
                        </div>
                        <div class="col-12 col-md-3">
                            <form id="sortForm" method="GET" action="{{ route('products') }}">
                                <select class="form-select toolbar-sort" id="sortSelect" name="sort" onchange="this.form.submit()">
                                    <option value="">Sắp xếp theo</option>
                                    <option value="name-az" {{ $sort == 'name-az' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="name-za" {{ $sort == 'name-za' ? 'selected' : '' }}>Tên Z-A</option>
                                    <option value="price-high-low" {{ $sort == 'price-high-low' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                    <option value="price-low-high" {{ $sort == 'price-low-high' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                </select>
                                <input type="hidden" name="category_id" value="{{ $categoryId }}">
                                <input type="hidden" name="search" value="{{ $search }}">
                                <input type="hidden" name="price_max" value="{{ $priceMax }}">
                            </form>
                        </div>
                        <div class="col-12 col-md-3 text-end">
                            <span class="toolbar-count">{{ $products->total() }} sản phẩm</span>
                        </div>
                    </div>
                </div>

                <div class="row" id="productList">
                    @forelse ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <a href="/chi-tiet/{{ $product->slug }}" class="text-decoration-none text-dark">
                                <div class="card product-card border-0">
                                    <div class="product-card-media">
                                        <span class="product-chip">New in</span>
                                        @if ($product->mainImage)
                                            <img src="{{ $product->mainImage->image_url }}"
                                                class="card-img-top" alt="{{ $product->name }}">
                                        @else
                                            <img src="https://img.freepik.com/free-vector/page-found-concept-illustration_114360-1869.jpg"
                                                class="card-img-top" alt="Keycap Artisan Natra">
                                        @endif
                                    </div>
                                    <div class="card-body text-center product-card-body">
                                        <span class="product-card-category">{{ $product->category['name'] }}</span>
                                        <h5 class="product-card-title">{{ $product->name }}</h5>
                                        <div class="product-price-row">
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
                    @empty
                        <div class="col-12 text-center">
                            <p>Không tìm thấy sản phẩm nào.</p>
                        </div>
                    @endforelse

                    <div class="mt-3">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePrice(value) {
            document.getElementById('priceValue').innerText = new Intl.NumberFormat('vi-VN').format(value);
        }
    </script>
@endsection
