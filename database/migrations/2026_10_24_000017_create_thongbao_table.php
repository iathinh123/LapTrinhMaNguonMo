<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ThongBao', function (Blueprint $table) {
            $table->increments('MaTB');
            $table->string('TieuDe');
            $table->text('NoiDung')->nullable();
            $table->dateTime('NgayTao')->useCurrent();
            $table->boolean('TrangThai')->default(false);         // 0: chưa xem, 1: đã xem
            $table->char('MaKH', 5)->nullable();
            $table->string('MaSP', 50)->nullable();
            $table->foreign('MaKH')->references('MaKH')->on('KhachHang')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ThongBao');
    }
};
