<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('BinhLuan', function (Blueprint $table) {
            $table->char('MaBL', 5)->primary();
            $table->char('MaSP', 5)->nullable();
            $table->char('MaKH', 5)->nullable();
            $table->string('NoiDung', 500)->nullable();
            $table->unsignedTinyInteger('SoSao')->nullable();
            $table->dateTime('NgayDang')->useCurrent();
            $table->boolean('TrangThai')->default(true);
            $table->foreign('MaSP')->references('MaSP')->on('SanPham')->cascadeOnDelete();
            $table->foreign('MaKH')->references('MaKH')->on('KhachHang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('BinhLuan');
    }
};
