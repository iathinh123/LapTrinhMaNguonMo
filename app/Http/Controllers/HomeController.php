<?php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const SO_SP_MOI_TRANG = 12;

    /** Danh sách sản phẩm: tìm kiếm + lọc danh mục + sắp xếp + phân trang (giữ nguyên tham số khi chuyển trang). */
    public function index(Request $request): View
    {
        $tuKhoa = trim((string) ($request->query('keyword') ?? $request->query('search')));
        $maDm = (string) $request->query('category');
        $sapXep = (string) $request->query('sort');

        $sanPhams = SanPham::query()
            ->timKiem($tuKhoa)
            ->when($maDm !== '', fn ($q) => $q->where('MaDM', $maDm))
            ->when($sapXep === 'priceAsc', fn ($q) => $q->orderBy('Gia'))
            ->when($sapXep === 'priceDesc', fn ($q) => $q->orderByDesc('Gia'))
            ->when(! in_array($sapXep, ['priceAsc', 'priceDesc'], true), fn ($q) => $q->orderByDesc('CreatedDate')->orderByDesc('MaSP'))
            ->paginate(self::SO_SP_MOI_TRANG)
            ->withQueryString();

        return view('customers.index', [
            'sanPhams' => $sanPhams,
            'danhMucs' => DanhMuc::orderBy('MaDM')->get(),
            'tuKhoa' => $tuKhoa, 'maDm' => $maDm, 'sapXep' => $sapXep,
        ]);
    }

    public function show(SanPham $sanPham): View
    {
        $binhLuans = $sanPham->binhLuans()->with('khachHang')->orderByDesc('NgayDang')->get();

        return view('customers.product-detail', [
            'sp' => $sanPham, 'binhLuans' => $binhLuans, 'diemTB' => (float) $binhLuans->avg('SoSao'),
        ]);
    }
}
