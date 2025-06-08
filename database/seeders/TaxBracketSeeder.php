<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxBracket;

class TaxBracketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem các bậc thuế đã tồn tại chưa
        if (TaxBracket::count() == 0) {
            TaxBracket::insert([
                [
                    'level' => 1,
                    'income_from' => 0,
                    'income_to' => 5000000,
                    'tax_rate' => 0.05, // 5%
                ],
                [
                    'level' => 2,
                    'income_from' => 5000001,
                    'income_to' => 10000000,
                    'tax_rate' => 0.10, // 10%
                ],
                [
                    'level' => 3,
                    'income_from' => 10000001,
                    'income_to' => 18000000,
                    'tax_rate' => 0.15, // 15%
                ],
                [
                    'level' => 4,
                    'income_from' => 18000001,
                    'income_to' => 32000000,
                    'tax_rate' => 0.20, // 20%
                ],
                [
                    'level' => 5,
                    'income_from' => 32000001,
                    'income_to' => 52000000,
                    'tax_rate' => 0.25, // 25%
                ],
                [
                    'level' => 6,
                    'income_from' => 52000001,
                    'income_to' => 80000000,
                    'tax_rate' => 0.30, // 30%
                ],
                [
                    'level' => 7,
                    'income_from' => 80000001,
                    'income_to' => null,
                    'tax_rate' => 0.35, // 35%
                ],
            ]);
             $this->command->info('Các bậc thuế đã được tạo.');
        } else {
            $this->command->info('Các bậc thuế đã tồn tại.');
        }
    }
}