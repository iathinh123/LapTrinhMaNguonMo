<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('KhachHang_KhuyenMai', function (Blueprint $table) {
            $table->char('MaKH', 5);
            $table->char('MaKM', 10);
            $table->boolean('TrangThaiSuDung')->default(false);
            $table->primary(['MaKH', 'MaKM']);
            $table->foreign('MaKH')->references('MaKH')->on('KhachHang')->cascadeOnDelete();
            $table->foreign('MaKM')->references('MaKM')->on('KhuyenMai')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('KhachHang_KhuyenMai');
    }
};
