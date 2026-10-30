@extends('layouts.admin')
@section('title', 'Nhà cung cấp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Nhà cung cấp</h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm theo tên">
            <button class="btn btn-sm btn-outline-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.ncc.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm</a>
    </div>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Tên</th><th>Địa chỉ</th><th>Điện thoại</th><th>Email</th><th>SP</th><th></th></tr></thead>
        <tbody>
        @forelse ($ds as $n)
            <tr>
                <td>{{ $n->MaNCC }}</td><td>{{ $n->TenNCC }}</td><td>{{ $n->DiaChi }}</td><td>{{ $n->SoDienThoai }}</td><td>{{ $n->Email }}</td><td>{{ $n->san_phams_count }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('admin.ncc.edit', $n) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <form action="{{ route('admin.ncc.destroy', $n) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa nhà cung cấp này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Không có nhà cung cấp.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
