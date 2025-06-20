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
            $table->json('calculation_data')->nullable()->after('other_deductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_entries', function (Blueprint $table) {
            $table->dropColumn('calculation_data');
        });
    }
};
