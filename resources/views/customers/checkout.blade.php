@extends('layouts.customer')
@section('title', 'Thanh toán - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <h4 class="fw-bold mb-3"><i class="fas fa-credit-card text-primary me-2"></i>Thanh toán</h4>
    <div class="row g-4">
        <div class="col-lg-7">
            <form action="{{ route('checkout.store') }}" method="post" class="card-modern p-4">
                @csrf
                @if ($maSp)
                    <input type="hidden" name="maSp" value="{{ $maSp }}">
                    <input type="hidden" name="soLuong" value="{{ $dong->first()->SoLuong }}">
                @endif
                <div class="mb-3">
                    <label class="form-label">Địa chỉ nhận hàng</label>
                    <input name="diaChi" value="{{ old('diaChi', $kh->DiaChi) }}" class="form-control @error('diaChi') is-invalid @enderror" required>
                    @error('diaChi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input name="sdt" value="{{ old('sdt', $kh->DienThoai) }}" class="form-control @error('sdt') is-invalid @enderror" required>
                    @error('sdt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Mã khuyến mãi (nếu có)</label>
                    <input name="maKm" value="{{ old('maKm') }}" class="form-control @error('maKm') is-invalid @enderror" placeholder="VD: GIAM10">
                    @error('maKm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <p class="text-muted small"><i class="fas fa-money-bill-wave me-1"></i>Thanh toán khi nhận hàng (COD).</p>
                <button class="btn btn-primary-modern px-4">ĐẶT HÀNG</button>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="card-modern p-4">
                <h6 class="fw-bold">Đơn hàng của bạn</h6>
                @foreach ($dong as $d)
                    <div class="d-flex justify-content-between small py-1"><span>{{ $d->sanPham->TenSP }} × {{ $d->SoLuong }}</span><span>{{ tien($d->SoLuong * $d->sanPham->Gia) }}</span></div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Tạm tính</span><span class="product-price">{{ tien($tong) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
