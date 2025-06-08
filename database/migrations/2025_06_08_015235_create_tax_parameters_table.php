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
        Schema::create('tax_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('param_key')->unique(); // Ví dụ: 'gt_ban_than', 'gt_nguoi_phu_thuoc', 'bh_tl_tong'
            $table->string('param_name'); // Tên tham số, ví dụ: 'Giảm trừ bản thân', 'Giảm trừ người phụ thuộc'
            $table->decimal('param_value', 15, 2); // Giá trị của tham số
            $table->text('description')->nullable(); // Mô tả tham số
            $table->date('effective_date')->nullable(); // Ngày có hiệu lực (để lưu trữ lịch sử thay đổi)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_parameters');
    }
};