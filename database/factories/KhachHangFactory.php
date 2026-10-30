<?php

namespace Database\Factories;

use App\Models\KhachHang;
use App\Support\IdGen;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<KhachHang> */
class KhachHangFactory extends Factory
{
    protected $model = KhachHang::class;
    private static ?int $seq = null;

    public function definition(): array
    {
        self::$seq ??= (int) substr(IdGen::numeric('KhachHang', 'MaKH', 'KH', 3), 2) - 1;
        $so = ++self::$seq;

        return [
            'MaKH' => 'KH' . str_pad((string) $so, 3, '0', STR_PAD_LEFT),
            'HoTen' => fake()->name(),
            'GioiTinh' => fake()->randomElement(['Nam', 'Nữ']),
            'DiaChi' => fake()->city(),
            'DienThoai' => '09' . fake()->numerify('########'),
            'Email' => 'kh' . $so . '@example.com',
            'TenDangNhap' => null,
        ];
    }
}