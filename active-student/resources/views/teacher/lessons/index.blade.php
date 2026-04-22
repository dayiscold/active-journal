@extends('layouts.app')

@section('title', 'Мои занятия')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard-teacher me-2"></i>Мои занятия</h2>
        <a href="{{ route('teacher.lessons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Создать занятие
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Дата</th>
                    <th>Пара</th>
                    <th>Группа</th>
                    <th>Дисциплина</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->date->format('d.m.Y') }}</td>
                        <td>{{ $lesson->pair_number }}-я пара</td>
                        <td><span class="badge bg-secondary">{{ $lesson->group->name }}</span></td>
                        <td>{{ $lesson->discipline->name }}</td>
                        <td>
                            <a href="{{ route('teacher.attendance.edit', $lesson) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-check-circle me-1"></i>Посещаемость
                            </a>
                            <form method="POST" action="{{ route('teacher.lessons.destroy', $lesson) }}" class="d-inline"
                                  onsubmit="return confirm('Удалить занятие?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Нет занятий. <a href="{{ route('teacher.lessons.create') }}">Создайте первое занятие</a></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $lessons->links() }}</div>
@endsection
