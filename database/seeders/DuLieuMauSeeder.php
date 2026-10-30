<?php

namespace Database\Seeders;

use App\Models\DanhMuc;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\NhaCungCap;
use App\Models\NhanVien;
use App\Models\SanPham;
use App\Models\TaiKhoan;
use Illuminate\Database\Seeder;

class DuLieuMauSeeder extends Seeder
{
    /** Dữ liệu mẫu: 3 tài khoản (mật khẩu 123456), 7 danh mục, 5 NCC, 20 sản phẩm, 2 voucher; thêm dữ liệu ngẫu nhiên bằng Factory để thử phân trang. */
    public function run(): void
    {
        $d = require __DIR__ . '/data/du-lieu-mau.php';
        $now = now();

        // Dùng insert để giữ nguyên chuỗi băm sẵn của MatKhau (cast 'hashed' sẽ băm lại nếu dùng create()).
        TaiKhoan::query()->insert($d['TaiKhoan']);
        NhanVien::query()->insert($d['NhanVien']);
        KhachHang::query()->insert($d['KhachHang']);
        DanhMuc::query()->insert($d['DanhMuc']);
        NhaCungCap::query()->insert($d['NhaCungCap']);

        SanPham::query()->insert(array_map(fn ($r) => $r + ['CreatedDate' => $now], $d['SanPham']));
        KhuyenMai::query()->insert(array_map(
            fn ($r) => $r + ['NgayBatDau' => $now, 'NgayKetThuc' => $now->copy()->addYear()],
            $d['KhuyenMai']
        ));

        // Demo cảnh báo tồn kho: SP001 còn 5, ngưỡng tối thiểu 10
        SanPham::where('MaSP', 'SP001')->update(['SoLuong' => 5, 'NguongToiThieu' => 10, 'TinhTrang' => 'Sắp hết']);

    }
}
