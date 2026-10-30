@extends('layouts.admin')
@section('title', 'Khuyến mãi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Khuyến mãi</h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Mã hoặc tên">
            <button class="btn btn-sm btn-outline-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.khuyenmai.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm</a>
    </div>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Mã</th><th>Tên</th><th>Giảm</th><th>Đơn tối thiểu</th><th>Còn</th><th>Hiệu lực</th><th>Trạng thái</th><th></th></tr></thead>
        <tbody>
        @forelse ($ds as $km)
            <tr>
                <td><code>{{ $km->MaCode }}</code></td><td>{{ $km->TenKM }}</td>
                <td>{{ $km->PhanTramGiam }}% (tối đa {{ tien($km->TienGiamToiDa) }})</td>
                <td>{{ tien($km->DonToiThieu) }}</td><td>{{ $km->SoLuong }}</td>
                <td class="small">{{ $km->NgayBatDau?->format('d/m/Y') }} – {{ $km->NgayKetThuc?->format('d/m/Y') }}</td>
                <td><span class="badge {{ $km->TrangThai ? 'bg-success' : 'bg-secondary' }}">{{ $km->TrangThai ? 'Bật' : 'Tắt' }}</span></td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('admin.khuyenmai.edit', $km) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <form action="{{ route('admin.khuyenmai.destroy', $km) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa khuyến mãi này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Không có khuyến mãi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
