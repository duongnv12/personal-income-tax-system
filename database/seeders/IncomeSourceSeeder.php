<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeSource;
use App\Models\User; // Import model User

class IncomeSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        if ($user) {
            // Xóa nguồn thu nhập cũ của user này nếu có để tránh trùng lặp
            $user->incomeSources()->delete();

            IncomeSource::create([
                'user_id' => $user->id,
                'name' => 'Lương Công ty A',
                'description' => 'Thu nhập từ công việc chính',
            ]);

            IncomeSource::create([
                'user_id' => $user->id,
                'name' => 'Thu nhập làm thêm',
                'description' => 'Thu nhập từ dự án ngoài giờ',
            ]);

            IncomeSource::create([
                'user_id' => $user->id,
                'name' => 'Cho thuê nhà',
                'description' => 'Thu nhập từ việc cho thuê bất động sản',
            ]);
        }
    }
}