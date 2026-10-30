@extends('layouts.customer')
@section('title', 'Giỏ hàng - '.config('app.name'))

@section('content')
<div class="container main-container my-4">
    <h4 class="fw-bold mb-3"><i class="fas fa-shopping-cart text-primary me-2"></i>Giỏ hàng</h4>

    @if ($dong->isEmpty())
        <div class="card-modern p-5 text-center">
            <p class="text-muted">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('home') }}" class="btn btn-primary-modern d-inline-block px-4">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="card-modern p-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th style="width:160px">Số lượng</th><th class="text-end">Thành tiền</th><th></th></tr></thead>
                    <tbody>
                    @foreach ($dong as $d)
                        <tr>
                            <td><a href="{{ route('product.show', $d->sanPham) }}" class="text-decoration-none">{{ $d->sanPham->TenSP }}</a></td>
                            <td>{{ tien($d->sanPham->Gia) }}</td>
                            <td>
                                <form action="{{ route('cart.update') }}" method="post" class="d-flex gap-1">
                                    @csrf
                                    <input type="hidden" name="maSp" value="{{ $d->MaSP }}">
                                    <input type="number" name="soLuong" value="{{ $d->SoLuong }}" min="0" class="form-control form-control-sm">
                                    <button class="btn btn-sm btn-outline-secondary" title="Cập nhật"><i class="fas fa-sync"></i></button>
                                </form>
                            </td>
                            <td class="text-end">{{ tien($d->SoLuong * $d->sanPham->Gia) }}</td>
                            <td class="text-end">
                                <form action="{{ route('cart.remove', $d->MaSP) }}" method="post">@csrf <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="fs-5">Tổng: <strong class="product-price">{{ tien($tong) }}</strong></div>
                <a href="{{ route('checkout.form') }}" class="btn btn-primary-modern px-4">Thanh toán</a>
            </div>
        </div>
    @endif
</div>
@endsection
