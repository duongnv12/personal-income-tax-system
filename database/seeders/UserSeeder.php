<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo hoặc cập nhật user admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Mật khẩu là 'password'
                'tax_code' => null,
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'address' => '123 Admin Street',
                'is_admin' => true,
            ]
        );

        // Tạo hoặc cập nhật user thường
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'tax_code' => '1234567890',
                'gender' => 'Female',
                'dob' => '1995-05-10',
                'address' => '456 Test Street',
                'is_admin' => false,
            ]
        );
    }
}