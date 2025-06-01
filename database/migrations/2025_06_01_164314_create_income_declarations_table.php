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
        Schema::create('income_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng users
            $table->date('declaration_month')->comment('Tháng/kỳ khai báo (ví dụ: 2025-06-01)'); // Sử dụng date để lưu tháng/năm
            $table->unsignedBigInteger('gross_salary')->default(0)->comment('Lương Gross từ công ty/nguồn chính');
            $table->unsignedBigInteger('other_taxable_income')->default(0)->comment('Tổng các khoản thu nhập chịu thuế khác');
            $table->unsignedBigInteger('non_taxable_income')->default(0)->comment('Các khoản thu nhập được miễn thuế');
            $table->unsignedBigInteger('social_insurance_contribution')->default(0)->comment('Khoản đã đóng BHXH, BHYT, BHTN trong tháng');
            $table->unsignedBigInteger('deduction_charity')->default(0)->comment('Giảm trừ từ thiện, nhân đạo');
            $table->unsignedBigInteger('tax_deducted_at_source')->default(0)->comment('Thuế đã bị khấu trừ tại nguồn (tạm nộp)');
            $table->unsignedBigInteger('calculated_tax')->default(0)->comment('Số thuế TNCN hệ thống tính ra cho kỳ này');
            $table->unsignedBigInteger('net_salary')->default(0)->comment('Lương Net hệ thống tính ra cho kỳ này');
            $table->timestamps();

            // Đảm bảo mỗi người dùng chỉ có một khai báo cho mỗi tháng
            $table->unique(['user_id', 'declaration_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_declarations');
    }
};