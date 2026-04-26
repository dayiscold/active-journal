@extends('layouts.app')
@section('title', 'Учебные группы')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Учебные группы</h1>
    <a href="{{ route('admin.groups.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Добавить группу
    </a>
</div>

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Факультет</th>
                    <th>Курс</th>
                    <th>Студентов</th>
                    <th class="text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
            @forelse($groups as $group)
                <tr>
                    <td class="text-white/30 text-xs">{{ $loop->iteration }}</td>
                    <td class="font-semibold text-white/90">{{ $group->name }}</td>
                    <td class="text-white/55 max-w-48 truncate">{{ $group->faculty }}</td>
                    <td>
                        <span class="badge badge-violet">{{ $group->course }} курс</span>
                    </td>
                    <td>
                        <span class="badge badge-teal">{{ $group->students_count }}</span>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.groups.edit', $group) }}"
                               class="btn-secondary btn-sm btn-icon"
                               title="Редактировать"
                               aria-label="Редактировать {{ $group->name }}">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <a href="{{ route('reports.group', $group) }}"
                               class="btn-amber btn-sm btn-icon"
                               title="Отчёт"
                               aria-label="Отчёт по группе {{ $group->name }}">
                                <i class="fas fa-chart-bar text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.groups.destroy', $group) }}"
                                  @submit.prevent="if(confirm('Удалить группу {{ addslashes($group->name) }}?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn-danger btn-sm btn-icon"
                                        title="Удалить"
                                        aria-label="Удалить {{ $group->name }}">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-16">
                        <div class="flex flex-col items-center gap-2 text-white/30">
                            <i class="fas fa-users text-3xl"></i>
                            <p>Групп пока нет</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection