@extends('layouts.customer')
@section('title', 'Đặt lại mật khẩu - '.config('app.name'))

@section('content')
<div id="content" class="py-5">
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card-modern border-0 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-lock fs-3" style="color: var(--primary);"></i>
                        </div>
                        <h3 class="fw-bold text-main">Đặt lại mật khẩu</h3>
                        <p class="text-muted small">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
                    </div>

                    @if ($errors->any())<div class="alert alert-danger border-0 rounded-3">{{ $errors->first() }}</div>@endif

                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Mật khẩu mới" required minlength="6">
                            <label for="password" class="text-muted">Mật khẩu mới</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required>
                            <label for="password_confirmation" class="text-muted">Xác nhận mật khẩu mới</label>
                        </div>
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary-modern py-3" type="submit">CẬP NHẬT MẬT KHẨU</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
