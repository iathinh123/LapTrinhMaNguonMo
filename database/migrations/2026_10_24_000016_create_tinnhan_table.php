<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TinNhan', function (Blueprint $table) {
            $table->increments('MaTN');
            $table->string('NguoiGui', 100)->nullable()->index();
            $table->string('LoaiNguoiGui', 20)->nullable();       // CUSTOMER | ADMIN
            $table->string('NguoiNhan', 100)->nullable()->index();
            $table->text('NoiDung')->nullable();
            $table->dateTime('ThoiGian')->useCurrent();
            $table->boolean('DaDoc')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TinNhan');
    }
};
