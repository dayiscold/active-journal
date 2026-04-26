@extends('layouts.app')
@section('title', 'Главная')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center py-16">

    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-teal-400 to-violet-500 flex items-center justify-center shadow-2xl shadow-teal-500/25 mb-8">
        <i class="fas fa-graduation-cap text-white text-3xl"></i>
    </div>

    <h1 class="text-5xl sm:text-6xl font-bold text-white mb-4 leading-tight">
        Активный<br>
        <span class="bg-gradient-to-r from-teal-400 to-violet-400 bg-clip-text text-transparent">студент</span>
    </h1>

    <p class="text-lg text-white/50 max-w-md mb-10">
        Автоматизированная система учёта посещаемости занятий для преподавателей и студентов
    </p>

    @guest
        <a href="{{ route('login') }}" class="btn-primary btn-lg">
            <i class="fas fa-sign-in-alt"></i> Войти в систему
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="btn-primary btn-lg">
            <i class="fas fa-tachometer-alt"></i> Перейти в панель
        </a>
    @endguest

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-20 w-full max-w-3xl">
        <div class="glass-card p-6 text-center">
            <div class="w-12 h-12 rounded-xl bg-teal-500/10 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-teal-400 text-lg"></i>
            </div>
            <h3 class="font-semibold text-white mb-1">Группы</h3>
            <p class="text-sm text-white/40">Управление учебными группами и студентами</p>
        </div>
        <div class="glass-card p-6 text-center">
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check-circle text-violet-400 text-lg"></i>
            </div>
            <h3 class="font-semibold text-white mb-1">Посещаемость</h3>
            <p class="text-sm text-white/40">Быстрая отметка и импорт из Teams CSV</p>
        </div>
        <div class="glass-card p-6 text-center">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-chart-bar text-amber-400 text-lg"></i>
            </div>
            <h3 class="font-semibold text-white mb-1">Отчёты</h3>
            <p class="text-sm text-white/40">Статистика по группам и студентам</p>
        </div>
    </div>

</div>
@endsection