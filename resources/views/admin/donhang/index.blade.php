@extends('layouts.admin')
@section('title', 'Đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Đơn hàng</h1>
    <form class="d-flex gap-2" method="get">
        <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Mã đơn">
        <select name="trangThai" class="form-select form-select-sm">
            <option value="">Tất cả trạng thái</option>
            @foreach ($trangThais as $t)<option @selected(request('trangThai') === $t)>{{ $t }}</option>@endforeach
        </select>
        <button class="btn btn-sm btn-outline-secondary">Lọc</button>
    </form>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Khách hàng</th><th>Ngày lập</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead>
        <tbody>
        @forelse ($ds as $hd)
            <tr>
                <td>#{{ $hd->MaHD }}</td><td>{{ $hd->khachHang?->HoTen }}</td><td>{{ $hd->NgayLap?->format('d/m/Y H:i') }}</td>
                <td>{{ tien($hd->TongTien) }}</td><td>{{ $hd->TrangThai }}</td>
                <td class="text-end"><a href="{{ route('admin.donhang.show', $hd) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Không có đơn hàng.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
