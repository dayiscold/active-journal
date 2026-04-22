<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Group;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with(['group', 'discipline'])
            ->where('teacher_id', Auth::id())
            ->orderByDesc('date')
            ->orderBy('pair_number')
            ->paginate(20);
        return view('teacher.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();
        $disciplines = Discipline::orderBy('name')->get();
        return view('teacher.lessons.create', compact('groups', 'disciplines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id'      => 'required|exists:groups,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'date'          => 'required|date',
            'pair_number'   => 'required|integer|min:1|max:8',
        ]);

        $lesson = Lesson::firstOrCreate(
            [
                'group_id'      => $request->group_id,
                'discipline_id' => $request->discipline_id,
                'date'          => $request->date,
                'pair_number'   => $request->pair_number,
            ],
            ['teacher_id' => Auth::id()]
        );

        return redirect()->route('teacher.attendance.edit', $lesson)
            ->with('success', 'Занятие создано. Отметьте посещаемость.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('teacher.lessons.index')->with('success', 'Занятие удалено');
    }
}
