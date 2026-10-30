<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\ChiTietGioHang;
use App\Models\HoaDon;
use App\Models\SanPham;
use App\Services\DonHangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonHangController extends Controller
{
    public function __construct(private DonHangService $donHang) {}

    /** Trang thanh toán: "mua ngay" 1 sản phẩm (?maSp=&soLuong=) hoặc cả giỏ hàng. */
    public function checkoutForm(Request $request): View|RedirectResponse
    {
        $kh = $this->khachHang($request);

        if ($request->filled('maSp')) {
            $sp = SanPham::find($request->query('maSp'));
            $sl = max(1, (int) $request->query('soLuong', 1));
            if (! $sp) {
                return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại');
            }
            if ($sp->SoLuong < $sl) {
                return redirect()->route('product.show', $sp)->with('error', "Sản phẩm '{$sp->TenSP}' đã hết hàng hoặc không đủ tồn kho!");
            }
            $dong = collect([(object) ['sanPham' => $sp, 'SoLuong' => $sl]]);
        } else {
            $dong = ChiTietGioHang::with('sanPham')->where('MaKH', $kh->MaKH)->get();
            if ($dong->isEmpty()) {
                return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống!');
            }
        }

        $tong = $dong->sum(fn ($d) => $d->SoLuong * (float) $d->sanPham->Gia);

        return view('customers.checkout', ['kh' => $kh, 'dong' => $dong, 'tong' => $tong, 'maSp' => $request->query('maSp')]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $kh = $this->khachHang($request);
        $d = $request->validated();

        $muaNgay = filled($d['maSp'] ?? null);
        $dong = $muaNgay
            ? [$d['maSp'] => (int) ($d['soLuong'] ?? 1)]
            : ChiTietGioHang::where('MaKH', $kh->MaKH)->pluck('SoLuong', 'MaSP')->map(fn ($v) => (int) $v)->all();
        if (! $dong) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống!');
        }

        $this->donHang->tao($kh, $dong, $d['diaChi'], $d['sdt'], $d['maKm'] ?? null, xoaGio: ! $muaNgay);

        return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công! Nhân viên sẽ liên hệ với bạn sớm.');
    }

    public function index(Request $request): View
    {
        $donHangs = HoaDon::with('chiTiet.sanPham')->where('MaKH', $this->khachHang($request)->MaKH)
            ->orderByDesc('NgayLap')->orderByDesc('MaHD')->paginate(10);

        return view('customers.orders', ['donHangs' => $donHangs]);
    }

    public function huy(Request $request, string $maHd): RedirectResponse
    {
        $hd = HoaDon::findOrFail($maHd);
        abort_unless($hd->MaKH === $this->khachHang($request)->MaKH, 403, 'Bạn không có quyền hủy đơn hàng này.');

        $this->donHang->huy($maHd);

        return redirect()->route('orders.index')->with('success', 'Hủy đơn hàng thành công!');
    }
}
