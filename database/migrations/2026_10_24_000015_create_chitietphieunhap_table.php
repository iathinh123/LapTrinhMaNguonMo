<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ChiTietPhieuNhap', function (Blueprint $table) {
            $table->char('MaPN', 5);
            $table->char('MaSP', 5);
            $table->integer('SoLuongNhap')->nullable();
            $table->decimal('GiaNhap', 18, 2)->nullable();
            $table->primary(['MaPN', 'MaSP']);
            $table->foreign('MaPN')->references('MaPN')->on('PhieuNhap')->cascadeOnDelete();
            $table->foreign('MaSP')->references('MaSP')->on('SanPham');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ChiTietPhieuNhap');
    }
};
