<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Group;
use App\Models\Discipline;
use App\Models\Lesson;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalStudents = User::where('role', 'student')->count();
        $totalGroups = Group::count();
        $totalDisciplines = Discipline::count();
        $todayLessons = Lesson::whereDate('date', today())->count();

        return view('dashboard.index', compact(
            'user',
            'totalStudents',
            'totalGroups',
            'totalDisciplines',
            'todayLessons'
        ));
    }
}
