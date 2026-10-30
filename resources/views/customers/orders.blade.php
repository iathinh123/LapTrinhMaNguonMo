@extends('layouts.customer')
@section('title', 'Đơn hàng của tôi - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <h4 class="fw-bold mb-3"><i class="fas fa-box text-primary me-2"></i>Đơn hàng của tôi</h4>

    @forelse ($donHangs as $hd)
        <div class="card-modern p-3 mb-3">
            <div class="d-flex justify-content-between flex-wrap gap-2">
                <div><strong>#{{ $hd->MaHD }}</strong> <span class="text-muted small ms-2">{{ $hd->NgayLap?->format('d/m/Y H:i') }}</span></div>
                <span class="badge {{ $hd->TrangThai === 'Đã hủy' ? 'bg-danger' : ($hd->TrangThai === 'Đã giao' ? 'bg-success' : 'bg-warning text-dark') }}">{{ $hd->TrangThai }}</span>
            </div>
            <ul class="small mt-2 mb-2">
                @foreach ($hd->chiTiet as $ct)<li>{{ $ct->sanPham?->TenSP }} × {{ $ct->SoLuong }} — {{ tien($ct->DonGia) }}</li>@endforeach
            </ul>
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">{{ $hd->DiaChiGiaoHang }} · {{ $hd->SoDienThoaiGiaoHang }}
                    @if ($hd->TienGiamGia > 0) · Giảm {{ tien($hd->TienGiamGia) }} @endif</div>
                <div class="d-flex align-items-center gap-3">
                    <strong class="product-price">{{ tien($hd->TongTien) }}</strong>
                    @if ($hd->coTheHuy())
                        <form action="{{ route('orders.cancel', $hd->MaHD) }}" method="post" onsubmit="return confirm('Hủy đơn hàng này?')">
                            @csrf <button class="btn btn-sm btn-outline-danger">Hủy đơn</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card-modern p-5 text-center text-muted">Bạn chưa có đơn hàng nào.</div>
    @endforelse

    <div class="d-flex justify-content-center">{{ $donHangs->links() }}</div>
</div>
@endsection
