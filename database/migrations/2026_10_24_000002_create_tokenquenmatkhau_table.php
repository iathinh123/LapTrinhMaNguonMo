<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TokenQuenMatKhau', function (Blueprint $table) {
            $table->increments('MaToken');
            $table->string('TenDangNhap', 100);
            $table->string('ChuoiToken')->unique();
            $table->string('OTPCode', 6)->nullable();
            $table->string('MucDich', 10)->default('forgot');   // register | forgot: quyết định OTP này dùng để làm gì
            $table->dateTime('NgayTao')->useCurrent();
            $table->dateTime('NgayHetHan');
            $table->foreign('TenDangNhap')->references('TenDangNhap')->on('TaiKhoan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TokenQuenMatKhau');
    }
};
