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
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // ID tài liệu
            $table->string('title'); // Tiêu đề
            $table->text('description')->nullable(); // Mô tả
            $table->string('file_path'); // Đường dẫn file
            $table->enum('type', ['university', 'school']); // Loại tài liệu
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade'); // Người tải
            $table->foreignId('university_id')->nullable()->constrained()->onDelete('set null'); // Trường Đại học
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('set null'); // Lớp học
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Môn học
            $table->foreignId('document_type_id')->constrained()->onDelete('cascade'); // Loại tài liệu
            $table->boolean('is_approved')->default(false); // Phê duyệt
            $table->integer('report_count')->default(0); // Số lần bị báo cáo
            $table->timestamps(); // Ngày tạo và cập nhật
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
