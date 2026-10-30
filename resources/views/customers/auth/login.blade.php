@extends('layouts.customer')
@section('title', 'Đăng nhập - '.config('app.name'))

@section('content')
<div id="content" class="py-4 py-md-5">
    <div class="container main-container">
        <div class="card-modern border-0 overflow-hidden shadow-lg" style="max-width: 1000px; margin: 0 auto;">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-block" style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                    <div class="h-100 d-flex flex-column justify-content-center text-white p-4 p-lg-5">
                        <h3 class="fw-bold mb-3">Chào mừng trở lại!</h3>
                        <p class="fs-6 text-white-50 mb-0">Đăng nhập để khám phá hàng ngàn phụ kiện công nghệ chất lượng với giá tốt nhất thị trường.</p>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-main">Đăng nhập tài khoản</h4>
                            <p class="text-muted small">Vui lòng nhập thông tin của bạn bên dưới</p>
                        </div>

                        @error('username')
                            <div class="alert alert-danger border-0 rounded-3 py-2 small"><i class="fas fa-exclamation-circle me-2"></i> {{ $message }}</div>
                        @enderror
                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-3 py-2 small"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
                        @endif

                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="username" name="username" value="{{ old('username') }}" placeholder="Tên đăng nhập" required autofocus>
                                <label for="username" class="text-muted">Tên đăng nhập</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Mật khẩu" required>
                                <label for="password" class="text-muted">Mật khẩu</label>
                            </div>

                            <div class="d-flex justify-content-end mb-4 small">
                                <a href="{{ route('password.forgot') }}" class="text-decoration-none fw-medium" style="color: var(--primary);">Quên mật khẩu?</a>
                            </div>

                            <div class="d-grid mb-4">
                                <button class="btn btn-primary-modern py-2 fs-6" type="submit">ĐĂNG NHẬP</button>
                            </div>

                            <div class="text-center mt-2">
                                <span class="text-muted small">Chưa có tài khoản? <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--primary);">Đăng ký ngay</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
