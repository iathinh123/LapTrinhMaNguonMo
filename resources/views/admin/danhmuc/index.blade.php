@extends('layouts.admin')
@section('title', 'Danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Danh mục</h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm theo tên">
            <button class="btn btn-sm btn-outline-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.danhmuc.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm</a>
    </div>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Tên danh mục</th><th>Số sản phẩm</th><th></th></tr></thead>
        <tbody>
        @forelse ($ds as $dm)
            <tr>
                <td>{{ $dm->MaDM }}</td><td>{{ $dm->TenDM }}</td><td>{{ $dm->san_phams_count }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('admin.danhmuc.edit', $dm) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <form action="{{ route('admin.danhmuc.destroy', $dm) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Không có danh mục.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
