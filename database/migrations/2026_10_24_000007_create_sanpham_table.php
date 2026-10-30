<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('SanPham', function (Blueprint $table) {
            $table->char('MaSP', 5)->primary();
            $table->string('TenSP', 150);
            $table->char('MaDM', 5)->nullable();
            $table->char('MaNCC', 5)->nullable();
            $table->decimal('Gia', 18, 2);
            $table->integer('SoLuong')->default(0);
            $table->string('MoTa')->nullable();
            $table->string('HinhAnh')->nullable();
            $table->string('TinhTrang', 30)->default('Còn hàng');
            $table->integer('NguongToiThieu')->default(10);
            $table->string('CreatedBy', 50)->nullable();
            $table->dateTime('CreatedDate')->nullable();
            $table->string('LastModifiedBy', 50)->nullable();
            $table->dateTime('LastModifiedDate')->nullable();
            $table->softDeletes();
            $table->foreign('MaDM')->references('MaDM')->on('DanhMuc');
            $table->foreign('MaNCC')->references('MaNCC')->on('NhaCungCap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('SanPham');
    }
};
