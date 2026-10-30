@extends('layouts.customer')
@section('title', 'Hồ sơ cá nhân - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <div class="row g-4">
        <div class="col-lg-7">
            <form action="{{ route('profile.update') }}" method="post" class="card-modern p-4">
                @csrf
                <h5 class="fw-bold mb-3">Thông tin cá nhân</h5>
                @foreach ([['hoTen', 'Họ tên', $kh->HoTen], ['dienThoai', 'Số điện thoại', $kh->DienThoai], ['email', 'Email', $kh->Email], ['diaChi', 'Địa chỉ', $kh->DiaChi]] as [$ten, $nhan, $giaTri])
                    <div class="mb-3">
                        <label class="form-label">{{ $nhan }}</label>
                        <input name="{{ $ten }}" value="{{ old($ten, $giaTri) }}" class="form-control @error($ten) is-invalid @enderror">
                        @error($ten)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach
                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gioiTinh" class="form-select">
                        <option value="">—</option>
                        @foreach (['Nam', 'Nữ', 'Khác'] as $g)<option @selected(old('gioiTinh', $kh->GioiTinh) === $g)>{{ $g }}</option>@endforeach
                    </select>
                </div>
                <button class="btn btn-primary-modern px-4">Lưu thay đổi</button>
            </form>
        </div>

        <div class="col-lg-5">
            <form action="{{ route('profile.password') }}" method="post" class="card-modern p-4">
                @csrf
                <h5 class="fw-bold mb-3">Đổi mật khẩu</h5>
                @if ($tk->LoaiTaiKhoan === 'GOOGLE')
                    <p class="text-muted">Tài khoản đăng nhập bằng Google không thể đổi mật khẩu.</p>
                @else
                    @foreach ([['oldPassword', 'Mật khẩu hiện tại'], ['newPassword', 'Mật khẩu mới'], ['newPassword_confirmation', 'Xác nhận mật khẩu mới']] as [$ten, $nhan])
                        <div class="mb-3">
                            <label class="form-label">{{ $nhan }}</label>
                            <input type="password" name="{{ $ten }}" class="form-control @error($ten) is-invalid @enderror">
                            @error($ten)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <button class="btn btn-outline-primary">Đổi mật khẩu</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
