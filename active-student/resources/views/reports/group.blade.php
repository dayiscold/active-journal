@extends('layouts.app')
@section('title', 'Отчёт: ' . $group->name)

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">Группа {{ $group->name }}</h1>
        <p class="text-sm text-white/40 mt-0.5">{{ $group->faculty }}</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

{{-- Date filter --}}
<div class="glass-card p-5 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-medium mb-1.5 text-white/50">С</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="input-glass w-40">
        </div>
        <div>
            <label class="block text-xs font-medium mb-1.5 text-white/50">По</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="input-glass w-40">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-filter"></i> Применить
        </button>
    </form>
</div>

<div class="flex items-center gap-3 mb-4">
    <span class="badge badge-teal">
        <i class="fas fa-calendar mr-1"></i>
        Занятий за период: {{ $lessons->count() }}
    </span>
</div>

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Студент</th>
                    <th>Занятий</th>
                    <th>Присутствовал</th>
                    <th>Прогулы</th>
                    <th>Болезнь</th>
                    <th>Посещаемость</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($stats as $row)
                <tr>
                    <td class="font-medium text-white/85">{{ $row['student']->name }}</td>
                    <td class="text-white/50">{{ $row['total'] }}</td>
                    <td><span class="badge badge-teal">{{ $row['present'] }}</span></td>
                    <td><span class="badge badge-rose">{{ $row['absent'] }}</span></td>
                    <td><span class="badge badge-sky">{{ $row['sick'] }}</span></td>
                    <td>
                        <div class="flex items-center gap-2 min-w-32">
                            <div class="progress-track flex-1">
                                <div class="progress-fill {{ $row['percentage'] >= 75 ? 'bg-teal-400' : ($row['percentage'] >= 50 ? 'bg-amber-400' : 'bg-rose-400') }}"
                                     style="width:{{ $row['percentage'] }}%"></div>
                            </div>
                            <span class="text-xs text-white/50 w-8 text-right">{{ $row['percentage'] }}%</span>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('reports.student', $row['student']) }}"
                           class="btn-secondary btn-sm">
                            Подробнее
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-white/30">
                        <i class="fas fa-chart-bar text-3xl mb-2 block"></i>
                        Нет данных за выбранный период
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection