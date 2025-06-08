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
        Schema::create('income_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // NNT
            $table->foreignId('income_source_id')->constrained()->onDelete('cascade'); // Nguồn thu nhập

            $table->integer('year'); // Năm của khoản thu nhập
            $table->integer('month')->nullable(); // Thay đổi thành nullable. Nếu nhập theo năm, cột này sẽ null.

            // Thêm cột để đánh dấu loại nhập liệu: 'monthly' hoặc 'yearly'
            $table->enum('entry_type', ['monthly', 'yearly'])->default('monthly');

            $table->decimal('gross_income', 15, 2); // Tổng thu nhập (Gross)
            $table->decimal('net_income', 15, 2);  // Thu nhập thực nhận (Net)
            $table->decimal('tax_paid', 15, 2)->nullable(); // Thuế TNCN đã nộp (tạm tính)
            $table->decimal('bhxh_deduction', 15, 2)->nullable(); // Khoản khấu trừ BHXH
            $table->decimal('other_deductions', 15, 2)->nullable(); // Các khoản giảm trừ khác (nếu có thể nhập chi tiết hơn)
            $table->timestamps();

            // Điều chỉnh ràng buộc duy nhất để phù hợp với entry_type
            // Nếu 'monthly': user_id, income_source_id, year, month là duy nhất
            // Nếu 'yearly': user_id, income_source_id, year là duy nhất (month sẽ null)
            // Ràng buộc này sẽ phức tạp hơn khi dùng unique index đơn giản
            // Tạm thời bỏ unique index phức tạp trên migration, sẽ kiểm tra logic này ở tầng ứng dụng (Controller/Model)
            // Hoặc có thể dùng unique index có điều kiện nếu MySQL version hỗ trợ, hoặc thêm một cột 'period_id' nếu cần
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_entries');
    }
};