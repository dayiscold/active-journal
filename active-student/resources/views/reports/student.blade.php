@extends('layouts.app')
@section('title', 'Отчёт: ' . $user->name)

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
        <p class="text-sm text-white/40 mt-0.5">
            {{ $user->group?->name ?? 'Без группы' }}
            @if($user->student_id)
                · <span class="font-mono">{{ $user->student_id }}</span>
            @endif
        </p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

{{-- Stats --}}
<div class="@container mb-8">
    <div class="grid grid-cols-2 @xl:grid-cols-4 gap-4">
        <x-stat-card value="{{ $percentage }}%"
                     label="Общая посещаемость"
                     icon="fas fa-chart-line"
                     color="{{ $percentage >= 75 ? 'teal' : ($percentage >= 50 ? 'amber' : 'rose') }}" />
        <x-stat-card value="{{ $present }}"
                     label="Присутствовал"
                     icon="fas fa-check-circle"
                     color="teal" />
        <x-stat-card value="{{ $attendances->where('status', 'absent')->count() }}"
                     label="Прогулов"
                     icon="fas fa-circle-xmark"
                     color="rose" />
        <x-stat-card value="{{ $attendances->where('status', 'sick')->count() }}"
                     label="По болезни"
                     icon="fas fa-kit-medical"
                     color="sky" />
    </div>
</div>

{{-- History --}}
<div class="glass-card overflow-hidden">
    <div class="glass-card-header">
        <h2 class="text-sm font-semibold text-white/70">История посещаемости</h2>
        <span class="badge badge-slate">{{ $attendances->count() }} записей</span>
    </div>
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Пара</th>
                    <th>Дисциплина</th>
                    <th>Преподаватель</th>
                    <th>Статус</th>
                    <th>Причина</th>
                </tr>
            </thead>
            <tbody>
            @forelse($attendances as $att)
                <tr>
                    <td class="whitespace-nowrap">{{ optional($att->lesson)->date?->format('d.m.Y') ?? '—' }}</td>
                    <td class="text-white/50">{{ optional($att->lesson)->pair_number ?? '—' }}</td>
                    <td>{{ optional(optional($att->lesson)->discipline)->name ?? '—' }}</td>
                    <td class="text-white/50">{{ optional(optional($att->lesson)->teacher)->name ?? '—' }}</td>
                    <td><x-badge :status="$att->status" /></td>
                    <td class="text-white/50">{{ $att->reason ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-white/30">Нет данных</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection