<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('DanhMuc', function (Blueprint $table) {
            $table->char('MaDM', 5)->primary();
            $table->string('TenDM', 100);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DanhMuc');
    }
};
