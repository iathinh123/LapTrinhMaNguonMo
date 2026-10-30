@php
    $nguoiDung = auth()->user();
    $soGio = $nguoiDung?->khachHang?->gioHang()->count() ?? 0;
@endphp
<nav class="navbar navbar-expand-lg navbar-ecommerce sticky-top">
    <div class="container main-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ route('home') }}" style="color: var(--primary);">
            <i class="fas fa-headphones"></i>
            <span>PhuKien<span class="text-dark">Re</span></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="search-box-wrapper mx-lg-4 my-3 my-lg-0" action="{{ route('home') }}" method="get">
                <i class="fas fa-search search-icon"></i>
                <input class="search-input-modern" type="search" placeholder="Bạn cần tìm phụ kiện gì hôm nay?" name="keyword" value="{{ request('keyword') }}">
                <button class="search-btn" type="submit">Tìm kiếm</button>
            </form>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="header-icon-btn" href="{{ route('home') }}">
                        <i class="fas fa-home fs-5"></i>
                        <span class="d-lg-none d-xl-inline">Trang chủ</span>
                    </a>
                </li>
                <li class="nav-item position-relative">
                    <a class="header-icon-btn" href="{{ url('/cart') }}">
                        <div class="position-relative">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <span class="cart-badge-modern">{{ $soGio }}</span>
                        </div>
                        <span class="d-lg-none d-xl-inline">Giỏ hàng</span>
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <div class="dropdown">
                        <button class="header-icon-btn bg-light dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fs-5 text-primary"></i>
                            <span class="fw-bold">{{ $nguoiDung?->TenDangNhap ?? 'Tài khoản' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 mt-2 rounded-3">
                            @guest
                                <li><a class="dropdown-item py-2 fw-medium" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2 text-muted"></i>Đăng nhập</a></li>
                                <li><a class="dropdown-item py-2 fw-medium" href="{{ route('register') }}"><i class="fas fa-user-plus me-2 text-muted"></i>Đăng ký</a></li>
                            @else
                                @if ($nguoiDung->laQuanTri())
                                    <li><a class="dropdown-item py-2 fw-medium" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt me-2 text-primary"></i>Trang quản trị</a></li>
                                @endif
                                <li><a class="dropdown-item py-2 fw-medium" href="{{ url('/profile') }}"><i class="fas fa-user-cog me-2 text-primary"></i>Hồ sơ cá nhân</a></li>
                                <li><a class="dropdown-item py-2 fw-medium" href="{{ url('/orders') }}"><i class="fas fa-box me-2 text-success"></i>Đơn hàng của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="post" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 fw-medium text-danger"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</button>
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
