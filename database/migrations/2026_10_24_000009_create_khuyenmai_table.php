<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('KhuyenMai', function (Blueprint $table) {
            $table->char('MaKM', 10)->primary();
            $table->string('MaCode', 20)->nullable()->unique();
            $table->string('TenKM', 100)->nullable();
            $table->integer('PhanTramGiam')->default(0);
            $table->decimal('TienGiamToiDa', 18, 2)->default(0);
            $table->decimal('DonToiThieu', 18, 2)->default(0);
            $table->integer('SoLuong')->default(100);
            $table->dateTime('NgayBatDau')->nullable();
            $table->dateTime('NgayKetThuc')->nullable();
            $table->boolean('TrangThai')->default(true);
            $table->string('LoaiKM', 20)->default('SAN_CO');     // SAN_CO | NHAP_MA
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('KhuyenMai');
    }
};
