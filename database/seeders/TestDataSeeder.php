<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $group = Group::firstOrCreate(
            ['name' => 'ИС-101'],
            ['course' => '1', 'faculty' => 'Информационных технологий']
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Иванов Иван Иванович',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]
        );

        $math = Discipline::firstOrCreate(
            ['code' => 'MATH101'],
            ['name' => 'Высшая математика']
        );

        $prog = Discipline::firstOrCreate(
            ['code' => 'PROG101'],
            ['name' => 'Программирование']
        );

        // Привязать дисциплины к группе
        $group->disciplines()->syncWithoutDetaching([
            $math->id => ['teacher_id' => $teacher->id, 'semester' => '1'],
            $prog->id => ['teacher_id' => $teacher->id, 'semester' => '1'],
        ]);

        // 10 студентов: у большинства teams_email отличается от login email
        // ST006 — без teams_email (совпадение идёт по email)
        // ST010 — teams_email совпадает с login email
        $studentsData = [
            ['student_id' => 'ST001', 'name' => 'Петров Петр Петрович',       'email' => 'student@example.com',   'teams_email' => 'p.petrov@teams.university.ru'],
            ['student_id' => 'ST002', 'name' => 'Сидоров Алексей Николаевич', 'email' => 'sidorov@example.com',   'teams_email' => 'a.sidorov@teams.university.ru'],
            ['student_id' => 'ST003', 'name' => 'Козлова Мария Сергеевна',    'email' => 'kozlova@example.com',   'teams_email' => 'm.kozlova@teams.university.ru'],
            ['student_id' => 'ST004', 'name' => 'Новиков Дмитрий Андреевич', 'email' => 'novikov@example.com',   'teams_email' => 'd.novikov@teams.university.ru'],
            ['student_id' => 'ST005', 'name' => 'Морозова Анна Игоревна',     'email' => 'morozova@example.com',  'teams_email' => 'a.morozova@teams.university.ru'],
            ['student_id' => 'ST006', 'name' => 'Волков Игорь Павлович',      'email' => 'volkov@example.com',    'teams_email' => null],
            ['student_id' => 'ST007', 'name' => 'Соколова Елена Викторовна',  'email' => 'sokolova@example.com',  'teams_email' => 'e.sokolova@teams.university.ru'],
            ['student_id' => 'ST008', 'name' => 'Лебедев Кирилл Олегович',   'email' => 'lebedev@example.com',   'teams_email' => 'k.lebedev@teams.university.ru'],
            ['student_id' => 'ST009', 'name' => 'Зайцева Ольга Дмитриевна',  'email' => 'zaitseva@example.com',  'teams_email' => 'o.zaitseva@teams.university.ru'],
            ['student_id' => 'ST010', 'name' => 'Попов Андрей Романович',     'email' => 'popov@example.com',     'teams_email' => 'popov@example.com'],
        ];

        $students = [];
        foreach ($studentsData as $data) {
            $students[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'       => $data['name'],
                    'password'   => Hash::make('password'),
                    'role'       => 'student',
                    'group_id'   => $group->id,
                    'teams_email' => $data['teams_email'],
                    'student_id' => $data['student_id'],
                ]
            );
        }

        // 4 занятия за последние 2 недели (пары 1 и 2)
        $lessonDefs = [
            ['date' => now()->subDays(14)->toDateString(), 'pair_number' => 1],
            ['date' => now()->subDays(14)->toDateString(), 'pair_number' => 2],
            ['date' => now()->subDays(7)->toDateString(),  'pair_number' => 1],
            ['date' => now()->subDays(3)->toDateString(),  'pair_number' => 1],
        ];

        $lessons = [];
        foreach ($lessonDefs as $def) {
            $lessons[] = Lesson::firstOrCreate(
                [
                    'group_id'      => $group->id,
                    'discipline_id' => $math->id,
                    'date'          => $def['date'],
                    'pair_number'   => $def['pair_number'],
                ],
                ['teacher_id' => $teacher->id]
            );
        }

        // Проставить посещаемость вручную для 3-го урока (чтобы было что видеть в отчётах)
        $manualLesson = $lessons[2];
        foreach ($students as $i => $student) {
            $status = match (true) {
                $i < 7  => 'present',
                $i === 7 => 'absent',
                $i === 8 => 'late',
                default  => 'sick',
            };
            Attendance::updateOrCreate(
                ['lesson_id' => $manualLesson->id, 'student_id' => $student->id],
                ['status' => $status, 'reason' => null]
            );
        }

        // Сгенерировать тестовый CSV для импорта (урок $lessons[0])
        $importLesson = $lessons[0];
        $this->generateSampleCsv($importLesson, $students);

        $this->command->info('Тестовые данные созданы.');
        $this->command->info("Группа: {$group->name} (id={$group->id})");
        $this->command->info("Учитель: {$teacher->email}");
        $this->command->info('Студентов: ' . count($students));
        $this->command->info('Уроков: ' . count($lessons));
        $this->command->info("CSV для импорта: storage/app/public/teams_import_sample.csv");
        $this->command->info("  Lesson ID в CSV: {$importLesson->id}");
        $this->command->info('  В CSV 8 из 10 студентов (ST009 и ST010 — отсутствуют, проверить как «не пришли»)');
    }

    private function generateSampleCsv(Lesson $lesson, array $students): void
    {
        $dir = storage_path('app/public');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $lines   = [];
        $lines[] = 'Lesson ID,Full Name,Email,Join Time,Leave Time,Duration';

        $baseJoin = now()->subDays(14)->setTime(9, 0, 0);

        // Первые 8 студентов присутствуют; ST009 и ST010 намеренно пропущены
        foreach (array_slice($students, 0, 8) as $i => $student) {
            $email     = $student->teams_email ?? $student->email;
            $joinTime  = $baseJoin->copy()->addMinutes($i * 2)->format('Y-m-d H:i:s');
            $leaveTime = $baseJoin->copy()->addMinutes(90)->format('Y-m-d H:i:s');
            $duration  = 90 - $i * 2;
            $lines[]   = "{$lesson->id},{$student->name},{$email},{$joinTime},{$leaveTime},{$duration}";
        }

        // Строка с неизвестным email — система должна её пропустить
        $lines[] = "{$lesson->id},Неизвестный Студент,unknown@nowhere.test,{$baseJoin->format('Y-m-d H:i:s')},{$baseJoin->copy()->addMinutes(90)->format('Y-m-d H:i:s')},90";

        file_put_contents("{$dir}/teams_import_sample.csv", implode("\n", $lines));
    }
}
