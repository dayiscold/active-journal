@extends('layouts.app')

@section('title', 'Моя успеваемость')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <h2>
                <i class="fas fa-user-graduate me-2"></i>Моя успеваемость
            </h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Группа</h6>
                            <h4 class="mb-0">{{ $student->group->name ?? 'Не назначена' }}</h4>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Посещаемость</h6>
                            <h4 class="mb-0">{{ $attendancePercentage }}%</h4>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Посещено занятий</h6>
                            <h4 class="mb-0">{{ $attendedLessons }} / {{ $totalLessons }}</h4>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Пропущено</h6>
                            <h4 class="mb-0">{{ $missedLessons }}</h4>
                        </div>
                        <i class="fas fa-calendar-times fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Общая статистика</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Посещаемость по дисциплинам</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Дисциплина</th>
                                <th>Всего</th>
                                <th>Посещено</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($disciplineStats as $stat)
                                <tr>
                                    <td>{{ $stat['discipline'] }}</td>
                                    <td>{{ $stat['total'] }}</td>
                                    <td>{{ $stat['attended'] }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $stat['percentage'] }}%">
                                                {{ $stat['percentage'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>История посещаемости</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->lesson->date->format('d.m.Y') }}</td>
                                    <td>{{ $attendance->lesson->discipline->name }}</td>
                                    <td>{{ $attendance->lesson->teacher->name }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge bg-success">✅ Присутствовал</span>
                                                @break
                                            @case('late')
                                                <span class="badge bg-warning">⏰ Опоздал</span>
                                                @break
                                            @case('absent')
                                                <span class="badge bg-danger">❌ Отсутствовал</span>
                                                @break
                                            @case('sick')
                                                <span class="badge bg-info">🤒 Болезнь</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $attendance->reason ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Посещено ({{ $attendedLessons }})', 'Пропущено ({{ $missedLessons }})'],
                datasets: [{
                    data: [{{ $attendedLessons }}, {{ $missedLessons }}],
                    backgroundColor: ['#4cc9f0', '#f72585'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
