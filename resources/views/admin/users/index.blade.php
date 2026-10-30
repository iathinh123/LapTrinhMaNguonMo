@extends('layouts.admin')
@section('title', 'Người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h4 mb-0">Người dùng</h1>
    <form class="d-flex gap-2" method="get">
        <input name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tên đăng nhập">
        <button class="btn btn-sm btn-outline-secondary">Tìm</button>
    </form>
</div>

<div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Tài khoản</th><th>Họ tên</th><th>Quyền</th><th>Trạng thái</th><th></th></tr></thead>
        <tbody>
        @foreach ($ds as $tk)
            <tr>
                <td>{{ $tk->TenDangNhap }}</td>
                <td>{{ $tk->khachHang?->HoTen }}</td>
                <td>
                    <form action="{{ route('admin.users.role', $tk) }}" method="post" class="d-flex gap-1">
                        @csrf
                        <select name="quyen" class="form-select form-select-sm" style="width: 150px">
                            @foreach (['ROLE_ADMIN', 'ROLE_STAFF', 'ROLE_CUSTOMER'] as $q)<option @selected($tk->Quyen === $q)>{{ $q }}</option>@endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-primary">Lưu</button>
                    </form>
                </td>
                <td><span class="badge {{ $tk->TrangThai ? 'bg-success' : 'bg-danger' }}">{{ $tk->TrangThai ? 'Hoạt động' : 'Bị khóa' }}</span></td>
                <td class="text-end">
                    <form action="{{ route('admin.users.lock', $tk) }}" method="post">
                        @csrf <button class="btn btn-sm btn-outline-{{ $tk->TrangThai ? 'danger' : 'success' }}">{{ $tk->TrangThai ? 'Khóa' : 'Mở khóa' }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div></div>
@error('quyen')<div class="text-danger mt-2">{{ $message }}</div>@enderror
<div class="mt-3 d-flex justify-content-center">{{ $ds->links() }}</div>
@endsection
