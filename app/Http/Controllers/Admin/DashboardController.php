<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'soSanPham' => SanPham::count(),
            'soKhachHang' => KhachHang::count(),
            'soDon' => HoaDon::count(),
            'donCho' => HoaDon::where('TrangThai', 'like', 'Chờ%')->count(),
            'doanhThu' => (float) HoaDon::where('TrangThai', HoaDon::ST_DA_GIAO)->sum('TongTien'),
            'sapHet' => SanPham::whereColumn('SoLuong', '<=', 'NguongToiThieu')->orderBy('SoLuong')->limit(10)->get(),
            'donMoi' => HoaDon::with('khachHang')->orderByDesc('NgayLap')->orderByDesc('MaHD')->limit(5)->get(),
        ]);
    }
}
