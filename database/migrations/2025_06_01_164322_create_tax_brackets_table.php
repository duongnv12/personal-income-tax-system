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
        Schema::create('tax_brackets', function (Blueprint $table) {
            $table->id();
            $table->integer('level'); // Bậc thuế (1, 2, 3...)
            $table->unsignedBigInteger('min_income'); // Ngưỡng thu nhập tối thiểu của bậc
            $table->unsignedBigInteger('max_income')->nullable(); // Ngưỡng thu nhập tối đa, có thể NULL cho bậc cuối
            $table->decimal('tax_rate', 5, 4); // Thuế suất (ví dụ: 0.05, 0.10)
            $table->date('effective_date')->comment('Ngày áp dụng biểu thuế'); // Rất quan trọng
            $table->timestamps();

            // Đảm bảo mỗi bậc thuế là duy nhất cho mỗi ngày áp dụng
            $table->unique(['level', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_brackets');
    }
};