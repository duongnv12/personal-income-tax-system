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
            $table->unsignedInteger('level')->unique()->comment('Bậc thuế');
            $table->decimal('income_from', 15, 2)->comment('Thu nhập tính thuế từ');
            $table->decimal('income_to', 15, 2)->nullable()->comment('Thu nhập tính thuế đến (null nếu là bậc cuối)');
            $table->decimal('tax_rate', 5, 4)->comment('Tỷ lệ thuế (ví dụ: 0.05 cho 5%)');
            $table->timestamps();
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