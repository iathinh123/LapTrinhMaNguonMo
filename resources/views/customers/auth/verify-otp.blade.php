@extends('layouts.customer')
@section('title', 'Xác nhận OTP - '.config('app.name'))

@section('content')
<div id="content" class="py-5">
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card-modern border-0 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-shield-alt fs-3" style="color: var(--primary);"></i>
                        </div>
                        <h3 class="fw-bold text-main">Xác nhận OTP</h3>
                        <p class="text-muted small">Vui lòng nhập mã OTP gồm 6 chữ số đã được gửi đến số điện thoại của bạn.</p>
                    </div>

                    @if (session('info'))<div class="alert alert-info border-0 rounded-3">{{ session('info') }}</div>@endif
                    @error('otp')<div class="alert alert-danger border-0 rounded-3">{{ $message }}</div>@enderror
                    @error('token')<div class="alert alert-danger border-0 rounded-3">{{ $message }}</div>@enderror

                    <form action="{{ route('otp.verify') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg text-center rounded-3 fw-bold fs-4" id="otp" name="otp"
                                   placeholder="_ _ _ _ _ _" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required autofocus>
                        </div>
                        <div class="d-grid mb-4">
                            <button class="btn btn-primary-modern py-3" type="submit">XÁC NHẬN</button>
                        </div>
                        <div class="text-center">
                            <span class="text-muted small">Chưa nhận được mã? <a href="{{ route('password.forgot') }}" class="fw-bold text-decoration-none" style="color: var(--primary);">Gửi lại</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
