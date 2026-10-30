<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KhuyenMaiRequest;
use App\Models\KhuyenMai;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KhuyenMaiController extends Controller
{
    public function index(Request $request): View
    {
        $ds = KhuyenMai::query()
            ->when($request->filled('keyword'), fn ($q) => $q->where(fn ($w) => $w
                ->where('MaCode', 'like', '%'.trim($request->query('keyword')).'%')
                ->orWhere('TenKM', 'like', '%'.trim($request->query('keyword')).'%')))
            ->orderByDesc('MaKM')->paginate(10)->withQueryString();

        return view('admin.khuyenmai.index', ['ds' => $ds]);
    }

    public function create(): View
    {
        return view('admin.khuyenmai.form', ['km' => new KhuyenMai(['TrangThai' => true, 'LoaiKM' => 'NHAP_MA', 'SoLuong' => 100])]);
    }

    public function store(KhuyenMaiRequest $request): RedirectResponse
    {
        KhuyenMai::create($this->duLieu($request) + ['MaKM' => IdGen::numeric('KhuyenMai', 'MaKM', 'KM', 3)]);

        return redirect()->route('admin.khuyenmai.index')->with('success', 'Đã thêm khuyến mãi.');
    }

    public function edit(KhuyenMai $khuyenmai): View
    {
        return view('admin.khuyenmai.form', ['km' => $khuyenmai]);
    }

    public function update(KhuyenMaiRequest $request, KhuyenMai $khuyenmai): RedirectResponse
    {
        $khuyenmai->update($this->duLieu($request));

        return redirect()->route('admin.khuyenmai.index')->with('success', 'Đã cập nhật khuyến mãi.');
    }

    public function destroy(KhuyenMai $khuyenmai): RedirectResponse
    {
        $khuyenmai->delete(); // xóa mềm, hóa đơn đã dùng mã vẫn giữ liên kết

        return redirect()->route('admin.khuyenmai.index')->with('success', 'Đã xóa khuyến mãi.');
    }

    private function duLieu(KhuyenMaiRequest $request): array
    {
        return $request->safe()->except('TrangThai') + ['TrangThai' => $request->boolean('TrangThai')];
    }
}
