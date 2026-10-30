<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ChiTietHoaDon', function (Blueprint $table) {
            $table->char('MaHD', 5);
            $table->char('MaSP', 5);
            $table->integer('SoLuong')->nullable();
            $table->decimal('DonGia', 18, 2)->nullable();
            $table->primary(['MaHD', 'MaSP']);
            $table->foreign('MaHD')->references('MaHD')->on('HoaDon')->cascadeOnDelete();
            $table->foreign('MaSP')->references('MaSP')->on('SanPham');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ChiTietHoaDon');
    }
};
