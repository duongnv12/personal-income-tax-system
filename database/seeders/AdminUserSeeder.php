<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem tài khoản admin đã tồn tại chưa để tránh trùng lặp
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Bạn nên đổi mật khẩu mặc định này
                'is_admin' => true,
                'remember_token' => Str::random(10),
            ]);
            $this->command->info('Tài khoản Admin đã được tạo thành công!');
        } else {
            $this->command->info('Tài khoản Admin đã tồn tại.');
        }
    }
}