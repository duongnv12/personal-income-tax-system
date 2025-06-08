<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dependent;
use App\Models\IncomeSource;
use App\Models\IncomeEntry;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ để tránh trùng lặp
        User::whereIn('email', ['admin@example.com', 'user@example.com'])->delete();

        // Tạo người dùng quản trị (Admin)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin', // Đặt vai trò là admin
        ]);

        // Tạo người dùng cá nhân (Normal User)
        $user = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user', // Đặt vai trò là user
        ]);

        // Tạo người phụ thuộc cho Nguyễn Văn A
        Dependent::create([
            'user_id' => $user->id,
            'name' => 'Nguyễn Thị B',
            'date_of_birth' => Carbon::parse('2010-05-15'),
            'relationship' => 'Con',
            'tax_code' => '8493820193',
            'deduction_start_date' => Carbon::parse('2020-01-01'),
            'deduction_end_date' => null, // Chưa kết thúc
        ]);

        Dependent::create([
            'user_id' => $user->id,
            'name' => 'Trần Văn C',
            'date_of_birth' => Carbon::parse('1965-11-20'),
            'relationship' => 'Bố',
            'tax_code' => '9283746501',
            'deduction_start_date' => Carbon::parse('2023-01-01'),
            'deduction_end_date' => null,
        ]);

        // Tạo nguồn thu nhập cho Nguyễn Văn A
        $source1 = IncomeSource::create([
            'user_id' => $user->id,
            'name' => 'Công ty ABC',
            'tax_code' => '0101234567',
            'address' => '123 Đường XYZ, Hà Nội',
        ]);

        $source2 = IncomeSource::create([
            'user_id' => $user->id,
            'name' => 'Công ty DEF',
            'tax_code' => '0107654321',
            'address' => '456 Đường QWE, TP.HCM',
        ]);

        // Tạo các khoản thu nhập mẫu cho Nguyễn Văn A
        // Nhập theo tháng
        IncomeEntry::create([
            'user_id' => $user->id,
            'income_source_id' => $source1->id,
            'year' => 2024,
            'month' => 1,
            'entry_type' => 'monthly',
            'gross_income' => 25000000.00,
            'net_income' => 20000000.00, // Giá trị ước tính, sẽ được tính lại bằng thuật toán
            'tax_paid' => 1000000.00, // Giá trị ước tính
            'bhxh_deduction' => 2625000.00, // Giá trị ước tính (25M * 10.5%)
            'other_deductions' => 0.00,
        ]);

        IncomeEntry::create([
            'user_id' => $user->id,
            'income_source_id' => $source1->id,
            'year' => 2024,
            'month' => 2,
            'entry_type' => 'monthly',
            'gross_income' => 25000000.00,
            'net_income' => 20000000.00,
            'tax_paid' => 1000000.00,
            'bhxh_deduction' => 2625000.00,
            'other_deductions' => 0.00,
        ]);

        // Ví dụ nhập theo năm (lương ổn định)
        IncomeEntry::create([
            'user_id' => $user->id,
            'income_source_id' => $source2->id,
            'year' => 2024,
            'month' => null, // Để null khi nhập theo năm
            'entry_type' => 'yearly',
            'gross_income' => 15000000.00, // Đây là lương GROSS/tháng, sẽ được tính thành 15M*12 khi quyết toán
            'net_income' => 13500000.00, // Giá trị ước tính/tháng
            'tax_paid' => null, // Thường sẽ không có thuế tạm tính nếu nhập theo năm
            'bhxh_deduction' => 1575000.00, // Giá trị ước tính/tháng (15M * 10.5%)
            'other_deductions' => 0.00,
        ]);
    }
}