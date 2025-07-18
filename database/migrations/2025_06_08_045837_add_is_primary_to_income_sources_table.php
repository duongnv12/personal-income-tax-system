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
            // Thêm cột is_primary
            $table->boolean('is_primary')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_sources', function (Blueprint $table) {
            // Xóa cột is_primary nếu rollback
            $table->dropColumn('is_primary');
        });
    }
};