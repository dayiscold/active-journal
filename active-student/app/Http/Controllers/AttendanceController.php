<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function edit(Lesson $lesson)
    {
        $attendances = Attendance::where('lesson_id', $lesson->id)
            ->get()
            ->keyBy('student_id');

        $students = $lesson->group->students()->get()->map(function ($student) use ($attendances) {
            $student->attendance = $attendances->get($student->id);
            return $student;
        });

        return view('teacher.attendance.edit', compact('lesson', 'students'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'attendance'           => 'required|array',
            'attendance.*.status'  => 'required|in:present,absent,late,sick',
            'attendance.*.reason'  => 'nullable|string|max:255',
        ]);

        foreach ($request->attendance as $studentId => $data) {
            Attendance::updateOrCreate(
                ['lesson_id' => $lesson->id, 'student_id' => (int) $studentId],
                [
                    'status' => $data['status'],
                    'reason' => $data['status'] === 'present' ? null : ($data['reason'] ?? null),
                ]
            );
        }

        return redirect()->route('teacher.lessons.index')->with('success', 'Посещаемость успешно сохранена');
    }

    public function importCsv(Request $request, Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Университетское расписание пар
        $pairStarts = [
            1 => '08:30', 2 => '10:15', 3 => '12:00',
            4 => '14:15', 5 => '16:00', 6 => '17:40', 7 => '19:15',
        ];

        $lateThreshold = null;
        if (isset($pairStarts[$lesson->pair_number])) {
            $lateThreshold = \Carbon\Carbon::parse(
                $lesson->date->format('Y-m-d') . ' ' . $pairStarts[$lesson->pair_number]
            )->addMinutes(15);
        }

        $handle = fopen($request->file('csv_file')->getPathname(), 'r');

        $rawHeader = fgetcsv($handle) ?? [];
        $emailCol    = null;
        $joinTimeCol = null;
        $durationCol = null;
        foreach ($rawHeader as $i => $h) {
            $h = mb_strtolower(trim($h));
            if ($emailCol === null && (str_contains($h, 'email') || str_contains($h, 'e-mail'))) {
                $emailCol = $i;
            }
            // Teams columns: "Join Time", "First Join", "Joined", "Время входа"
            if ($joinTimeCol === null && (
                str_contains($h, 'join') || str_contains($h, 'время вх') || $h === 'joined'
            )) {
                $joinTimeCol = $i;
            }
            // Teams columns: "Duration", "In-Meeting Duration", "Длительность"
            if ($durationCol === null && (
                str_contains($h, 'duration') || str_contains($h, 'длитель')
            )) {
                $durationCol = $i;
            }
        }

        // Collect only students of this lesson's group
        $groupStudentIds = $lesson->group->students()->pluck('id')->flip();

        // Map student_id => ['joinTime' => Carbon|null, 'duration' => int (minutes)]
        $csvStudents = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($emailCol !== null) {
                $email = trim($row[$emailCol] ?? '');
            } else {
                $email = '';
                foreach ($row as $cell) {
                    if (filter_var(trim($cell), FILTER_VALIDATE_EMAIL)) {
                        $email = trim($cell);
                        break;
                    }
                }
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $student = User::where(function ($q) use ($email) {
                $q->where('teams_email', $email)->orWhere('email', $email);
            })->where('role', 'student')->first();

            if (! $student || ! $groupStudentIds->has($student->id)) {
                continue;
            }

            // Parse join time; keep earliest per student
            $joinTime = null;
            if ($joinTimeCol !== null) {
                $raw = trim($row[$joinTimeCol] ?? '');
                if ($raw) {
                    try { $joinTime = \Carbon\Carbon::parse($raw); } catch (\Exception) {}
                }
            }

            // Parse duration; sum across multiple rows (student may rejoin)
            $minutes = null;
            if ($durationCol !== null) {
                $raw = trim($row[$durationCol] ?? '');
                if ($raw !== '') {
                    $parts = explode(':', $raw);
                    $minutes = match (count($parts)) {
                        3 => (int)$parts[0] * 60 + (int)$parts[1],   // H:MM:SS
                        2 => (int)$parts[0] * 60 + (int)$parts[1],   // MM:SS (treat as min:sec)
                        default => is_numeric($raw) ? (int)$raw : null,
                    };
                }
            }

            $sid = $student->id;
            if (! array_key_exists($sid, $csvStudents)) {
                $csvStudents[$sid] = ['joinTime' => $joinTime, 'duration' => $minutes ?? 0];
            } else {
                // Keep earliest join time
                if ($joinTime && (! $csvStudents[$sid]['joinTime'] || $joinTime->lt($csvStudents[$sid]['joinTime']))) {
                    $csvStudents[$sid]['joinTime'] = $joinTime;
                }
                // Sum durations for re-joins
                $csvStudents[$sid]['duration'] += $minutes ?? 0;
            }
        }

        fclose($handle);

        // Mark every student in the group
        $counts = ['present' => 0, 'late' => 0, 'absent' => 0];

        foreach ($lesson->group->students()->get() as $student) {
            if (array_key_exists($student->id, $csvStudents)) {
                $joinTime = $csvStudents[$student->id]['joinTime'];
                $duration = $csvStudents[$student->id]['duration'];

                $joinedLate = $lateThreshold && $joinTime && $joinTime->gt($lateThreshold);
                $shortStay  = $durationCol !== null && $duration < 75;

                $status = ($joinedLate || $shortStay) ? 'late' : 'present';
            } else {
                $status = 'absent';
            }

            Attendance::updateOrCreate(
                ['lesson_id' => $lesson->id, 'student_id' => $student->id],
                ['status' => $status, 'reason' => null]
            );
            $counts[$status]++;
        }

        return redirect()->route('teacher.attendance.edit', $lesson)
            ->with('success', "Импорт завершён. Присутствовали: {$counts['present']}, опоздали: {$counts['late']}, отсутствовали: {$counts['absent']}");
    }
}
