@extends('layouts.customer')
@section('title', 'Quên mật khẩu - '.config('app.name'))

@section('content')
<div id="content" class="py-5">
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card-modern border-0 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-mobile-alt fs-3" style="color: var(--primary);"></i>
                        </div>
                        <h3 class="fw-bold text-main">Quên mật khẩu?</h3>
                        <p class="text-muted small">Nhập số điện thoại đã đăng ký để nhận mã OTP khôi phục tài khoản.</p>
                    </div>

                    @error('phone')<div class="alert alert-danger border-0 rounded-3">{{ $message }}</div>@enderror

                    <form action="{{ route('password.forgot') }}" method="post">
                        @csrf
                        <div class="form-floating mb-4">
                            <input type="tel" class="form-control rounded-3" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Số điện thoại" required>
                            <label for="phone" class="text-muted">Số điện thoại</label>
                        </div>
                        <div class="d-grid mb-4">
                            <button class="btn btn-primary-modern py-3" type="submit">GỬI MÃ OTP</button>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Quay lại đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
