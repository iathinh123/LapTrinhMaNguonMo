@extends('layouts.customer')
@section('title', 'Trang chủ - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <div class="d-flex flex-column flex-lg-row gap-4">
        {{-- Danh mục --}}
        <div class="sidebar-wrapper d-none d-lg-block">
            <div class="card-modern" style="top: 88px;">
                <div class="bg-surface px-4 py-3 border-bottom"><h6 class="fw-bold mb-0 text-uppercase"><i class="fas fa-list-ul text-primary me-2"></i>Danh mục</h6></div>
                <div class="p-2">
                    <a href="{{ route('home', request()->except(['category', 'page'])) }}" class="cat-menu-item {{ $maDm === '' ? 'active' : '' }}">Tất cả sản phẩm</a>
                    @foreach ($danhMucs as $dm)
                        <a href="{{ route('home', array_merge(request()->except('page'), ['category' => $dm->MaDM])) }}" class="cat-menu-item {{ $maDm === $dm->MaDM ? 'active' : '' }}">{{ $dm->TenDM }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="main-content-wrapper flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0 text-main">{{ $tuKhoa !== '' ? 'Kết quả cho "'.$tuKhoa.'"' : 'Sản phẩm' }}</h4>
                <form method="get" action="{{ route('home') }}" class="d-flex gap-2">
                    @foreach (request()->except(['sort', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()">
                        <option value="" @selected($sapXep === '')>Mới nhất</option>
                        <option value="priceAsc" @selected($sapXep === 'priceAsc')>Giá thấp đến cao</option>
                        <option value="priceDesc" @selected($sapXep === 'priceDesc')>Giá cao đến thấp</option>
                    </select>
                </form>
            </div>

            @forelse ($sanPhams as $sp)
                @if ($loop->first)<div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">@endif
                <div class="col">
                    <div class="product-card position-relative">
                        @unless ($sp->conHang())
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-1">Hết hàng</span>
                        @endunless
                        <a href="{{ route('product.show', $sp) }}" class="product-img-wrapper"><img src="{{ $sp->anhUrl() }}" alt="{{ $sp->TenSP }}"></a>
                        <a href="{{ route('product.show', $sp) }}" class="text-decoration-none"><div class="product-title">{{ $sp->TenSP }}</div></a>
                        <div class="mt-auto pt-2">
                            <div class="product-price mb-3">{{ tien($sp->Gia) }}</div>
                            <form action="{{ route('cart.add') }}" method="post">
                                @csrf
                                <input type="hidden" name="maSp" value="{{ $sp->MaSP }}">
                                <button class="btn btn-primary-modern py-2 w-100" @disabled(! $sp->conHang())><i class="fas fa-cart-plus"></i> Chọn mua</button>
                            </form>
                        </div>
                    </div>
                </div>
                @if ($loop->last)</div>@endif
            @empty
                <div class="card-modern p-5 text-center">
                    <h5 class="fw-bold text-secondary">Không tìm thấy sản phẩm!</h5>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">Xóa bộ lọc</a>
                </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">{{ $sanPhams->links() }}</div>
        </div>
    </div>
</div>
@endsection
