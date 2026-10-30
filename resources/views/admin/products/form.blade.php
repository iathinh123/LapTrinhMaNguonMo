@extends('layouts.admin')
@section('title', $sp->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
<h1 class="h4 mb-3">{{ $sp->exists ? 'Sửa sản phẩm '.$sp->MaSP : 'Thêm sản phẩm' }}</h1>

<form action="{{ $sp->exists ? route('admin.products.update', $sp) : route('admin.products.store') }}" method="post" enctype="multipart/form-data" class="card border-0 shadow-sm p-4" style="max-width: 760px">
    @csrf
    @if ($sp->exists) @method('PUT') @endif

    @php $o = fn ($k) => old($k, $sp->{$k}); @endphp
    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input name="TenSP" value="{{ $o('TenSP') }}" class="form-control @error('TenSP') is-invalid @enderror">
        @error('TenSP')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Danh mục</label>
            <select name="MaDM" class="form-select @error('MaDM') is-invalid @enderror">
                <option value="">-- Chọn --</option>
                @foreach ($danhMucs as $dm)<option value="{{ $dm->MaDM }}" @selected($o('MaDM') === $dm->MaDM)>{{ $dm->TenDM }}</option>@endforeach
            </select>
            @error('MaDM')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nhà cung cấp</label>
            <select name="MaNCC" class="form-select @error('MaNCC') is-invalid @enderror">
                <option value="">-- Chọn --</option>
                @foreach ($nhaCungCaps as $n)<option value="{{ $n->MaNCC }}" @selected($o('MaNCC') === $n->MaNCC)>{{ $n->TenNCC }}</option>@endforeach
            </select>
            @error('MaNCC')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row">
        @foreach ([['Gia', 'Giá (₫)', 0], ['SoLuong', 'Số lượng tồn', 0], ['NguongToiThieu', 'Ngưỡng cảnh báo', 10]] as [$k, $nhan, $macDinh])
            <div class="col-md-4 mb-3">
                <label class="form-label">{{ $nhan }}</label>
                <input type="number" name="{{ $k }}" value="{{ $o($k) ?? $macDinh }}" min="0" class="form-control @error($k) is-invalid @enderror">
                @error($k)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endforeach
    </div>
    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <input name="MoTa" value="{{ $o('MoTa') }}" class="form-control @error('MoTa') is-invalid @enderror">
        @error('MoTa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Hình ảnh</label>
        @if ($sp->exists && $sp->HinhAnh)<div class="mb-2"><img src="{{ $sp->anhUrl() }}" alt="" height="80"></div>@endif
        <input type="file" name="HinhAnh" accept="image/*" class="form-control @error('HinhAnh') is-invalid @enderror">
        @error('HinhAnh')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
