<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportController;

Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/user/theme', [ProfileController::class, 'updateTheme'])->name('user.theme');

    // Admin
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::resource('groups', GroupController::class);
        Route::resource('disciplines', DisciplineController::class);
        Route::resource('users', UserController::class);
    });

    // Teacher
    Route::prefix('teacher')->middleware('role:teacher')->name('teacher.')->group(function () {
        Route::resource('lessons', LessonController::class)->except(['show', 'edit', 'update']);
        Route::get('attendance/{lesson}', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('attendance/{lesson}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::post('attendance/{lesson}/import-csv', [AttendanceController::class, 'importCsv'])->name('attendance.importCsv');
    });

    // Student
    Route::prefix('student')->middleware('role:student')->name('student.')->group(function () {
        Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');
    });

    // Reports (admin, teacher, dean)
    Route::prefix('reports')->middleware('role:admin,teacher,dean')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/group/{group}', [ReportController::class, 'byGroup'])->name('group');
        Route::get('/student/{user}', [ReportController::class, 'byStudent'])->name('student');
    });
});
