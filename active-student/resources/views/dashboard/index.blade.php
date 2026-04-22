@extends('layouts.app')

@section('title', 'Панель управления')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt me-2"></i>Панель управления</h2>
        <span class="badge bg-secondary fs-6">
            @switch(auth()->user()->role)
                @case('admin') Администратор @break
                @case('teacher') Преподаватель @break
                @case('dean') Учебная часть @break
                @default {{ auth()->user()->role }}
            @endswitch
        </span>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><h6 class="mb-0">Студентов</h6><h2 class="mb-0">{{ $totalStudents }}</h2></div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><h6 class="mb-0">Групп</h6><h2 class="mb-0">{{ $totalGroups }}</h2></div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><h6 class="mb-0">Дисциплин</h6><h2 class="mb-0">{{ $totalDisciplines }}</h2></div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><h6 class="mb-0">Занятий сегодня</h6><h2 class="mb-0">{{ $todayLessons }}</h2></div>
                    <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @if(auth()->user()->isAdmin())
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5>Группы</h5>
                        <a href="{{ route('admin.groups.index') }}" class="btn btn-outline-primary">Управление</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-book fa-3x text-success mb-3"></i>
                        <h5>Дисциплины</h5>
                        <a href="{{ route('admin.disciplines.index') }}" class="btn btn-outline-success">Управление</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-user-cog fa-3x text-warning mb-3"></i>
                        <h5>Пользователи</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning">Управление</a>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->isTeacher())
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                        <h5>Мои занятия</h5>
                        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-primary">Перейти</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-file-import fa-3x text-success mb-3"></i>
                        <h5>Импорт из Teams</h5>
                        <a href="{{ route('teacher.import.index') }}" class="btn btn-outline-success">Перейти</a>
                    </div>
                </div>
            </div>
        @endif

        @if(!auth()->user()->isStudent())
            <div class="col-md-{{ auth()->user()->isDean() ? '12' : '4' }}">
                <div class="card h-100">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-danger mb-3"></i>
                        <h5>Отчёты по посещаемости</h5>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-danger">Перейти</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
