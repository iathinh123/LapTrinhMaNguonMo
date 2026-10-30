<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaCungCap;
use App\Models\PhieuNhap;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NhapKhoController extends Controller
{
    public function index(Request $request): View
    {
        $ds = PhieuNhap::with('nhaCungCap')
            ->when($request->filled('keyword'), fn ($q) => $q->where('MaPN', 'like', '%'.trim($request->query('keyword')).'%'))
            ->orderByDesc('NgayNhap')->orderByDesc('MaPN')->paginate(10)->withQueryString();

        return view('admin.nhapkho.index', ['ds' => $ds]);
    }

    public function create(): View
    {
        return view('admin.nhapkho.create', [
            'nhaCungCaps' => NhaCungCap::orderBy('TenNCC')->get(), 'sanPhams' => SanPham::orderBy('TenSP')->get(), 'soDong' => 5,
        ]);
    }

    /** Tạo phiếu nhập, cộng tồn kho từng sản phẩm. Các dòng để trống sản phẩm sẽ bị bỏ qua. */
    public function store(Request $request): RedirectResponse
    {
        $d = $request->validate([
            'MaNCC' => ['required', 'exists:NhaCungCap,MaNCC'],
            'GhiChu' => ['nullable', 'string', 'max:255'],
            'dong' => ['required', 'array'],
            'dong.*.MaSP' => ['nullable', 'exists:SanPham,MaSP'],
            'dong.*.SoLuong' => ['nullable', 'integer', 'min:1'],
            'dong.*.GiaNhap' => ['nullable', 'numeric', 'min:0'],
        ], [
            'MaNCC.required' => 'Vui lòng chọn nhà cung cấp.',
            'dong.*.SoLuong.min' => 'Số lượng nhập phải lớn hơn 0.',
        ]);

        $dong = collect($d['dong'])->filter(fn ($r) => filled($r['MaSP'] ?? null));
        if ($dong->isEmpty() || $dong->contains(fn ($r) => blank($r['SoLuong'] ?? null) || blank($r['GiaNhap'] ?? null))) {
            return back()->withInput()->withErrors(['dong' => 'Hãy chọn ít nhất một sản phẩm và nhập đủ số lượng, giá nhập cho mỗi dòng đã chọn.']);
        }

        $pn = DB::transaction(function () use ($d, $dong, $request) {
            $pn = PhieuNhap::create([
                'MaPN' => IdGen::numeric('PhieuNhap', 'MaPN', 'PN', 3), 'MaNV' => $request->user()->nhanVien?->MaNV,
                'MaNCC' => $d['MaNCC'], 'NgayNhap' => now(), 'GhiChu' => $d['GhiChu'] ?? null,
            ]);

            $tong = 0;
            foreach ($dong->groupBy('MaSP') as $maSp => $cacDong) { // gộp các dòng trùng sản phẩm (khóa kép MaPN+MaSP)
                $sl = (int) $cacDong->sum('SoLuong');
                $gia = (float) $cacDong->last()['GiaNhap'];
                $pn->chiTiet()->create(['MaSP' => $maSp, 'SoLuongNhap' => $sl, 'GiaNhap' => $gia]);
                $sp = SanPham::lockForUpdate()->find($maSp);
                $sp->increment('SoLuong', $sl);
                $sp->refresh()->capNhatTinhTrang();
                $tong += $sl * $gia;
            }
            $pn->update(['TongTienNhap' => $tong]);

            return $pn;
        });

        return redirect()->route('admin.nhapkho.show', $pn)->with('success', 'Đã tạo phiếu nhập '.$pn->MaPN.' và cập nhật tồn kho.');
    }

    public function show(PhieuNhap $nhapkho): View
    {
        return view('admin.nhapkho.show', ['pn' => $nhapkho->load('nhaCungCap', 'nhanVien', 'chiTiet.sanPham')]);
    }
}
