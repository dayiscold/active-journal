@extends('layouts.app')

@section('title', 'Дисциплины')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>Дисциплины</h2>
        <a href="{{ route('admin.disciplines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Добавить дисциплину
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($disciplines as $discipline)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $discipline->name }}</strong></td>
                        <td><code>{{ $discipline->code }}</code></td>
                        <td>
                            <a href="{{ route('admin.disciplines.edit', $discipline) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.disciplines.destroy', $discipline) }}" class="d-inline"
                                  onsubmit="return confirm('Удалить дисциплину?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Нет дисциплин</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
