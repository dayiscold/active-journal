<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function edit(Lesson $lesson)
    {
        $students = $lesson->group->students()->get()->map(function ($student) use ($lesson) {
            $student->attendance = Attendance::where('lesson_id', $lesson->id)
                ->where('student_id', $student->id)
                ->first();
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
}
