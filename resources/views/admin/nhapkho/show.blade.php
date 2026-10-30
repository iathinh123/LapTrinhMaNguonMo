@extends('layouts.admin')
@section('title', 'Phiếu nhập '.$pn->MaPN)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Phiếu nhập {{ $pn->MaPN }}</h1>
    <a href="{{ route('admin.nhapkho.index') }}" class="btn btn-sm btn-outline-secondary">← Danh sách</a>
</div>
<div class="card border-0 shadow-sm p-3 mb-3 small">
    <div><strong>Nhà cung cấp:</strong> {{ $pn->nhaCungCap?->TenNCC }}</div>
    <div><strong>Nhân viên:</strong> {{ $pn->nhanVien?->HoTen ?? '—' }}</div>
    <div><strong>Ngày nhập:</strong> {{ $pn->NgayNhap?->format('d/m/Y H:i') }}</div>
    <div><strong>Ghi chú:</strong> {{ $pn->GhiChu }}</div>
</div>
<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá nhập</th><th class="text-end">Thành tiền</th></tr></thead>
        @foreach ($pn->chiTiet as $ct)
            <tr><td>{{ $ct->sanPham?->TenSP }}</td><td>{{ $ct->SoLuongNhap }}</td><td>{{ tien($ct->GiaNhap) }}</td><td class="text-end">{{ tien($ct->SoLuongNhap * $ct->GiaNhap) }}</td></tr>
        @endforeach
        <tr class="fw-bold"><td colspan="3" class="text-end">Tổng</td><td class="text-end">{{ tien($pn->TongTienNhap) }}</td></tr>
    </table>
</div></div>
@endsection
