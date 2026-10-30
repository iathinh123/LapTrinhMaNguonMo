@extends('layouts.customer')
@section('title', $sp->TenSP.' - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <div class="card-modern p-4">
        <div class="row g-4">
            <div class="col-md-5 text-center"><img src="{{ $sp->anhUrl() }}" alt="{{ $sp->TenSP }}" class="img-fluid rounded" style="max-height: 380px;"></div>
            <div class="col-md-7">
                <h3 class="fw-bold">{{ $sp->TenSP }}</h3>
                <div class="text-warning mb-2">
                    @for ($i = 1; $i <= 5; $i++)<i class="fa{{ $i <= round($diemTB) ? 's' : 'r' }} fa-star"></i>@endfor
                    <span class="text-muted small ms-1">({{ $binhLuans->count() }} đánh giá)</span>
                </div>
                <div class="product-price fs-3 mb-3">{{ tien($sp->Gia) }}</div>
                <p class="text-muted">{{ $sp->MoTa }}</p>
                <p class="small">Danh mục: <strong>{{ $sp->danhMuc?->TenDM ?? '—' }}</strong> · Tình trạng:
                    <span class="badge {{ $sp->conHang() ? 'bg-success' : 'bg-danger' }}">{{ $sp->conHang() ? 'Còn '.$sp->SoLuong.' sản phẩm' : 'Hết hàng' }}</span></p>

                @if ($sp->conHang())
                    <form method="post" class="d-flex gap-2 align-items-center flex-wrap">
                        @csrf
                        <input type="hidden" name="maSp" value="{{ $sp->MaSP }}">
                        <input type="number" name="soLuong" value="1" min="1" max="{{ $sp->SoLuong }}" class="form-control" style="width: 90px;">
                        <button formaction="{{ route('cart.add') }}" class="btn btn-outline-primary"><i class="fas fa-cart-plus"></i> Thêm vào giỏ</button>
                        <a href="{{ route('checkout.form', ['maSp' => $sp->MaSP, 'soLuong' => 1]) }}" class="btn btn-primary-modern px-4">Mua ngay</a>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card-modern p-4 mt-4">
        <h5 class="fw-bold mb-3">Đánh giá sản phẩm</h5>

        @auth
            @if (auth()->user()->khachHang)
                <form action="{{ route('review.store') }}" method="post" class="mb-4">
                    @csrf
                    <input type="hidden" name="maSp" value="{{ $sp->MaSP }}">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select name="soSao" class="form-select">
                                @foreach ([5, 4, 3, 2, 1] as $s)<option value="{{ $s }}" @selected(old('soSao', 5) == $s)>{{ $s }} sao</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-8"><input name="noiDung" value="{{ old('noiDung') }}" class="form-control @error('noiDung') is-invalid @enderror" placeholder="Nhận xét của bạn" maxlength="500">
                            @error('noiDung')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="col-md-2 d-grid"><button class="btn btn-primary-modern">Gửi</button></div>
                    </div>
                </form>
            @endif
        @else
            <p class="text-muted"><a href="{{ route('login') }}">Đăng nhập</a> để gửi đánh giá.</p>
        @endauth

        @forelse ($binhLuans as $bl)
            <div class="border-top py-3 d-flex justify-content-between">
                <div>
                    <strong>{{ $bl->khachHang?->HoTen ?? 'Khách hàng' }}</strong>
                    <span class="text-warning ms-2">@for ($i = 1; $i <= $bl->SoSao; $i++)<i class="fas fa-star"></i>@endfor</span>
                    <small class="text-muted ms-2">{{ $bl->NgayDang?->format('d/m/Y H:i') }}</small>
                    <div>{{ $bl->NoiDung }}</div>
                </div>
                @auth
                    @if (auth()->user()->laQuanTri() || auth()->user()->khachHang?->MaKH === $bl->MaKH)
                        <form action="{{ route('review.destroy', $bl) }}" method="post" onsubmit="return confirm('Xóa đánh giá này?')">
                            @csrf <button class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <p class="text-muted mb-0">Chưa có đánh giá nào.</p>
        @endforelse
    </div>
</div>
@endsection
