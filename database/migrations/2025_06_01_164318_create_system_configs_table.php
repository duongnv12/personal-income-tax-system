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
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Tên của cấu hình (ví dụ: 'personal_deduction_amount')
            $table->decimal('value', 15, 4); // Giá trị của cấu hình (có thể là tiền hoặc tỷ lệ, dùng decimal cho chính xác)
            $table->date('effective_date')->comment('Ngày áp dụng cấu hình'); // Rất quan trọng cho việc thay đổi quy định
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};