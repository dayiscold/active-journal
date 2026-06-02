@extends('layouts.app')
@section('title', 'Мои занятия')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Мои занятия</h1>
    <a href="{{ route('teacher.lessons.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Создать занятие
    </a>
</div>

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Пара</th>
                    <th>Группа</th>
                    <th>Дисциплина</th>
                    <th class="text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
            @forelse($lessons as $lesson)
                <tr>
                    <td class="whitespace-nowrap font-medium text-white/85">
                        {{ $lesson->date->format('d.m.Y') }}
                    </td>
                    <td class="whitespace-nowrap text-white/50">
                        {{ $lesson->pair_number }}-я пара
                    </td>
                    <td>
                        <span class="badge badge-violet">{{ $lesson->group->name }}</span>
                    </td>
                    <td class="max-w-48 truncate">{{ $lesson->discipline->name }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('teacher.attendance.edit', $lesson) }}"
                               class="btn-primary btn-sm"
                               title="Отметить посещаемость">
                                <i class="fas fa-check-circle"></i>
                                <span class="hidden sm:inline">Посещаемость</span>
                            </a>
                            <form method="POST" action="{{ route('teacher.lessons.destroy', $lesson) }}"
                                  @submit.prevent="if(confirm('Удалить занятие?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm btn-icon" title="Удалить" aria-label="Удалить занятие">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-16">
                        <div class="flex flex-col items-center gap-3 text-white/30">
                            <i class="fas fa-chalkboard-teacher text-4xl"></i>
                            <p>Занятий пока нет</p>
                            <a href="{{ route('teacher.lessons.create') }}" class="btn-primary btn-sm mt-1">
                                <i class="fas fa-plus"></i> Создать первое занятие
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $lessons->links() }}</div>

@endsection