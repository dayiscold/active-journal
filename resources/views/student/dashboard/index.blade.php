@extends('layouts.app')
@section('title', 'Моя посещаемость')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">Моя посещаемость</h1>
        <p class="text-sm text-white/40 mt-0.5">{{ auth()->user()->name }}</p>
    </div>
    @if($group)
        <span class="badge badge-teal">{{ $group->name }}</span>
    @endif
</div>

{{-- Stats --}}
<div class="@container mb-8">
    <div class="grid grid-cols-2 @xl:grid-cols-4 gap-4">
        <x-stat-card value="{{ $percentage }}%"            label="Посещаемость"         icon="fas fa-chart-line"    color="teal"   />
        <x-stat-card value="{{ $attended }} / {{ $totalLessons }}" label="Посещено занятий" icon="fas fa-calendar-check" color="violet" />
        <x-stat-card value="{{ $absent }}"                 label="Прогулов"             icon="fas fa-calendar-xmark" color="rose"   />
        <x-stat-card value="{{ $sick }}"                   label="По болезни"           icon="fas fa-kit-medical"   color="sky"    />
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

    {{-- Doughnut chart --}}
    <div class="lg:col-span-2 glass-card p-6 flex flex-col">
        <h2 class="text-sm font-semibold text-white/70 mb-4">Общая статистика</h2>
        <div class="flex-1 flex items-center justify-center">
            <canvas id="attendanceChart" class="max-h-56"></canvas>
        </div>
    </div>

    {{-- By discipline --}}
    <div class="lg:col-span-3 glass-card overflow-hidden">
        <div class="glass-card-header">
            <h2 class="text-sm font-semibold text-white/70">По дисциплинам</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Дисциплина</th>
                        <th>Занятий</th>
                        <th>Посещено</th>
                        <th>Процент</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($byDiscipline as $name => $stat)
                    <tr>
                        <td class="font-medium text-white/85">{{ $name }}</td>
                        <td>{{ $stat['total'] }}</td>
                        <td>{{ $stat['attended'] }}</td>
                        <td>
                            <div class="flex items-center gap-2 min-w-28">
                                <div class="progress-track flex-1">
                                    <div class="progress-fill {{ $stat['percentage'] >= 75 ? 'bg-teal-400' : ($stat['percentage'] >= 50 ? 'bg-amber-400' : 'bg-rose-400') }}"
                                         style="width:{{ $stat['percentage'] }}%"></div>
                                </div>
                                <span class="text-xs text-white/50 w-8 text-right">{{ $stat['percentage'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-white/30">Нет данных</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- History --}}
<div class="glass-card overflow-hidden">
    <div class="glass-card-header">
        <h2 class="text-sm font-semibold text-white/70">История посещаемости</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Дисциплина</th>
                    <th>Преподаватель</th>
                    <th>Статус</th>
                    <th>Причина</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentAttendance as $att)
                <tr>
                    <td class="whitespace-nowrap">{{ optional($att->lesson)->date?->format('d.m.Y') ?? '—' }}</td>
                    <td>{{ optional(optional($att->lesson)->discipline)->name ?? '—' }}</td>
                    <td class="text-white/50">{{ optional(optional($att->lesson)->teacher)->name ?? '—' }}</td>
                    <td><x-badge :status="$att->status" /></td>
                    <td class="text-white/50">{{ $att->reason ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-10 text-white/30">Записей пока нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart');
    if (ctx) {
        const labelColor = () => getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim();

        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Присутствовал ({{ $attended }})', 'Прогулы ({{ $absent }})', 'Болезнь ({{ $sick }})'],
                datasets: [{
                    data: [{{ $attended }}, {{ $absent }}, {{ $sick }}],
                    backgroundColor: ['rgba(20,184,166,.7)', 'rgba(244,63,94,.7)', 'rgba(56,189,248,.7)'],
                    borderColor:     ['rgba(20,184,166,1)',  'rgba(244,63,94,1)',  'rgba(56,189,248,1)'],
                    borderWidth: 1,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: labelColor(), boxWidth: 12, padding: 16 }
                    }
                }
            }
        });

        new MutationObserver(() => {
            chart.options.plugins.legend.labels.color = labelColor();
            chart.update('none');
        }).observe(document.documentElement, { attributeFilter: ['class'] });
    }
</script>
@endpush
@endsection
