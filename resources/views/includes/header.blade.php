<div id="loading-spinner" class="loading-overlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@php
    $mainNavItems = [
        ['label' => 'Trang chủ', 'url' => '/', 'active' => request()->is('/')],
        ['label' => 'Sản phẩm', 'url' => '/san-pham', 'active' => request()->is('san-pham')],
        ['label' => 'Tin tức', 'url' => '/tin-tuc', 'active' => request()->is('tin-tuc') || request()->is('tin-tuc/*')],
        ['label' => 'Yêu thích', 'url' => '/profile/favorite', 'active' => request()->is('profile/favorite')],
    ];

    if (Auth::check() && Auth::user()->role === 'admin') {
        $mainNavItems[] = ['label' => 'Admin', 'url' => '/admin', 'active' => request()->is('admin') || request()->is('admin/*')];
    }

    $avatarUrl = Auth::check() && Auth::user()->avatar
        ? asset(Auth::user()->avatar)
        : 'https://muaclone247.com/assets/storage/images/avatar4N0.png';
@endphp

<header class="site-header">
    <div class="header-shell">
        <div class="header-top">
            <button class="mobile-menu-btn d-md-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileNavbar" aria-controls="mobileNavbar">
                <i class="bi bi-list"></i>
            </button>

            <a href="/" class="header-brand text-decoration-none">
                <span class="brand-mark">K</span>
                <div class="brand-copy">
                    <span class="brand-name">KICAP</span>
                    <span class="brand-tag">Mechanical Keyboard Gear</span>
                </div>
            </a>

            <form action="{{ route('products') }}" method="GET" class="header-search d-none d-lg-flex">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tim keycap, switch, kit..."
                    aria-label="Tim san pham">
                <button type="submit">Tìm</button>
            </form>

            <div class="header-actions">
                <a href="/gio-hang" class="header-action-card text-decoration-none text-dark">
                    <div class="header-action-icon">
                        <i class="bi bi-bag"></i>
                        <span class="header-badge">{{ session('count_cart', 0) }}</span>
                    </div>
                    <div class="header-action-copy d-none d-md-block">
                        <span class="header-action-label">Giỏ hàng</span>
                        <strong>{{ session('count_cart', 0) }} sản phẩm</strong>
                    </div>
                </a>

                @if (Auth::check())
                    <div class="dropdown">
                        <button class="account-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $avatarUrl }}" alt="{{ Auth::user()->name }}">
                            <span class="d-none d-md-flex flex-column text-start">
                                <small>Tài khoản</small>
                                <strong>{{ Auth::user()->name }}</strong>
                            </span>
                            <i class="bi bi-chevron-down d-none d-md-inline"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end account-menu">
                            <li class="account-summary">
                                <img src="{{ $avatarUrl }}" alt="{{ Auth::user()->name }}">
                                <div>
                                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                    <small>{{ Auth::user()->email }}</small>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="/profile">Trang cá nhân</a></li>
                            <li><a class="dropdown-item" href="/profile/favorite">Sản phẩm yêu thích</a></li>
                            @if (Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="/admin">Trang admin</a></li>
                            @endif
                            <li>
                                <form action="/dang-xuat" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="/dang-nhap" class="login-cta text-decoration-none">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-md-inline">Đăng nhập</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="header-search-mobile d-lg-none">
            <form action="{{ route('products') }}" method="GET" class="header-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tim san pham..."
                    aria-label="Tim san pham">
                <button type="submit">Tim</button>
            </form>
        </div>

        <nav class="header-nav d-none d-md-flex">
            @foreach ($mainNavItems as $item)
                <a href="{{ $item['url'] }}"
                    class="header-nav-link {{ $item['active'] ? 'is-active border' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </div>

    <div class="offcanvas offcanvas-start mobile-drawer" tabindex="-1" id="mobileNavbar"
        aria-labelledby="mobileNavbarLabel">
        <div class="offcanvas-header">
            <div>
                <h5 class="offcanvas-title" id="mobileNavbarLabel">Kicap Store</h5>
                <small class="text-muted">Menu điều hướng</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mobile-account-card">
                @if (Auth::check())
                    <img src="{{ $avatarUrl }}" alt="{{ Auth::user()->name }}">
                    <div>
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ Auth::user()->email }}</small>
                    </div>
                @else
                    <i class="bi bi-person-circle"></i>
                    <div>
                        <strong>Chua dang nhap</strong>
                        <small><a href="/dang-nhap" class="text-decoration-none">Đăng nhập ngay</a></small>
                    </div>
                @endif
            </div>

            <div class="mobile-nav-list">
                @foreach ($mainNavItems as $item)
                    <a href="{{ $item['url'] }}"
                        class="mobile-nav-link {{ $item['active'] ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
            </div>

            @if (Auth::check())
                <div class="mobile-nav-section">
                    <a href="/profile" class="mobile-nav-link">Trang cá nhân</a>
                    <a href="/profile/history" class="mobile-nav-link">Đơn hàng của tôi</a>
                    <form action="/dang-xuat" method="POST">
                        @csrf
                        <button type="submit" class="mobile-nav-link mobile-logout-btn">Đăng Xuất</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</header>

