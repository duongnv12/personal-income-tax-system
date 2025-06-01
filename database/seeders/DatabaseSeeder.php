<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Có thể tạo một người dùng mẫu (nếu chưa có)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true, // Gán user này làm admin
        ]);

        $this->call([
            SystemConfigSeeder::class,
            TaxBracketSeeder::class,
        ]);
    }
}