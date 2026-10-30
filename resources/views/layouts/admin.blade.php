<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
@php
    $menu = [
        ['admin', 'fas fa-home', 'Dashboard', 'admin'],
        ['admin/products', 'fas fa-box-open', 'Sản phẩm', 'admin/products*'],
        ['admin/danhmuc', 'fas fa-tags', 'Danh mục', 'admin/danhmuc*'],
        ['admin/ncc', 'fas fa-truck', 'Nhà cung cấp', 'admin/ncc*'],
        ['admin/khuyenmai', 'fas fa-ticket-alt', 'Khuyến mãi', 'admin/khuyenmai*'],
        ['admin/nhapkho', 'fas fa-warehouse', 'Nhập kho', 'admin/nhapkho*'],
        ['admin/donhang', 'fas fa-shopping-cart', 'Đơn hàng', 'admin/donhang*'],
        ['admin/users', 'fas fa-users', 'Người dùng', 'admin/users*'],
    ];
    $tenDangNhap = auth()->user()->TenDangNhap;
@endphp
<div class="admin-container">
    <div class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center justify-content-center justify-content-lg-start pb-3 mb-4 text-white text-decoration-none border-bottom border-secondary px-3 pt-3">
            <span class="fs-5 fw-bold sidebar-logo-text" style="font-family: 'Roboto', sans-serif;">ADMIN PANEL</span>
            <i class="fas fa-shield-alt fs-4 sidebar-logo-icon text-primary"></i>
        </a>
        <ul class="nav nav-pills flex-column mb-auto w-100" id="menu">
            @foreach ($menu as [$href, $icon, $nhan, $mau])
                <li class="nav-item w-100">
                    <a href="{{ url($href) }}" class="nav-link {{ request()->is($mau) ? 'active' : '' }}"><i class="{{ $icon }}"></i> <span>{{ $nhan }}</span></a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar navbar-expand bg-white admin-header py-3 px-4 d-flex justify-content-between">
            <div>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fas fa-external-link-alt me-1"></i> Xem Website</a>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ rawurlencode($tenDangNhap) }}&background=2563EB&color=fff" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ $tenDangNhap }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                        <li><a class="dropdown-item" href="{{ url('admin/users') }}"><i class="fas fa-users-cog me-1"></i> Quản lý tài khoản</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="p-4 flex-grow-1">
            @include('includes.flash')
            @yield('content')
        </div>

        <footer class="bg-white border-top py-3 mt-auto">
            <div class="container-fluid text-center text-muted small">© {{ date('Y') }} - Admin Dashboard by {{ config('app.name') }}</div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.APP = { base: @json(url('/')), csrf: @json(csrf_token()) };</script>
@stack('scripts')
</body>
</html>
