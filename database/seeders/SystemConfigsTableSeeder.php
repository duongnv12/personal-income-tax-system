<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Đảm bảo đã import
use Carbon\Carbon; // Đảm bảo đã import

class SystemConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả dữ liệu hiện có trong bảng system_configs
        DB::table('system_configs')->truncate();

        // Thêm lại dữ liệu cấu hình mặc định
        $configs = [
            [
                'key' => 'personal_deduction_amount',
                'value' => 11000000,
                'description' => 'Mức giảm trừ bản thân hàng tháng',
                'effective_date' => Carbon::parse('2020-07-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'dependent_deduction_amount',
                'value' => 4400000,
                'description' => 'Mức giảm trừ người phụ thuộc hàng tháng',
                'effective_date' => Carbon::parse('2020-07-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'social_insurance_rate',
                'value' => 0.08, // 8%
                'description' => 'Tỷ lệ đóng BHXH (phần người lao động)',
                'effective_date' => Carbon::parse('2007-01-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'health_insurance_rate',
                'value' => 0.015, // 1.5%
                'description' => 'Tỷ lệ đóng BHYT (phần người lao động)',
                'effective_date' => Carbon::parse('2007-01-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'unemployment_insurance_rate',
                'value' => 0.01, // 1%
                'description' => 'Tỷ lệ đóng BHTN (phần người lao động)',
                'effective_date' => Carbon::parse('2009-01-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'max_social_insurance_base_salary',
                'value' => 36000000,
                'description' => 'Mức trần lương đóng BHXH (20 lần lương tối thiểu vùng)',
                'effective_date' => Carbon::parse('2023-07-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Thêm các cấu hình mặc định khác nếu có
        ];

        DB::table('system_configs')->insert($configs);
    }
}