<?php

namespace App\Services;

use App\Models\ChiTietGioHang;
use App\Models\KhachHang;
use App\Models\SanPham;
use Illuminate\Validation\ValidationException;

class GioHangService
{
    public function them(KhachHang $kh, string $maSp, int $soLuong): void
    {
        $sp = SanPham::find($maSp) ?? throw $this->loi('Sản phẩm không tồn tại');
        if (! $sp->conHang()) {
            throw $this->loi("Sản phẩm '{$sp->TenSP}' đã hết hàng!");
        }

        $dong = $this->dong($kh, $maSp);
        $dich = $soLuong + ($dong?->SoLuong ?? 0);
        if ($sp->SoLuong < $dich) {
            throw $this->loi("Không thể thêm! Trong kho chỉ còn {$sp->SoLuong} sản phẩm '{$sp->TenSP}'.");
        }

        $this->luu($kh, $maSp, $dich);
    }

    public function capNhat(KhachHang $kh, string $maSp, int $soLuong): void
    {
        $this->dong($kh, $maSp) ?? throw $this->loi('Sản phẩm không có trong giỏ hàng');
        if ($soLuong <= 0) {
            $this->xoa($kh, $maSp);

            return;
        }

        $sp = SanPham::find($maSp);
        if (! $sp || $sp->SoLuong < $soLuong) {
            throw $this->loi('Không thể cập nhật! Trong kho chỉ còn '.($sp->SoLuong ?? 0)." sản phẩm '".($sp->TenSP ?? $maSp)."'.");
        }

        $this->luu($kh, $maSp, $soLuong);
    }

    public function xoa(KhachHang $kh, string $maSp): void
    {
        ChiTietGioHang::where('MaKH', $kh->MaKH)->where('MaSP', $maSp)->delete();
    }

    private function dong(KhachHang $kh, string $maSp): ?ChiTietGioHang
    {
        return ChiTietGioHang::where('MaKH', $kh->MaKH)->where('MaSP', $maSp)->first();
    }

    // Khóa kép nên dùng updateOrInsert thay vì save().
    private function luu(KhachHang $kh, string $maSp, int $soLuong): void
    {
        ChiTietGioHang::query()->updateOrInsert(
            ['MaKH' => $kh->MaKH, 'MaSP' => $maSp],
            ['SoLuong' => $soLuong, 'NgayThem' => now()]
        );
    }

    private function loi(string $msg): ValidationException
    {
        return ValidationException::withMessages(['gioHang' => $msg]);
    }
}
