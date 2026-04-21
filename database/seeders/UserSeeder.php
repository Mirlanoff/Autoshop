<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем админа
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN, // 'admin'
        ]);

        // Создаем 5 обычных пользователей
        User::factory(5)->create([
            'role' => User::ROLE_USER, // 'user'
        ]);
    }
}
