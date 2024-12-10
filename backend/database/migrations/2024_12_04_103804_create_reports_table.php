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
        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // ID báo cáo
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Người báo cáo
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade'); // Tài liệu
            $table->foreignId('report_reason_id')->constrained('report_reasons')->onDelete('cascade'); // Lý do
            $table->text('user_note')->nullable(); // Ghi chú thêm
            $table->timestamps(); // Ngày tạo và cập nhật
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
