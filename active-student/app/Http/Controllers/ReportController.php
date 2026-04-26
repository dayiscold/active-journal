<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $groups      = Group::orderBy('faculty')->orderBy('course')->get();
        $disciplines = Discipline::orderBy('name')->get();
        $students    = User::where('role', 'student')->with('group')->orderBy('name')->get();
        return view('reports.index', compact('groups', 'disciplines', 'students'));
    }

    public function byGroup(Request $request, Group $group)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to', now()->format('Y-m-d'));

        $lessons = Lesson::where('group_id', $group->id)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->with(['discipline', 'attendances'])
            ->orderBy('date')
            ->get();

        $students = $group->students()->orderBy('name')->get();

        $stats = $students->map(function ($student) use ($lessons) {
            $total   = $lessons->count();
            $records = $lessons->flatMap->attendances->where('student_id', $student->id);
            $present = $records->whereIn('status', ['present', 'late'])->count();
            return [
                'student'    => $student,
                'total'      => $total,
                'present'    => $present,
                'absent'     => $records->where('status', 'absent')->count(),
                'sick'       => $records->where('status', 'sick')->count(),
                'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        });

        return view('reports.group', compact('group', 'stats', 'dateFrom', 'dateTo', 'lessons'));
    }

    public function byStudent(User $user)
    {
        $attendances = Attendance::where('student_id', $user->id)
            ->with(['lesson.discipline', 'lesson.teacher', 'lesson'])
            ->get()
            ->sortByDesc(fn($a) => optional($a->lesson)->date);

        $total      = $attendances->count();
        $present    = $attendances->whereIn('status', ['present', 'late'])->count();
        $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

        return view('reports.student', compact('user', 'attendances', 'total', 'present', 'percentage'));
    }
}