<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id(); // ID trường Đại học
            $table->string('name', 255)->unique(); // Tên trường
            $table->timestamps(); // Ngày tạo và cập nhật
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
