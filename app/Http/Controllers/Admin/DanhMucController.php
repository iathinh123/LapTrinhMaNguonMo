<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DanhMucRequest;
use App\Models\DanhMuc;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DanhMucController extends Controller
{
    public function index(Request $request): View
    {
        $ds = DanhMuc::withCount('sanPhams')
            ->when($request->filled('keyword'), fn ($q) => $q->where('TenDM', 'like', '%'.trim($request->query('keyword')).'%'))
            ->orderBy('MaDM')->paginate(10)->withQueryString();

        return view('admin.danhmuc.index', ['ds' => $ds]);
    }

    public function create(): View
    {
        return view('admin.danhmuc.form', ['dm' => new DanhMuc]);
    }

    public function store(DanhMucRequest $request): RedirectResponse
    {
        DanhMuc::create($request->validated() + ['MaDM' => IdGen::numeric('DanhMuc', 'MaDM', 'DM', 2)]);

        return redirect()->route('admin.danhmuc.index')->with('success', 'Đã thêm danh mục.');
    }

    public function edit(DanhMuc $danhmuc): View
    {
        return view('admin.danhmuc.form', ['dm' => $danhmuc]);
    }

    public function update(DanhMucRequest $request, DanhMuc $danhmuc): RedirectResponse
    {
        $danhmuc->update($request->validated());

        return redirect()->route('admin.danhmuc.index')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(DanhMuc $danhmuc): RedirectResponse
    {
        if ($danhmuc->sanPhams()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm.');
        }
        $danhmuc->delete();

        return redirect()->route('admin.danhmuc.index')->with('success', 'Đã xóa danh mục.');
    }
}
