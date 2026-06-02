@extends('layouts.app')
@section('title', 'Отчёты')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Отчёты по посещаемости</h1>
    <p class="text-sm text-white/40 mt-0.5">Выберите тип отчёта для просмотра статистики</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Group report --}}
    <div class="glass-card p-6" x-data>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center shrink-0">
                <i class="fas fa-users text-teal-400"></i>
            </div>
            <div>
                <h2 class="font-semibold text-white">Отчёт по группе</h2>
                <p class="text-xs text-white/40">Посещаемость всех студентов группы</p>
            </div>
        </div>
        <div>
            <label for="groupSelect" class="label-text">
                Выберите группу
            </label>
            <select id="groupSelect"
                    class="select-glass"
                    @change="if($event.target.value) window.location = $event.target.value"
                    aria-label="Группа для отчёта">
                <option value="">— Выберите группу —</option>
                @foreach($groups as $group)
                    <option value="{{ route('reports.group', $group) }}">
                        {{ $group->name }} — {{ $group->faculty }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Student report --}}
    <div class="glass-card p-6" x-data>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center shrink-0">
                <i class="fas fa-user-graduate text-violet-400"></i>
            </div>
            <div>
                <h2 class="font-semibold text-white">Отчёт по студенту</h2>
                <p class="text-xs text-white/40">Личная статистика по всем дисциплинам</p>
            </div>
        </div>
        <div>
            <label for="studentSelect" class="label-text">
                Выберите студента
            </label>
            <select id="studentSelect"
                    class="select-glass"
                    @change="if($event.target.value) window.location = $event.target.value"
                    aria-label="Студент для отчёта">
                <option value="">— Выберите студента —</option>
                @foreach($students as $student)
                    <option value="{{ route('reports.student', $student) }}">
                        {{ $student->name }} — {{ $student->group?->name ?? 'Без группы' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>

@endsection