@extends('layouts.admin')
@section('title', $dm->exists ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<h1 class="h4 mb-3">{{ $dm->exists ? 'Sửa danh mục '.$dm->MaDM : 'Thêm danh mục' }}</h1>

<form action="{{ $dm->exists ? route('admin.danhmuc.update', $dm) : route('admin.danhmuc.store') }}" method="post" class="card border-0 shadow-sm p-4" style="max-width: 560px">
    @csrf
    @if ($dm->exists) @method('PUT') @endif
    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>
        <input name="TenDM" value="{{ old('TenDM', $dm->TenDM) }}" class="form-control @error('TenDM') is-invalid @enderror">
        @error('TenDM')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
