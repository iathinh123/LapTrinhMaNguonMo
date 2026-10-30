@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="h4 mb-3">Dashboard</h1>

<div class="row g-3 mb-4">
    @foreach ([['Sản phẩm', $soSanPham, 'fa-box-open', 'primary'], ['Khách hàng', $soKhachHang, 'fa-users', 'info'], ['Đơn hàng', $soDon.' ('.$donCho.' chờ)', 'fa-shopping-cart', 'warning'], ['Doanh thu (đã giao)', tien($doanhThu), 'fa-coins', 'success']] as [$nhan, $so, $icon, $mau])
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <i class="fas {{ $icon }} fs-3 text-{{ $mau }}"></i>
                <div><div class="text-muted small">{{ $nhan }}</div><div class="fw-bold">{{ $so }}</div></div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-bold">Đơn hàng mới nhất</div>
            <table class="table mb-0 small">
                @forelse ($donMoi as $hd)
                    <tr><td><a href="{{ route('admin.donhang.show', $hd) }}">#{{ $hd->MaHD }}</a></td><td>{{ $hd->khachHang?->HoTen }}</td><td>{{ tien($hd->TongTien) }}</td><td>{{ $hd->TrangThai }}</td></tr>
                @empty
                    <tr><td class="text-muted">Chưa có đơn hàng.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-bold text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Sản phẩm sắp hết hàng</div>
            <table class="table mb-0 small">
                @forelse ($sapHet as $sp)
                    <tr><td><a href="{{ route('admin.products.edit', $sp) }}">{{ $sp->TenSP }}</a></td><td>Còn {{ $sp->SoLuong }} (ngưỡng {{ $sp->NguongToiThieu }})</td></tr>
                @empty
                    <tr><td class="text-muted">Tồn kho đều ổn.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</div>
@endsection
