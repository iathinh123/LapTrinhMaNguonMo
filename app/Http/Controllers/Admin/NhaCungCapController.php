<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NhaCungCapRequest;
use App\Models\NhaCungCap;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NhaCungCapController extends Controller
{
    public function index(Request $request): View
    {
        $ds = NhaCungCap::withCount('sanPhams')
            ->when($request->filled('keyword'), fn ($q) => $q->where('TenNCC', 'like', '%'.trim($request->query('keyword')).'%'))
            ->orderBy('MaNCC')->paginate(10)->withQueryString();

        return view('admin.ncc.index', ['ds' => $ds]);
    }

    public function create(): View
    {
        return view('admin.ncc.form', ['ncc' => new NhaCungCap]);
    }

    public function store(NhaCungCapRequest $request): RedirectResponse
    {
        NhaCungCap::create($request->validated() + ['MaNCC' => IdGen::numeric('NhaCungCap', 'MaNCC', 'NCC', 2)]);

        return redirect()->route('admin.ncc.index')->with('success', 'Đã thêm nhà cung cấp.');
    }

    public function edit(NhaCungCap $ncc): View
    {
        return view('admin.ncc.form', ['ncc' => $ncc]);
    }

    public function update(NhaCungCapRequest $request, NhaCungCap $ncc): RedirectResponse
    {
        $ncc->update($request->validated());

        return redirect()->route('admin.ncc.index')->with('success', 'Đã cập nhật nhà cung cấp.');
    }

    public function destroy(NhaCungCap $ncc): RedirectResponse
    {
        if ($ncc->sanPhams()->exists()) {
            return back()->with('error', 'Không thể xóa nhà cung cấp đang có sản phẩm.');
        }
        $ncc->delete();

        return redirect()->route('admin.ncc.index')->with('success', 'Đã xóa nhà cung cấp.');
    }
}
