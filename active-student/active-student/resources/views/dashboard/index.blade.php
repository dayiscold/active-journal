@extends('layouts.app')
@section('title', 'Панель управления')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">Панель управления</h1>
        <p class="text-sm text-white/40 mt-0.5">
            @switch(auth()->user()->role)
                @case('admin')   Администратор @break
                @case('teacher') Преподаватель @break
                @case('dean')    Учебная часть @break
                @default         {{ auth()->user()->role }}
            @endswitch
        </p>
    </div>
</div>

{{-- Stats --}}
<div class="@container mb-8">
    <div class="grid grid-cols-2 @xl:grid-cols-4 gap-4">
        <x-stat-card value="{{ $totalStudents }}"   label="Студентов"       icon="fas fa-user-graduate" color="teal"   />
        <x-stat-card value="{{ $totalGroups }}"     label="Групп"           icon="fas fa-users"         color="violet" />
        <x-stat-card value="{{ $totalDisciplines }}" label="Дисциплин"      icon="fas fa-book"          color="amber"  />
        <x-stat-card value="{{ $todayLessons }}"    label="Занятий сегодня" icon="fas fa-calendar-day"  color="rose"   />
    </div>
</div>

{{-- Quick links --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.groups.index') }}"
       class="glass-card p-6 flex items-center gap-4 group hover:border-teal-500/25 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-teal-500/10 flex items-center justify-center shrink-0 group-hover:bg-teal-500/15 transition-colors">
            <i class="fas fa-users text-teal-400 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-white">Группы</p>
            <p class="text-xs text-white/40 mt-0.5">Управление учебными группами</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-white/20 ml-auto"></i>
    </a>
    <a href="{{ route('admin.disciplines.index') }}"
       class="glass-card p-6 flex items-center gap-4 group hover:border-violet-500/25 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center shrink-0 group-hover:bg-violet-500/15 transition-colors">
            <i class="fas fa-book text-violet-400 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-white">Дисциплины</p>
            <p class="text-xs text-white/40 mt-0.5">Управление дисциплинами</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-white/20 ml-auto"></i>
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="glass-card p-6 flex items-center gap-4 group hover:border-amber-500/25 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0 group-hover:bg-amber-500/15 transition-colors">
            <i class="fas fa-user-cog text-amber-400 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-white">Пользователи</p>
            <p class="text-xs text-white/40 mt-0.5">Управление пользователями</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-white/20 ml-auto"></i>
    </a>
    @endif

    @if(auth()->user()->isTeacher())
    <a href="{{ route('teacher.lessons.index') }}"
       class="glass-card p-6 flex items-center gap-4 group hover:border-teal-500/25 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-teal-500/10 flex items-center justify-center shrink-0 group-hover:bg-teal-500/15 transition-colors">
            <i class="fas fa-chalkboard-teacher text-teal-400 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-white">Мои занятия</p>
            <p class="text-xs text-white/40 mt-0.5">Список занятий и посещаемость</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-white/20 ml-auto"></i>
    </a>
    @endif

    @if(!auth()->user()->isStudent())
    <a href="{{ route('reports.index') }}"
       class="glass-card p-6 flex items-center gap-4 group hover:border-rose-500/25 transition-colors
              {{ auth()->user()->isDean() ? 'sm:col-span-2 lg:col-span-1' : '' }}">
        <div class="w-12 h-12 rounded-xl bg-rose-500/10 flex items-center justify-center shrink-0 group-hover:bg-rose-500/15 transition-colors">
            <i class="fas fa-chart-bar text-rose-400 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-white">Отчёты</p>
            <p class="text-xs text-white/40 mt-0.5">Статистика посещаемости</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-white/20 ml-auto"></i>
    </a>
    @endif

</div>
@endsection