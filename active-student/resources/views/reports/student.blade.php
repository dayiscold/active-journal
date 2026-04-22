@extends('layouts.app')

@section('title', 'Отчёт: ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user me-2"></i>{{ $user->name }}</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h3>{{ $percentage }}%</h3>
                    <p class="mb-0">Посещаемость</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3>{{ $present }}</h3>
                    <p class="mb-0">Присутствовал</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h3>{{ $attendances->where('status', 'absent')->count() }}</h3>
                    <p class="mb-0">Прогулов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h3>{{ $attendances->where('status', 'sick')->count() }}</h3>
                    <p class="mb-0">По болезни</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>История посещаемости</strong></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
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
                        <td>{{ optional($att->lesson)->pair_number ?? '—' }}</td>
                        <td>{{ optional(optional($att->lesson)->discipline)->name ?? '—' }}</td>
                        <td>{{ optional(optional($att->lesson)->teacher)->name ?? '—' }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                        <td>{{ $att->reason ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Нет данных</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
