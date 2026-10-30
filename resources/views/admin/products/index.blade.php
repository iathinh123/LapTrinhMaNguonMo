@extends('layouts.admin')
@section('title', 'Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Sản phẩm</h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input name="keyword" value="{{ $tuKhoa }}" class="form-control form-control-sm" placeholder="Tìm theo tên">
            <button class="btn btn-sm btn-outline-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm</a>
    </div>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Ảnh</th><th>Tên</th><th>Danh mục</th><th>Giá</th><th>Tồn</th><th>Tình trạng</th><th></th></tr></thead>
        <tbody>
        @forelse ($sanPhams as $sp)
            <tr>
                <td>{{ $sp->MaSP }}</td>
                <td><img src="{{ $sp->anhUrl() }}" alt="" width="48" height="48" style="object-fit: cover"></td>
                <td>{{ $sp->TenSP }}</td>
                <td>{{ $sp->danhMuc?->TenDM }}</td>
                <td>{{ tien($sp->Gia) }}</td>
                <td>{{ $sp->SoLuong }}</td>
                <td>{{ $sp->TinhTrang }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('admin.products.edit', $sp) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $sp) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Không có sản phẩm.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $sanPhams->links() }}</div>
@endsection
