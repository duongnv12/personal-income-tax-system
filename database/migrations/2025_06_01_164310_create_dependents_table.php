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
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng users, xóa người dùng thì xóa người phụ thuộc
            $table->string('full_name');
            $table->date('dob'); // Ngày sinh người phụ thuộc
            $table->string('relationship'); // Ví dụ: 'con', 'cha', 'mẹ', 'vợ', 'chồng'
            $table->string('identification_number')->unique()->nullable(); // CCCD/CMND của người phụ thuộc, có thể null
            $table->date('registration_date')->nullable(); // Ngày đăng ký người phụ thuộc (tùy chọn)
            $table->boolean('is_disabled')->default(false); // Có bị khuyết tật/mất khả năng lao động không
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependents');
    }
};