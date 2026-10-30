<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PhieuNhap', function (Blueprint $table) {
            $table->char('MaPN', 5)->primary();
            $table->char('MaNV', 5)->nullable();
            $table->char('MaNCC', 5)->nullable();
            $table->dateTime('NgayNhap')->useCurrent();
            $table->decimal('TongTienNhap', 18, 2)->default(0);
            $table->string('GhiChu')->nullable();
            $table->foreign('MaNV')->references('MaNV')->on('NhanVien');
            $table->foreign('MaNCC')->references('MaNCC')->on('NhaCungCap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PhieuNhap');
    }
};
