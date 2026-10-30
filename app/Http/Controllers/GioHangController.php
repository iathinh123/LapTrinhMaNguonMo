<?php

namespace App\Http\Controllers;

use App\Models\ChiTietGioHang;
use App\Services\GioHangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GioHangController extends Controller
{
    public function __construct(private GioHangService $gio) {}

    public function index(Request $request): View
    {
        $dong = ChiTietGioHang::with('sanPham')->where('MaKH', $this->khachHang($request)->MaKH)->get();
        $tong = $dong->sum(fn ($d) => $d->SoLuong * (float) $d->sanPham->Gia);

        return view('customers.cart', ['dong' => $dong, 'tong' => $tong]);
    }

    public function store(Request $request): RedirectResponse
    {
        $d = $request->validate(['maSp' => ['required', 'string'], 'soLuong' => ['nullable', 'integer', 'min:1']]);
        $this->gio->them($this->khachHang($request), $d['maSp'], (int) ($d['soLuong'] ?? 1));

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
    }

    public function update(Request $request): RedirectResponse
    {
        $d = $request->validate(['maSp' => ['required', 'string'], 'soLuong' => ['required', 'integer', 'min:0']]);
        $this->gio->capNhat($this->khachHang($request), $d['maSp'], (int) $d['soLuong']);

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, string $maSp): RedirectResponse
    {
        $this->gio->xoa($this->khachHang($request), $maSp);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
