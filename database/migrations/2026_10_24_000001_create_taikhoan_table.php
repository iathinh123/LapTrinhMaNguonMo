<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TaiKhoan', function (Blueprint $table) {
            $table->string('TenDangNhap', 100)->primary();
            $table->string('MatKhau')->nullable();
            $table->string('Quyen', 20);                          // ROLE_ADMIN, ROLE_STAFF, ROLE_CUSTOMER
            $table->boolean('TrangThai')->default(true);          // 1: hoạt động, 0: bị khóa
            $table->string('LoaiTaiKhoan', 50)->default('LOCAL'); // LOCAL | GOOGLE
            $table->string('ProviderId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TaiKhoan');
    }
};
