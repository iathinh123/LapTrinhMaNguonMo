<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ChiTietGioHang', function (Blueprint $table) {
            $table->char('MaKH', 5);
            $table->char('MaSP', 5);
            $table->integer('SoLuong')->nullable();
            $table->dateTime('NgayThem')->useCurrent();
            $table->primary(['MaKH', 'MaSP']);
            $table->foreign('MaKH')->references('MaKH')->on('KhachHang')->cascadeOnDelete();
            $table->foreign('MaSP')->references('MaSP')->on('SanPham')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ChiTietGioHang');
    }
};
