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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id(); // ID loại tài liệu
            $table->string('name', 100); // Tên loại tài liệu
            $table->timestamps(); // Ngày tạo và cập nhật
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
