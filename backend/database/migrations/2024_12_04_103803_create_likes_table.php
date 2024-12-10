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
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // ID lượt thích
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người dùng
            $table->foreignId('document_id')->constrained()->onDelete('cascade'); // Tài liệu
            $table->boolean('is_like'); // TRUE: thích, FALSE: không thích
            $table->timestamps(); // Ngày tạo và cập nhật
            $table->unique(['user_id', 'document_id']); // Một người chỉ thích hoặc không thích 1 lần
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
