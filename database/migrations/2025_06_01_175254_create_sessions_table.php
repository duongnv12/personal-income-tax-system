<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID của session
            $table->foreignId('user_id')->nullable()->index(); // ID người dùng (nếu đăng nhập)
            $table->string('ip_address', 45)->nullable(); // Địa chỉ IP
            $table->text('user_agent')->nullable(); // User-Agent của trình duyệt
            $table->longText('payload'); // Dữ liệu session (encrypted)
            $table->integer('last_activity')->index(); // Thời gian hoạt động cuối cùng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};