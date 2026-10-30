@extends('layouts.customer')
@section('title', 'Đăng ký - '.config('app.name'))

@section('content')
<div id="content" class="py-4 py-md-5">
    <div class="container main-container">
        <div class="card-modern border-0 overflow-hidden">
            <div class="row g-0 flex-row-reverse">
                <div class="col-md-6 d-none d-md-block" style="background: linear-gradient(135deg, var(--accent), var(--primary));">
                    <div class="h-100 d-flex flex-column justify-content-center text-white p-5">
                        <h2 class="fw-bold mb-4">Gia nhập cùng chúng tôi!</h2>
                        <p class="fs-5 text-white-50 mb-0">Đăng ký ngay để nhận các mã khuyến mãi đặc biệt và ưu đãi độc quyền dành riêng cho bạn.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-4 p-md-5">
                        <div class="text-center mb-5">
                            <h3 class="fw-bold text-main">Tạo tài khoản mới</h3>
                            <p class="text-muted">Chỉ mất vài phút để bắt đầu</p>
                        </div>

                        <form action="{{ route('register') }}" method="post" novalidate>
                            @csrf
                            @foreach ([
                                ['username', 'text', 'Tên đăng nhập'],
                                ['password', 'password', 'Mật khẩu'],
                                ['phone', 'tel', 'Số điện thoại'],
                                ['email', 'email', 'Email (tùy chọn)'],
                            ] as [$ten, $kieu, $nhan])
                                <div class="form-floating mb-3">
                                    <input type="{{ $kieu }}" class="form-control rounded-3 @error($ten) is-invalid @enderror" id="{{ $ten }}" name="{{ $ten }}"
                                           value="{{ $kieu === 'password' ? '' : old($ten) }}" placeholder="{{ $nhan }}">
                                    <label for="{{ $ten }}" class="text-muted">{{ $nhan }}</label>
                                    @error($ten)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endforeach

                            <div class="d-grid my-4">
                                <button class="btn btn-primary-modern py-3" type="submit">ĐĂNG KÝ</button>
                            </div>

                            <div class="text-center mt-4">
                                <span class="text-muted">Đã có tài khoản? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary);">Đăng nhập</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
