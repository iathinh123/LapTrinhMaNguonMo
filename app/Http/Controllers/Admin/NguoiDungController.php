<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NguoiDungController extends Controller
{
    public function index(Request $request): View
    {
        $ds = TaiKhoan::with('khachHang')
            ->when($request->filled('keyword'), fn ($q) => $q->where('TenDangNhap', 'like', '%'.trim($request->query('keyword')).'%'))
            ->orderBy('TenDangNhap')->paginate(10)->withQueryString();

        return view('admin.users.index', ['ds' => $ds]);
    }

    public function doiQuyen(Request $request, TaiKhoan $taiKhoan): RedirectResponse
    {
        $quyen = $request->validate([
            'quyen' => ['required', Rule::in([TaiKhoan::ROLE_ADMIN, TaiKhoan::ROLE_STAFF, TaiKhoan::ROLE_CUSTOMER])],
        ])['quyen'];

        if ($this->laChinhMinh($request, $taiKhoan)) {
            return back()->with('error', 'Bạn không thể đổi quyền của chính mình.');
        }
        $taiKhoan->update(['Quyen' => $quyen]);

        return back()->with('success', 'Đã đổi quyền của '.$taiKhoan->TenDangNhap.'.');
    }

    public function khoa(Request $request, TaiKhoan $taiKhoan): RedirectResponse
    {
        if ($this->laChinhMinh($request, $taiKhoan)) {
            return back()->with('error', 'Bạn không thể khóa chính mình.');
        }
        $taiKhoan->update(['TrangThai' => ! $taiKhoan->TrangThai]);

        return back()->with('success', ($taiKhoan->TrangThai ? 'Đã mở khóa ' : 'Đã khóa ').$taiKhoan->TenDangNhap.'.');
    }

    private function laChinhMinh(Request $request, TaiKhoan $taiKhoan): bool
    {
        return $request->user()->TenDangNhap === $taiKhoan->TenDangNhap;
    }
}
