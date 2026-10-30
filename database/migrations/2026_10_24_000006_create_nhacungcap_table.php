<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('NhaCungCap', function (Blueprint $table) {
            $table->char('MaNCC', 5)->primary();
            $table->string('TenNCC', 100);
            $table->string('DiaChi')->nullable();
            $table->string('SoDienThoai', 20)->nullable();
            $table->string('Email', 100)->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('NhaCungCap');
    }
};
