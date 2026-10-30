<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('HoaDon', function (Blueprint $table) {
            $table->char('MaHD', 5)->primary();
            $table->char('MaKH', 5)->nullable();
            $table->char('MaNV', 5)->nullable();
            $table->char('MaKM', 10)->nullable();
            $table->decimal('TienGiamGia', 18, 2)->default(0);
            $table->dateTime('NgayLap')->useCurrent();
            $table->dateTime('NgayThanhToan')->nullable();
            $table->decimal('TongTien', 18, 2)->nullable();
            $table->string('TrangThai', 50)->default('Chờ thanh toán');
            $table->string('PhuongThucThanhToan', 50)->nullable();
            $table->string('DiaChiGiaoHang')->nullable();
            $table->string('SoDienThoaiGiaoHang', 15)->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->dateTime('CreatedDate')->nullable();
            $table->string('LastModifiedBy', 50)->nullable();
            $table->dateTime('LastModifiedDate')->nullable();
            $table->foreign('MaKH')->references('MaKH')->on('KhachHang');
            $table->foreign('MaNV')->references('MaNV')->on('NhanVien');
            $table->foreign('MaKM')->references('MaKM')->on('KhuyenMai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('HoaDon');
    }
};
