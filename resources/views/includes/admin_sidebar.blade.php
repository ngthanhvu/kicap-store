<style>
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #1a202c;
        color: white;
        padding-top: 20px;
    }

    .sidebar a {
        color: #a0aec0;
        text-decoration: none;
        padding: 10px 20px;
        display: block;
    }

    .sidebar a:hover {
        background-color: #2d3748;
        color: white;
    }

    .content {
        margin-left: 250px;
        padding: 20px;
    }

    .navbar {
        margin-left: 250px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out;
    }

    .loading-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .menu-item {
        position: relative;
    }

    .menu-toggle {
        color: #a0aec0;
        text-decoration: none;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .menu-toggle:hover {
        background-color: #2d3748;
        color: white;
    }

    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }

    .submenu.active {
        max-height: 200px;
    }

    .submenu a {
        color: #a0aec0;
        text-decoration: none;
        padding: 8px 20px 8px 40px;
        display: block;
        font-size: 0.95rem;
    }

    .submenu a:hover {
        background-color: #4a5568;
        color: white;
    }

    .toggle-icon {
        transition: transform 0.3s ease;
    }

    .toggle-icon.active {
        transform: rotate(180deg);
    }
</style>
<div id="loading-spinner" class="loading-overlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!-- Sidebar -->
<div class="sidebar d-flex flex-column justify-content-between">
    <div>
        <div class="tw-p-4">
            <h1 class="tw-text-xl tw-font-bold tw-text-white">
                <i class="fas fa-wave-square tw-text-blue-500"></i> Admin Dashboard
            </h1>
        </div>
        <nav class="tw-mt-4">
            <a href="/admin"><i class="fas fa-home tw-mr-2"></i> Bảng điều khiển</a>

            <div class="menu-item">
                <a href="#" class="menu-toggle">
                    <i class="fas fa-layer-group tw-mr-2"></i> Sản phẩm
                    <i class="fas fa-chevron-down toggle-icon tw-ml-[75px]"></i>
                </a>
                <div class="submenu">
                    <a href="/admin/products"><i class="fas fa-list tw-mr-2"></i> Quản lý sản phẩm</a>
                    <a href="/admin/products/create"><i class="fas fa-plus tw-mr-2"></i> Thêm sản phẩm</a>
                    <a href="/admin/categories"><i class="fas fa-folder tw-mr-2"></i> Danh mục</a>
                </div>
            </div>

            <a href="/admin/orders"><i class="fas fa-money-bill tw-mr-2"></i> Đơn hàng</a>
            <a href="/admin/coupons"><i class="fas fa-ticket tw-mr-2"></i> Mã giảm giá</a>
            <a href="/admin/comments"><i class="fa-solid fa-comments tw-mr-2"></i> Bình luận & đánh giá</a>
            <a href="/admin/posts"><i class="fas fa-book tw-mr-2"></i> Bài viết</a>
            <a href="/admin/users"><i class="fas fa-users tw-mr-2"></i> Người dùng</a>
            <a href="{{ route('admin.settings.home-slider.edit') }}"><i class="fas fa-gear tw-mr-2"></i> Cài đặt</a>
        </nav>
    </div>

    <div class="tw-p-4">
        <a href="/" class="tw-text-white text-decoration-none">
            <i class="fas fa-arrow-left tw-mr-2"></i> Trở về trang chủ
        </a>
    </div>
</div>
<nav class="navbar tw-p-3">
    <div class="container-fluid">
        <div class="d-flex tw-w-50">
        </div>
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a class="d-flex align-items-center tw-text-dark text-decoration-none tw-text-gray-500" href="#">
                    <img src="https://www.gravatar.com/avatar/dfb7d7bb286d54795ab66227e90ff048.jpg?s=80&d=mp&r=g"
                        class="tw-rounded-circle tw-me-2 tw-w-8" alt="User">
                    <span>Xin chào, {{ Auth::user()->name }}</span>
                </a>
            </div>
        </div>
    </div>
</nav>
