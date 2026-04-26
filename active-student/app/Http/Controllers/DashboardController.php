<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            return app(StudentController::class)->dashboard();
        }

        $totalStudents    = User::where('role', 'student')->count();
        $totalGroups      = Group::count();
        $totalDisciplines = Discipline::count();
        $todayLessons     = Lesson::whereDate('date', today())->count();

        return view('dashboard.index', compact(
            'user', 'totalStudents', 'totalGroups', 'totalDisciplines', 'todayLessons'
        ));
    }
}
