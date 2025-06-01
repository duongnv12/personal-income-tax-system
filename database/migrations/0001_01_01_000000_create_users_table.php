<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // Thêm các trường thông tin cá nhân
            $table->string('tax_code')->unique()->nullable(); // Mã số thuế, có thể null ban đầu
            $table->date('dob')->nullable(); // Ngày sinh
            $table->string('gender')->nullable(); // Giới tính (Nam/Nữ/Khác)
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_admin')->default(false); // Đánh dấu admin
            // End thêm
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};