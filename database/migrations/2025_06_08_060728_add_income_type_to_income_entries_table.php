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
        Schema::table('income_entries', function (Blueprint $table) {
            // Thêm cột income_type sau cột 'income_source_id'
            // Mặc định là 'salary' để tương thích với dữ liệu cũ
            $table->string('income_type')->default('salary')->after('income_source_id')->comment('Loại thu nhập: salary, business, investment, other');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_entries', function (Blueprint $table) {
            $table->dropColumn('income_type');
        });
    }
};