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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id(); // ID lượt tải
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Người tải
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade'); // Tài liệu
            $table->timestamp('downloaded_at')->useCurrent(); // Ngày tải
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
