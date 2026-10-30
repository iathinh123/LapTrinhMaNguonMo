<?php

namespace Database\Factories;

use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<HoaDon> */
class HoaDonFactory extends Factory
{
    protected $model = HoaDon::class;
    private static int $seq = -1;

    public function definition(): array
    {
        if (self::$seq < 0) {
            $last = IdGen::base36('HoaDon', 'MaHD', 'HD');
            self::$seq = (int) base_convert(substr($last, 2), 36, 10) - 1;
        }
        $ma = 'HD' . strtoupper(str_pad(base_convert((string) ++self::$seq, 10, 36), 3, '0', STR_PAD_LEFT));
        $trangThai = fake()->randomElement(['Đã giao', 'Đã hủy', 'Chờ giao hàng (Thanh toán khi nhận hàng)', 'Đã thanh toán - Chờ giao hàng']);

        return [
            'MaHD' => $ma,
            'MaKH' => KhachHang::inRandomOrder()->value('MaKH'),
            'NgayLap' => fake()->dateTimeBetween('-60 days', 'now'),
            'TongTien' => 0,
            'TrangThai' => $trangThai,
            'PhuongThucThanhToan' => fake()->randomElement(['Tiền mặt', 'VNPAY']),
            'DiaChiGiaoHang' => fake()->address(),
            'SoDienThoaiGiaoHang' => '09' . fake()->numerify('########'),
        ];
    }

    /** Sau khi tạo hóa đơn, thêm 1-3 dòng chi tiết và cập nhật TongTien cho khớp. */
    public function configure(): static
    {
        return $this->afterCreating(function (HoaDon $hd) {
            $tong = 0;
            foreach (SanPham::inRandomOrder()->limit(fake()->numberBetween(1, 3))->get() as $sp) {
                $sl = fake()->numberBetween(1, 3);
                $hd->chiTiet()->create(['MaSP' => $sp->MaSP, 'SoLuong' => $sl, 'DonGia' => $sp->Gia]);
                $tong += $sp->Gia * $sl;
            }
            $hd->update(['TongTien' => $tong]);
        });
    }
}