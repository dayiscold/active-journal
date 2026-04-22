@extends('layouts.app')

@section('title', 'Моя посещаемость')

@section('content')
    <h2 class="mb-4"><i class="fas fa-user-graduate me-2"></i>Моя посещаемость</h2>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Группа</h6>
                        <h4 class="mb-0">{{ $group?->name ?? 'Не назначена' }}</h4>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Посещаемость</h6>
                        <h4 class="mb-0">{{ $percentage }}%</h4>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Посещено</h6>
                        <h4 class="mb-0">{{ $attended }} / {{ $totalLessons }}</h4>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Пропущено</h6>
                        <h4 class="mb-0">{{ $absent + $sick }}</h4>
                    </div>
                    <i class="fas fa-calendar-times fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Общая статистика</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>По дисциплинам</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr><th>Дисциплина</th><th>Занятий</th><th>Посещено</th><th>%</th></tr>
                        </thead>
                        <tbody>
                        @forelse($byDiscipline as $name => $stat)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $stat['total'] }}</td>
                                <td>{{ $stat['attended'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <div class="progress flex-grow-1" style="height:8px">
                                            <div class="progress-bar bg-{{ $stat['percentage'] >= 75 ? 'success' : ($stat['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                                 style="width:{{ $stat['percentage'] }}%"></div>
                                        </div>
                                        <small>{{ $stat['percentage'] }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted text-center py-3">Нет данных</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>История посещаемости</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                <tr><th>Дата</th><th>Дисциплина</th><th>Преподаватель</th><th>Статус</th><th>Причина</th></tr>
                </thead>
                <tbody>
                @forelse($recentAttendance as $att)
                    @php
                        $badge = match($att->status) {
                            'present' => 'success',
                            'late'    => 'warning',
                            'absent'  => 'danger',
                            'sick'    => 'info',
                            default   => 'secondary',
                        };
                        $label = match($att->status) {
                            'present' => 'Присутствовал',
                            'late'    => 'Опоздал',
                            'absent'  => 'Отсутствовал',
                            'sick'    => 'Болезнь',
                            default   => $att->status,
                        };
                    @endphp
                    <tr>
                        <td>{{ optional($att->lesson)->date?->format('d.m.Y') ?? '—' }}</td>
                        <td>{{ optional(optional($att->lesson)->discipline)->name ?? '—' }}</td>
                        <td>{{ optional(optional($att->lesson)->teacher)->name ?? '—' }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                        <td>{{ $att->reason ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted text-center py-4">Записей нет</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('attendanceChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Посещено ({{ $attended }})', 'Прогулы ({{ $absent }})', 'Болезнь ({{ $sick }})'],
                    datasets: [{
                        data: [{{ $attended }}, {{ $absent }}, {{ $sick }}],
                        backgroundColor: ['#4cc9f0', '#f72585', '#ffd166'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        </script>
    @endpush
@endsection
