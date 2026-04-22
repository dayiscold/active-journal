@extends('layouts.app')

@section('title', 'Отчёт: ' . $group->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>Группа {{ $group->name }}</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">С</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">По</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Применить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Занятий за период: {{ $lessons->count() }}</strong>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Студент</th>
                    <th>Всего занятий</th>
                    <th>Присутствовал</th>
                    <th>Прогулы</th>
                    <th>Болезнь</th>
                    <th>% посещаемости</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($stats as $row)
                    <tr>
                        <td>{{ $row['student']->name }}</td>
                        <td>{{ $row['total'] }}</td>
                        <td><span class="badge bg-success">{{ $row['present'] }}</span></td>
                        <td><span class="badge bg-danger">{{ $row['absent'] }}</span></td>
                        <td><span class="badge bg-info">{{ $row['sick'] }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:10px">
                                    <div class="progress-bar bg-{{ $row['percentage'] >= 75 ? 'success' : ($row['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                         style="width:{{ $row['percentage'] }}%"></div>
                                </div>
                                <span>{{ $row['percentage'] }}%</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('reports.student', $row['student']) }}" class="btn btn-sm btn-outline-primary">
                                Подробнее
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Нет данных за выбранный период</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
