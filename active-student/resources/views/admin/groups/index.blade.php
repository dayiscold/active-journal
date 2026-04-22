@extends('layouts.app')

@section('title', 'Группы')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>Учебные группы</h2>
        <a href="{{ route('admin.groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Добавить группу
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Факультет</th>
                    <th>Курс</th>
                    <th>Студентов</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($groups as $group)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $group->name }}</strong></td>
                        <td>{{ $group->faculty }}</td>
                        <td>{{ $group->course }}</td>
                        <td><span class="badge bg-info">{{ $group->students_count }}</span></td>
                        <td>
                            <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.groups.destroy', $group) }}" class="d-inline"
                                  onsubmit="return confirm('Удалить группу {{ $group->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            <a href="{{ route('reports.group', $group) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-chart-bar"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Нет групп</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
