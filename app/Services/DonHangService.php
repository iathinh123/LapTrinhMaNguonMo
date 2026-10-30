<?php

namespace App\Services;

use App\Models\ChiTietGioHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DonHangService
{
    /**
     * Tạo đơn (thanh toán khi nhận hàng) từ danh sách dòng [MaSP => SoLuong].
     * Khóa dòng sản phẩm bằng lockForUpdate() để không bán vượt tồn kho khi nhiều người mua cùng lúc.
     *
     * @param  array<string,int>  $dong
     */
    public function tao(KhachHang $kh, array $dong, string $diaChi, string $sdt, ?string $maKm, bool $xoaGio): HoaDon
    {
        return DB::transaction(function () use ($kh, $dong, $diaChi, $sdt, $maKm, $xoaGio) {
            $tong = 0.0;
            $chiTiet = [];
            foreach ($dong as $maSp => $soLuong) {
                $sp = SanPham::lockForUpdate()->find($maSp) ?? throw $this->loi('Sản phẩm không tồn tại');
                if ($soLuong < 1) {
                    throw $this->loi('Số lượng không hợp lệ.');
                }
                if ($sp->SoLuong < $soLuong) {
                    throw $this->loi("Sản phẩm {$sp->TenSP} không đủ tồn kho!");
                }
                $chiTiet[] = [$sp, $soLuong];
                $tong += (float) $sp->Gia * $soLuong;
            }

            $km = null;
            $giam = 0.0;
            if (filled($maKm)) {
                $km = KhuyenMai::hopLe($maKm, $tong);
                $giam = $km->soTienGiam($tong);
                $tong -= $giam;
                $km->decrement('SoLuong');
            }

            $hd = HoaDon::create([
                'MaHD' => IdGen::base36('HoaDon', 'MaHD', 'HD'), 'MaKH' => $kh->MaKH, 'MaKM' => $km?->MaKM,
                'TienGiamGia' => $giam, 'NgayLap' => now(), 'TongTien' => $tong,
                'TrangThai' => HoaDon::ST_COD, 'PhuongThucThanhToan' => 'Tiền mặt',
                'DiaChiGiaoHang' => $diaChi, 'SoDienThoaiGiaoHang' => $sdt,
            ]);

            foreach ($chiTiet as [$sp, $soLuong]) {
                $hd->chiTiet()->create(['MaSP' => $sp->MaSP, 'SoLuong' => $soLuong, 'DonGia' => $sp->Gia]);
                $sp->decrement('SoLuong', $soLuong);
                $sp->refresh()->capNhatTinhTrang();
            }

            if ($xoaGio) {
                ChiTietGioHang::where('MaKH', $kh->MaKH)->delete();
            }

            return $hd;
        });
    }

    /** Hủy đơn và hoàn lại tồn kho. Chặn hủy đơn đã giao hoặc đã hủy (tránh cộng kho hai lần). */
    public function huy(string $maHd): void
    {
        DB::transaction(function () use ($maHd) {
            $hd = HoaDon::lockForUpdate()->find($maHd) ?? throw $this->loi('Không tìm thấy hóa đơn!');
            if ($hd->TrangThai === HoaDon::ST_DA_GIAO) {
                throw $this->loi('Không thể hủy đơn hàng đã giao thành công!');
            }
            if ($hd->TrangThai === HoaDon::ST_DA_HUY) {
                throw $this->loi('Đơn hàng này đã được hủy trước đó.');
            }

            foreach ($hd->chiTiet as $ct) {
                $sp = SanPham::withTrashed()->lockForUpdate()->find($ct->MaSP);
                $sp?->increment('SoLuong', $ct->SoLuong);
                $sp?->refresh()->capNhatTinhTrang();
            }
            $hd->update(['TrangThai' => HoaDon::ST_DA_HUY]);
        });
    }

    private function loi(string $msg): ValidationException
    {
        return ValidationException::withMessages(['donHang' => $msg]);
    }
}
