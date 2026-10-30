<?php

namespace App\Http\Controllers;

use App\Models\BinhLuan;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BinhLuanController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $d = $request->validate([
            'maSp' => ['required', 'exists:SanPham,MaSP'],
            'soSao' => ['required', 'integer', 'between:1,5'],
            'noiDung' => ['required', 'string', 'max:500'],
        ], [
            'noiDung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noiDung.max' => 'Nội dung tối đa 500 ký tự.',
            'soSao.between' => 'Số sao phải từ 1 đến 5.',
        ]);
        $kh = $request->user()->khachHang ?? abort(403, 'Không tìm thấy hồ sơ khách hàng.');

        BinhLuan::create([
            'MaBL' => IdGen::numeric('BinhLuan', 'MaBL', 'BL', 3), 'MaSP' => $d['maSp'], 'MaKH' => $kh->MaKH,
            'SoSao' => $d['soSao'], 'NoiDung' => $d['noiDung'], 'NgayDang' => now(), 'TrangThai' => true,
        ]);

        return redirect()->route('product.show', $d['maSp'])->with('success', 'Đánh giá của bạn đã được gửi!');
    }

    /** Chủ bình luận hoặc admin/nhân viên được xóa. */
    public function destroy(Request $request, BinhLuan $binhLuan): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->laQuanTri() || $user->khachHang?->MaKH === $binhLuan->MaKH, 403, 'Bạn không có quyền xóa đánh giá này.');

        $binhLuan->delete();

        return redirect()->route('product.show', $binhLuan->MaSP)->with('success', 'Đã xóa đánh giá.');
    }
}
