<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();
        $group   = $student->group;

        $totalLessons = $group
            ? Lesson::where('group_id', $group->id)->count()
            : 0;

        $attendanceRecords = Attendance::where('student_id', $student->id)
            ->with(['lesson.discipline', 'lesson.teacher'])
            ->get();

        $attended   = $attendanceRecords->whereIn('status', ['present', 'late'])->count();
        $absent     = $attendanceRecords->where('status', 'absent')->count();
        $sick       = $attendanceRecords->where('status', 'sick')->count();
        $percentage = $totalLessons > 0 ? round(($attended / $totalLessons) * 100) : 0;

        $byDiscipline = $attendanceRecords
            ->groupBy(fn($a) => optional(optional($a->lesson)->discipline)->name ?? 'Не указано')
            ->map(function ($records) {
                $total    = $records->count();
                $attended = $records->whereIn('status', ['present', 'late'])->count();
                return [
                    'total'      => $total,
                    'attended'   => $attended,
                    'percentage' => $total > 0 ? round(($attended / $total) * 100) : 0,
                ];
            });

        $recentAttendance = $attendanceRecords
            ->sortByDesc(fn($a) => optional($a->lesson)->date)
            ->take(20);

        return view('student.dashboard.index', compact(
            'student', 'group', 'totalLessons', 'attended',
            'absent', 'sick', 'percentage', 'byDiscipline', 'recentAttendance'
        ));
    }
}
