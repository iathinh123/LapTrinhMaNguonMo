<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Services\DonHangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DonHangController extends Controller
{
    public const TRANG_THAI = [HoaDon::ST_COD, 'Đang giao', HoaDon::ST_DA_GIAO, HoaDon::ST_DA_HUY];

    public function __construct(private DonHangService $donHang) {}

    public function index(Request $request): View
    {
        $ds = HoaDon::with('khachHang')
            ->when($request->filled('keyword'), fn ($q) => $q->where('MaHD', 'like', '%'.trim($request->query('keyword')).'%'))
            ->when($request->filled('trangThai'), fn ($q) => $q->where('TrangThai', $request->query('trangThai')))
            ->orderByDesc('NgayLap')->orderByDesc('MaHD')->paginate(10)->withQueryString();

        return view('admin.donhang.index', ['ds' => $ds, 'trangThais' => self::TRANG_THAI]);
    }

    public function show(HoaDon $donhang): View
    {
        return view('admin.donhang.show', ['hd' => $donhang->load('khachHang', 'chiTiet.sanPham'), 'trangThais' => self::TRANG_THAI]);
    }

    /** Đổi trạng thái; chuyển sang "Đã hủy" sẽ hoàn kho. Đơn đã hủy không đổi lại được. */
    public function capNhatTrangThai(Request $request, HoaDon $donhang): RedirectResponse
    {
        $moi = $request->validate(['trangThai' => ['required', Rule::in(self::TRANG_THAI)]])['trangThai'];

        if ($donhang->TrangThai === $moi) {
            return back()->with('warning', 'Trạng thái không thay đổi.');
        }
        if ($donhang->TrangThai === HoaDon::ST_DA_HUY) {
            return back()->with('error', 'Đơn hàng đã hủy không thể đổi trạng thái.');
        }

        if ($moi === HoaDon::ST_DA_HUY) {
            $this->donHang->huy($donhang->MaHD);
        } else {
            $donhang->update(['TrangThai' => $moi, 'NgayThanhToan' => $moi === HoaDon::ST_DA_GIAO ? now() : $donhang->NgayThanhToan]);
        }

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
