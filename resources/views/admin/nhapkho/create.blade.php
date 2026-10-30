@extends('layouts.admin')
@section('title', 'Nhập kho')

@section('content')
<h1 class="h4 mb-3">Tạo phiếu nhập kho</h1>

<form action="{{ route('admin.nhapkho.store') }}" method="post" class="card border-0 shadow-sm p-4" style="max-width: 900px">
    @csrf
    @error('dong')<div class="alert alert-danger">{{ $message }}</div>@enderror
    <div class="row">
        <div class="col-md-5 mb-3">
            <label class="form-label">Nhà cung cấp</label>
            <select name="MaNCC" class="form-select @error('MaNCC') is-invalid @enderror">
                <option value="">-- Chọn --</option>
                @foreach ($nhaCungCaps as $n)<option value="{{ $n->MaNCC }}" @selected(old('MaNCC') === $n->MaNCC)>{{ $n->TenNCC }}</option>@endforeach
            </select>
            @error('MaNCC')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-7 mb-3">
            <label class="form-label">Ghi chú</label>
            <input name="GhiChu" value="{{ old('GhiChu') }}" class="form-control">
        </div>
    </div>

    <table class="table align-middle">
        <thead><tr><th>Sản phẩm</th><th style="width:120px">Số lượng</th><th style="width:160px">Giá nhập (₫)</th></tr></thead>
        @for ($i = 0; $i < $soDong; $i++)
            <tr>
                <td>
                    <select name="dong[{{ $i }}][MaSP]" class="form-select form-select-sm">
                        <option value="">-- Bỏ trống --</option>
                        @foreach ($sanPhams as $sp)<option value="{{ $sp->MaSP }}" @selected(old("dong.$i.MaSP") === $sp->MaSP)>{{ $sp->TenSP }} (tồn {{ $sp->SoLuong }})</option>@endforeach
                    </select>
                </td>
                <td><input type="number" min="1" name="dong[{{ $i }}][SoLuong]" value="{{ old("dong.$i.SoLuong") }}" class="form-control form-control-sm"></td>
                <td><input type="number" min="0" name="dong[{{ $i }}][GiaNhap]" value="{{ old("dong.$i.GiaNhap") }}" class="form-control form-control-sm"></td>
            </tr>
        @endfor
    </table>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Tạo phiếu nhập</button>
        <a href="{{ route('admin.nhapkho.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
