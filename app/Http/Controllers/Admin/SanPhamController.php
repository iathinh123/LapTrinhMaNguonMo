<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SanPhamRequest;
use App\Models\DanhMuc;
use App\Models\NhaCungCap;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SanPhamController extends Controller
{
    public function index(Request $request): View
    {
        $sanPhams = SanPham::with('danhMuc')
            ->timKiem(trim((string) $request->query('keyword')))
            ->orderByDesc('CreatedDate')->orderByDesc('MaSP')
            ->paginate(10)->withQueryString();

        return view('admin.products.index', ['sanPhams' => $sanPhams, 'tuKhoa' => $request->query('keyword')]);
    }

    public function create(): View
    {
        return view('admin.products.form', $this->danhSachChon() + ['sp' => new SanPham]);
    }

    public function store(SanPhamRequest $request): RedirectResponse
    {
        $d = $request->safe()->except('HinhAnh');
        $d['MaSP'] = IdGen::numeric('SanPham', 'MaSP', 'SP', 3);
        $d['HinhAnh'] = $this->luuAnh($request);
        $d['CreatedBy'] = $request->user()->TenDangNhap;
        $d['CreatedDate'] = now();

        $sp = SanPham::create($d);
        $sp->capNhatTinhTrang();

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm '.$sp->MaSP.'.');
    }

    public function edit(SanPham $product): View
    {
        return view('admin.products.form', $this->danhSachChon() + ['sp' => $product]);
    }

    public function update(SanPhamRequest $request, SanPham $product): RedirectResponse
    {
        $d = $request->safe()->except('HinhAnh');
        if ($anh = $this->luuAnh($request)) {
            $this->xoaAnhCu($product);
            $d['HinhAnh'] = $anh;
        }
        $d['LastModifiedBy'] = $request->user()->TenDangNhap;
        $d['LastModifiedDate'] = now();

        $product->update($d);
        $product->capNhatTinhTrang();

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    /** Xóa mềm: sản phẩm biến mất khỏi cửa hàng nhưng hóa đơn cũ vẫn giữ nguyên dữ liệu. */
    public function destroy(SanPham $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    private function danhSachChon(): array
    {
        return ['danhMucs' => DanhMuc::orderBy('TenDM')->get(), 'nhaCungCaps' => NhaCungCap::orderBy('TenNCC')->get()];
    }

    private function luuAnh(Request $request): ?string
    {
        if (! $request->hasFile('HinhAnh')) {
            return null;
        }

        return basename(Storage::disk('public')->putFile('products', $request->file('HinhAnh')));
    }

    private function xoaAnhCu(SanPham $sp): void
    {
        if (filled($sp->HinhAnh)) {
            Storage::disk('public')->delete('products/'.$sp->HinhAnh); // ảnh mẫu trong public/images không bị ảnh hưởng
        }
    }
}
