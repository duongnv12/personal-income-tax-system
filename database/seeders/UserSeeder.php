<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Mật khẩu là 'password'
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}