<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $group = Group::firstOrCreate(
            ['name' => 'ИС-101'],
            ['course' => '1', 'faculty' => 'Информационных технологий']
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Администратор',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dean@example.com'],
            [
                'name'     => 'Учебная часть',
                'password' => Hash::make('password'),
                'role'     => 'dean',
            ]
        );

        Discipline::firstOrCreate(['code' => 'MATH101'], ['name' => 'Высшая математика']);
        Discipline::firstOrCreate(['code' => 'PROG101'], ['name' => 'Программирование']);

        $this->call(TestDataSeeder::class);
    }
}
