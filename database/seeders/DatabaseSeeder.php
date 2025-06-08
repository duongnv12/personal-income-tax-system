<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use App\Models\User;
use Illuminate\Database\Seeder;
use App\Services\TaxCalculationService; // Import service



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Tắt kiểm tra khóa ngoại
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // // Nếu bạn muốn truncate bảng users (thường không cần khi dùng migrate:fresh)
        // // User::truncate(); // Có thể bỏ qua nếu bạn dùng migrate:fresh
        User::where('email', 'user@example.com')->delete();


        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'), // Mật khẩu mẫu
            'tax_code' => '0123456789', // Mã số thuế mẫu
            'address' => 'Sông Công, Thái Nguyên', // Địa chỉ mẫu
            'phone_number' => '0987654321', // Số điện thoại mẫu
        ]);

        $this->call([
            AdminUserSeeder::class, // Tạo tài khoản admin
            TaxParameterSeeder::class, // Đảm bảo TaxParameterSeeder chạy trước
            TaxBracketSeeder::class, // Đảm bảo TaxBracketSeeder chạy trước
            IncomeSourceSeeder::class,
            DependentSeeder::class, // Gọi seeder Người phụ thuộc
            IncomeEntrySeeder::class, // Gọi seeder Khoản thu nhập
        ]);

        // // REFRESH the TaxCalculationService instance
        // // This ensures the service reloads parameters and brackets from the DB AFTER seeders ran
        // app()->forgetInstance(TaxCalculationService::class);
        // app()->make(TaxCalculationService::class);

        $this->call([

        ]);

        // // Bật lại kiểm tra khóa ngoại
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}