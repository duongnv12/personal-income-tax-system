<?php

namespace Database\Seeders;

use App\Models\TaxBracket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Thêm dòng này để sử dụng Carbon

class TaxBracketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có
        TaxBracket::truncate();

        // Biểu thuế lũy tiến từng phần (áp dụng từ 01/01/2009 cho đến nay)
        $effectiveDate = '2009-01-01'; // Ngày có hiệu lực của biểu thuế

        TaxBracket::create([
            'level' => 1,
            'min_income' => 0,
            'max_income' => 5000000,
            'tax_rate' => 0.05, // 5%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 2,
            'min_income' => 5000001,
            'max_income' => 10000000,
            'tax_rate' => 0.10, // 10%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 3,
            'min_income' => 10000001,
            'max_income' => 18000000,
            'tax_rate' => 0.15, // 15%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 4,
            'min_income' => 18000001,
            'max_income' => 32000000,
            'tax_rate' => 0.20, // 20%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 5,
            'min_income' => 32000001,
            'max_income' => 52000000,
            'tax_rate' => 0.25, // 25%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 6,
            'min_income' => 52000001,
            'max_income' => 80000000,
            'tax_rate' => 0.30, // 30%
            'effective_date' => $effectiveDate
        ]);
        TaxBracket::create([
            'level' => 7,
            'min_income' => 80000001,
            'max_income' => null, // Không có mức tối đa
            'tax_rate' => 0.35, // 35%
            'effective_date' => $effectiveDate
        ]);
    }
}