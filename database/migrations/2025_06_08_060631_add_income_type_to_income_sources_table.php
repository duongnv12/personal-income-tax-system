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
        Schema::table('income_sources', function (Blueprint $table) {
            // Thêm cột income_type sau cột 'name'
            $table->string('income_type')->default('salary')->after('name')->comment('Loại thu nhập: salary, business, investment, other');
            // Cập nhật các bản ghi hiện có nếu cần (tùy thuộc vào dữ liệu có sẵn)
            // Hoặc có thể chạy một Artisan command sau migration để gán type cho dữ liệu cũ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_sources', function (Blueprint $table) {
            $table->dropColumn('income_type');
        });
    }
};