@extends('layouts.admin')
@section('title', $km->exists ? 'Sửa khuyến mãi' : 'Thêm khuyến mãi')

@section('content')
<h1 class="h4 mb-3">{{ $km->exists ? 'Sửa khuyến mãi '.$km->MaKM : 'Thêm khuyến mãi' }}</h1>

<form action="{{ $km->exists ? route('admin.khuyenmai.update', $km) : route('admin.khuyenmai.store') }}" method="post" class="card border-0 shadow-sm p-4" style="max-width: 760px">
    @csrf
    @if ($km->exists) @method('PUT') @endif
    <div class="row">
        @foreach ([['MaCode', 'Mã nhập', 'text'], ['TenKM', 'Tên chương trình', 'text'], ['PhanTramGiam', 'Giảm (%)', 'number'], ['TienGiamToiDa', 'Giảm tối đa (₫)', 'number'], ['DonToiThieu', 'Đơn tối thiểu (₫)', 'number'], ['SoLuong', 'Số lượt dùng', 'number']] as [$k, $nhan, $kieu])
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ $nhan }}</label>
                <input type="{{ $kieu }}" name="{{ $k }}" value="{{ old($k, $km->{$k}) }}" class="form-control @error($k) is-invalid @enderror">
                @error($k)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endforeach
        @foreach ([['NgayBatDau', 'Bắt đầu'], ['NgayKetThuc', 'Kết thúc']] as [$k, $nhan])
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ $nhan }}</label>
                <input type="date" name="{{ $k }}" value="{{ old($k, $km->{$k}?->format('Y-m-d')) }}" class="form-control @error($k) is-invalid @enderror">
                @error($k)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endforeach
        <div class="col-md-6 mb-3">
            <label class="form-label">Loại</label>
            <select name="LoaiKM" class="form-select">
                <option value="NHAP_MA" @selected(old('LoaiKM', $km->LoaiKM) === 'NHAP_MA')>Nhập mã</option>
                <option value="SAN_CO" @selected(old('LoaiKM', $km->LoaiKM) === 'SAN_CO')>Có sẵn</option>
            </select>
        </div>
        <div class="col-md-6 mb-3 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="TrangThai" name="TrangThai" value="1" @checked(old('TrangThai', $km->TrangThai))>
                <label class="form-check-label" for="TrangThai">Đang bật</label>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
