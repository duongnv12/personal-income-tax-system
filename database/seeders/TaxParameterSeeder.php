<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxParameter;

class TaxParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem các tham số đã tồn tại chưa để tránh trùng lặp
        if (TaxParameter::count() == 0) {
            TaxParameter::insert([
                [
                    'param_key' => 'PERSONAL_DEDUCTION',
                    'param_name' => 'personal_deduction',
                    'param_value' => 11000000,
                    'description' => 'Mức giảm trừ bản thân',
                ],
                [
                    'param_key' => 'DEPENDENT_DEDUCTION',
                    'param_name' => 'dependent_deduction',
                    'param_value' => 4400000,
                    'description' => 'Mức giảm trừ người phụ thuộc',
                ],
                [
                    'param_key' => 'SOCIAL_INSURANCE_RATE',
                    'param_name' => 'social_insurance_rate',
                    'param_value' => 0.08, // 8%
                    'description' => 'Tỷ lệ đóng bảo hiểm xã hội (người lao động)',
                ],
                [
                    'param_key' => 'HEALTH_INSURANCE_RATE',
                    'param_name' => 'health_insurance_rate',
                    'param_value' => 0.015, // 1.5%
                    'description' => 'Tỷ lệ đóng bảo hiểm y tế (người lao động)',
                ],
                [
                    'param_key' => 'UNEMPLOYMENT_INSURANCE_RATE',
                    'param_name' => 'unemployment_insurance_rate',
                    'param_value' => 0.01, // 1%
                    'description' => 'Tỷ lệ đóng bảo hiểm thất nghiệp (người lao động)',
                ],
                [
                    'param_key' => 'MAX_SOCIAL_INSURANCE',
                    'param_name' => 'max_social_insurance',
                    'param_value' => 29800000,
                    'description' => 'Mức lương tối đa tính BHXH, BHYT, BHTN',
                ],
                // Thêm các tham số khác nếu cần
            ]);
            $this->command->info('Các tham số thuế đã được tạo.');
        } else {
            $this->command->info('Các tham số thuế đã tồn tại.');
        }
    }
}