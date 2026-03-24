<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use App\Models\Discipline;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $group = Group::firstOrCreate(
            ['name' => 'ИС-101'],
            [
                'name' => 'ИС-101',
                'course' => '1',
                'faculty' => 'Информационных технологий'
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Иванов Иван Иванович',
                'password' => Hash::make('password'),
                'role' => 'teacher'
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Петров Петр Петрович',
                'password' => Hash::make('password'),
                'role' => 'student',
                'group_id' => $group->id,
                'student_id' => 'ST001'
            ]
        );

        User::updateOrCreate(
            ['email' => 'dean@example.com'],
            [
                'name' => 'Учебная часть',
                'password' => Hash::make('password'),
                'role' => 'dean'
            ]
        );

        Discipline::firstOrCreate(
            ['code' => 'ВМ-101'],
            [
                'name' => 'Высшая математика',
                'code' => 'MATH101'
            ]
        );

        Discipline::firstOrCreate(
            ['code' => 'П-15'],
            [
                'name' => 'Программирование',
                'code' => 'PROG101'
            ]
        );
    }
}
