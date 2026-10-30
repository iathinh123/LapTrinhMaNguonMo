<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('KhachHang', function (Blueprint $table) {
            $table->char('MaKH', 5)->primary();
            $table->string('HoTen', 100);
            $table->string('GioiTinh', 10)->nullable();
            $table->string('DiaChi')->nullable();
            $table->string('DienThoai', 15)->nullable();
            $table->string('Email', 100)->nullable()->unique();
            $table->string('TenDangNhap', 100)->nullable();
            $table->foreign('TenDangNhap')->references('TenDangNhap')->on('TaiKhoan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('KhachHang');
    }
};
