@extends('layouts.admin')
@section('title', $ncc->exists ? 'Sửa nhà cung cấp' : 'Thêm nhà cung cấp')

@section('content')
<h1 class="h4 mb-3">{{ $ncc->exists ? 'Sửa nhà cung cấp '.$ncc->MaNCC : 'Thêm nhà cung cấp' }}</h1>

<form action="{{ $ncc->exists ? route('admin.ncc.update', $ncc) : route('admin.ncc.store') }}" method="post" class="card border-0 shadow-sm p-4" style="max-width: 560px">
    @csrf
    @if ($ncc->exists) @method('PUT') @endif
    @foreach ([['TenNCC', 'Tên nhà cung cấp'], ['DiaChi', 'Địa chỉ'], ['SoDienThoai', 'Số điện thoại'], ['Email', 'Email']] as [$k, $nhan])
        <div class="mb-3">
            <label class="form-label">{{ $nhan }}</label>
            <input name="{{ $k }}" value="{{ old($k, $ncc->{$k}) }}" class="form-control @error($k) is-invalid @enderror">
            @error($k)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endforeach
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.ncc.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
