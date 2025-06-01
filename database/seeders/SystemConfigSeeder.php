<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Thêm dòng này để sử dụng Carbon

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có để tránh trùng lặp khi chạy lại seeder
        SystemConfig::truncate();

        // Cập nhật các giá trị theo quy định pháp luật hiện hành tại Việt Nam
        // (Kiểm tra lại các mốc thời gian và giá trị có thể đã thay đổi)
        $currentDate = Carbon::now();

        // Mức giảm trừ gia cảnh (áp dụng từ 01/07/2020)
        SystemConfig::create([
            'key' => 'personal_deduction_amount',
            'value' => 11000000, // 11 triệu VND/tháng
            'effective_date' => '2020-07-01'
        ]);
        SystemConfig::create([
            'key' => 'dependent_deduction_amount',
            'value' => 4400000, // 4.4 triệu VND/tháng/người
            'effective_date' => '2020-07-01'
        ]);

        // Tỷ lệ đóng bảo hiểm bắt buộc (phần người lao động đóng)
        SystemConfig::create([
            'key' => 'social_insurance_rate',
            'value' => 0.08, // 8%
            'effective_date' => '2007-01-01'
        ]);
        SystemConfig::create([
            'key' => 'health_insurance_rate',
            'value' => 0.015, // 1.5%
            'effective_date' => '2007-01-01'
        ]);
        SystemConfig::create([
            'key' => 'unemployment_insurance_rate',
            'value' => 0.01, // 1%
            'effective_date' => '2009-01-01'
        ]);

        // Mức trần lương đóng bảo hiểm xã hội, y tế, thất nghiệp (20 lần mức lương cơ sở)
        // Lưu ý: Từ 01/07/2024, mức lương cơ sở bị bãi bỏ. Mức trần sẽ dựa trên 20 lần mức lương tối thiểu vùng.
        // Cần tra cứu quy định mới nhất để cập nhật giá trị chính xác nếu áp dụng cho thời điểm sau 01/07/2024.
        // Giả sử vẫn dùng mức cũ cho ví dụ này:
        SystemConfig::create([
            'key' => 'max_social_insurance_base_salary',
            'value' => 36000000, // 20 x 1.800.000 VNĐ (Mức lương cơ sở cũ)
            'effective_date' => '2023-07-01' // Ngày áp dụng mức lương cơ sở 1.8tr
        ]);
    }
}