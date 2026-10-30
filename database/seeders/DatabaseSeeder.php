<?php

namespace Database\Seeders;

use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Dữ liệu mẫu thật (tài khoản, danh mục, sản phẩm, voucher) + dữ liệu ngẫu nhiên bằng Factory để thử phân trang.
     * Chỉ muốn dữ liệu thật: php artisan db:seed --class=DuLieuMauSeeder
     */
    public function run(): void
    {
        $this->call(DuLieuMauSeeder::class);

        SanPham::factory()->count(30)->create();
        KhachHang::factory()->count(10)->create();
        HoaDon::factory()->count(15)->create();
    }
}
