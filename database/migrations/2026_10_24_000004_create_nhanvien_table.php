<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('NhanVien', function (Blueprint $table) {
            $table->char('MaNV', 5)->primary();
            $table->string('HoTen', 100);
            $table->string('ChucVu', 50)->nullable();
            $table->string('DienThoai', 15)->nullable();
            $table->string('TenDangNhap', 100)->nullable();
            $table->foreign('TenDangNhap')->references('TenDangNhap')->on('TaiKhoan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('NhanVien');
    }
};
