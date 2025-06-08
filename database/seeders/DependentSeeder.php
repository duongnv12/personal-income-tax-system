<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dependent;
use Carbon\Carbon;

class DependentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một user mẫu
        $user = User::first();

        if ($user) {
            Dependent::truncate(); // Xóa dữ liệu cũ nếu có

            Dependent::create([
                'user_id' => $user->id,
                'full_name' => 'Nguyễn Thị A',
                'dob' => '1990-05-15',
                'identification_number' => '123456789012',
                'relationship' => 'Vợ',
                'gender' => 'Nữ',
                'registration_date' => Carbon::parse('2024-01-01'), // Đăng ký từ đầu năm 2024
                'deactivation_date' => null, // Chưa kết thúc
                'status' => 'active',
            ]);

            Dependent::create([
                'user_id' => $user->id,
                'full_name' => 'Nguyễn Văn B',
                'dob' => '2010-03-20',
                'identification_number' => '012345678901',
                'relationship' => 'Con',
                'gender' => 'Nam',
                'registration_date' => Carbon::parse('2024-03-01'), // Đăng ký từ tháng 3 năm 2024
                'deactivation_date' => null,
                'status' => 'active',
            ]);

            Dependent::create([
                'user_id' => $user->id,
                'full_name' => 'Nguyễn Thị C',
                'dob' => '1960-01-01',
                'identification_number' => '987654321098',
                'relationship' => 'Mẹ',
                'gender' => 'Nữ',
                'registration_date' => Carbon::parse('2024-01-01'),
                'deactivation_date' => Carbon::parse('2024-06-30'), // Kết thúc giảm trừ vào cuối tháng 6 năm 2024
                'status' => 'inactive', // Trạng thái này sẽ khiến nó không được tính dù có ngày
            ]);

            Dependent::create([
                'user_id' => $user->id,
                'full_name' => 'Nguyễn Văn D',
                'dob' => '2020-01-01',
                'identification_number' => '111222333444',
                'relationship' => 'Con',
                'gender' => 'Nam',
                'registration_date' => Carbon::parse('2023-01-01'), // Đăng ký từ năm trước
                'deactivation_date' => null,
                'status' => 'active',
            ]);
        }
    }
}