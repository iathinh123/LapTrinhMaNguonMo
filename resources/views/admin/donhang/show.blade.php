@extends('layouts.admin')
@section('title', 'Đơn hàng #'.$hd->MaHD)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Đơn hàng #{{ $hd->MaHD }}</h1>
    <a href="{{ route('admin.donhang.index') }}" class="btn btn-sm btn-outline-secondary">← Danh sách</a>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm"><div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th class="text-end">Thành tiền</th></tr></thead>
                @foreach ($hd->chiTiet as $ct)
                    <tr><td>{{ $ct->sanPham?->TenSP }}</td><td>{{ $ct->SoLuong }}</td><td>{{ tien($ct->DonGia) }}</td><td class="text-end">{{ tien($ct->SoLuong * $ct->DonGia) }}</td></tr>
                @endforeach
                @if ($hd->TienGiamGia > 0)
                    <tr><td colspan="3" class="text-end">Giảm giá ({{ $hd->MaKM }})</td><td class="text-end">-{{ tien($hd->TienGiamGia) }}</td></tr>
                @endif
                <tr class="fw-bold"><td colspan="3" class="text-end">Tổng</td><td class="text-end">{{ tien($hd->TongTien) }}</td></tr>
            </table>
        </div></div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-3 mb-3 small">
            <div><strong>Khách hàng:</strong> {{ $hd->khachHang?->HoTen }}</div>
            <div><strong>Giao đến:</strong> {{ $hd->DiaChiGiaoHang }}</div>
            <div><strong>Điện thoại:</strong> {{ $hd->SoDienThoaiGiaoHang }}</div>
            <div><strong>Ngày lập:</strong> {{ $hd->NgayLap?->format('d/m/Y H:i') }}</div>
            <div><strong>Thanh toán:</strong> {{ $hd->PhuongThucThanhToan }}</div>
        </div>
        <form action="{{ route('admin.donhang.status', $hd) }}" method="post" class="card border-0 shadow-sm p-3">
            @csrf
            <label class="form-label fw-bold">Trạng thái</label>
            <select name="trangThai" class="form-select mb-2" @disabled($hd->TrangThai === 'Đã hủy')>
                @foreach ($trangThais as $t)<option @selected($hd->TrangThai === $t)>{{ $t }}</option>@endforeach
                @unless (in_array($hd->TrangThai, $trangThais, true))<option selected>{{ $hd->TrangThai }}</option>@endunless
            </select>
            @error('trangThai')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
            <button class="btn btn-primary" @disabled($hd->TrangThai === 'Đã hủy')>Cập nhật</button>
            <div class="form-text">Chuyển sang "Đã hủy" sẽ hoàn lại tồn kho.</div>
        </form>
    </div>
</div>
@endsection