@if (session('success'))
    <script>
        iziToast.success({
            title: 'Thành công',
            message: '{{ session('success') }}',
            position: 'topRight'
        });
    </script>
@endif

<style>
    .site-header {
        position: sticky;
        top: 0;
        z-index: 1030;
        background:
            radial-gradient(circle at top left, rgba(148, 163, 184, 0.15), transparent 28%),
            linear-gradient(180deg, #fbfdff 0%, #f4f7fb 100%);
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        backdrop-filter: blur(14px);
    }

    .header-shell {
        width: min(1320px, calc(100% - 24px));
        margin: 0 auto;
        padding: 14px 0 12px;
    }

    .header-top {
        display: grid;
        grid-template-columns: auto 1fr minmax(320px, 480px) auto;
        align-items: center;
        gap: 18px;
    }

    .header-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: #0f172a;
    }

    .brand-mark {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 800;
        color: #fff;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.24);
    }

    .brand-copy {
        display: flex;
        flex-direction: column;
        line-height: 1.05;
    }

    .brand-name {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: 0.18em;
    }

    .brand-tag {
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #64748b;
    }

    .header-search {
        height: 52px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 10px 0 16px;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .header-search i {
        color: #64748b;
        font-size: 1rem;
    }

    .header-search input {
        flex: 1;
        border: 0;
        outline: 0;
        background: transparent;
        color: #0f172a;
        font-size: 0.97rem;
    }

    .header-search button {
        border: 0;
        border-radius: 999px;
        padding: 10px 16px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #111827 0%, #334155 100%);
    }

    .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .header-action-card {
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.22);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }

    .header-action-icon {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 1.1rem;
    }

    .header-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0f172a;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .header-action-copy {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .header-action-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #64748b;
    }

    .account-trigger {
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px 8px 8px;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }

    .account-trigger img {
        width: 38px;
        height: 38px;
        border-radius: 14px;
        object-fit: cover;
    }

    .account-trigger small {
        color: #64748b;
        line-height: 1;
    }

    .account-trigger strong {
        color: #0f172a;
        line-height: 1.1;
    }

    .account-menu {
        width: 260px;
        padding: 10px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 18px;
        box-shadow: 0 22px 55px rgba(15, 23, 42, 0.16);
    }

    .account-summary {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 8px 10px 12px;
        margin-bottom: 8px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    }

    .account-summary img {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        object-fit: cover;
    }

    .account-menu .dropdown-item {
        border-radius: 12px;
        padding: 10px 12px;
    }

    .login-cta {
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 16px;
        border-radius: 999px;
        color: #0f172a;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.22);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        font-weight: 700;
    }

    .header-search-mobile {
        margin-top: 12px;
    }

    .header-nav {
        margin-top: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(148, 163, 184, 0.16);
        width: fit-content;
    }

    .header-nav-link {
        padding: 10px 16px;
        border-radius: 999px;
        text-decoration: none;
        color: #475569;
        font-weight: 700;
        transition: 0.2s ease;
    }

    .header-nav-link:hover,
    .header-nav-link.is-active {
        color: #0f172a;
        background: #ffffff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    }

    .mobile-menu-btn {
        width: 44px;
        height: 44px;
        border: 0;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        font-size: 1.35rem;
        color: #0f172a;
    }

    .mobile-drawer {
        border-top-right-radius: 24px;
        border-bottom-right-radius: 24px;
    }

    .mobile-account-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        border-radius: 18px;
        background: #f8fafc;
        margin-bottom: 18px;
    }

    .mobile-account-card img,
    .mobile-account-card i {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        object-fit: cover;
        font-size: 2rem;
        color: #334155;
    }

    .mobile-account-card strong,
    .mobile-account-card small {
        display: block;
    }

    .mobile-nav-list,
    .mobile-nav-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .mobile-nav-section {
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid rgba(148, 163, 184, 0.16);
    }

    .mobile-nav-link {
        padding: 12px 14px;
        border-radius: 14px;
        text-decoration: none;
        color: #0f172a;
        background: #f8fafc;
        font-weight: 700;
        border: 0;
        text-align: left;
    }

    .mobile-nav-link.is-active {
        background: #e2e8f0;
    }

    .mobile-logout-btn {
        color: #dc2626;
    }

    @media (max-width: 1199.98px) {
        .header-top {
            grid-template-columns: auto 1fr auto;
        }
    }

    @media (max-width: 767.98px) {
        .site-header {
            position: static;
        }

        .header-shell {
            width: calc(100% - 20px);
            padding: 10px 0;
        }

        .header-top {
            grid-template-columns: auto 1fr auto;
            gap: 10px;
        }

        .header-brand {
            justify-content: center;
            min-width: 0;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            font-size: 1rem;
        }

        .brand-name {
            font-size: 1.25rem;
            letter-spacing: 0.12em;
        }

        .brand-tag {
            display: none;
        }

        .header-actions {
            gap: 8px;
        }

        .header-action-card,
        .login-cta,
        .account-trigger {
            min-height: 44px;
            padding: 6px 8px;
            border-radius: 14px;
        }

        .header-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
        }

        .account-trigger img {
            width: 32px;
            height: 32px;
            border-radius: 10px;
        }
    }
</style>
