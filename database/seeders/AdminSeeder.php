<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * สร้าง Admin user ตัวอย่าง
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@coffeeshop.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $this->command->info('✅ Admin user created: admin@coffeeshop.com / password');
    }
}
