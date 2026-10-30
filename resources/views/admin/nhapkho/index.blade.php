@extends('layouts.admin')
@section('title', 'Nhập kho')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Phiếu nhập kho</h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Mã phiếu">
            <button class="btn btn-sm btn-outline-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.nhapkho.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Nhập kho</a>
    </div>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Nhà cung cấp</th><th>Ngày nhập</th><th>Tổng tiền</th><th>Ghi chú</th><th></th></tr></thead>
        <tbody>
        @forelse ($ds as $pn)
            <tr>
                <td>{{ $pn->MaPN }}</td><td>{{ $pn->nhaCungCap?->TenNCC }}</td><td>{{ $pn->NgayNhap?->format('d/m/Y H:i') }}</td>
                <td>{{ tien($pn->TongTienNhap) }}</td><td>{{ $pn->GhiChu }}</td>
                <td class="text-end"><a href="{{ route('admin.nhapkho.show', $pn) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Chưa có phiếu nhập.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
