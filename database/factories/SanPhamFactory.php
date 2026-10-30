<?php

namespace Database\Factories;

use App\Models\DanhMuc;
use App\Models\NhaCungCap;
use App\Models\SanPham;
use App\Support\IdGen;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SanPham> */
class SanPhamFactory extends Factory
{
    protected $model = SanPham::class;
    private static ?int $seq = null;

    public function definition(): array
    {
        // Factory dựng cả lô trước khi lưu nên phải tự đếm; lấy mã lớn nhất hiện có làm điểm bắt đầu.
        self::$seq ??= (int) substr(IdGen::numeric('SanPham', 'MaSP', 'SP', 3), 2) - 1;
        $so = ++self::$seq;
        $loai = fake()->randomElement(['Ốp lưng', 'Tai nghe', 'Sạc dự phòng', 'Cáp sạc', 'Kính cường lực', 'Chuột', 'Bàn phím']);
        $sl = fake()->numberBetween(0, 80);

        return [
            'MaSP' => 'SP' . str_pad((string) $so, 3, '0', STR_PAD_LEFT),
            'TenSP' => $loai . ' ' . fake()->words(2, true) . ' ' . $so,
            'MaDM' => DanhMuc::inRandomOrder()->value('MaDM'),
            'MaNCC' => NhaCungCap::inRandomOrder()->value('MaNCC'),
            'Gia' => fake()->numberBetween(50, 2000) * 1000,
            'SoLuong' => $sl,
            'MoTa' => fake()->sentence(8),
            'HinhAnh' => null,
            'TinhTrang' => $sl === 0 ? 'Hết hàng' : 'Còn hàng',
            'NguongToiThieu' => 10,
            'CreatedBy' => 'FACTORY',
            'CreatedDate' => now(),
        ];
    }
}