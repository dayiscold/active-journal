@extends('layouts.app')

@section('title', 'Импорт из Teams')

@section('content')
    <h2 class="mb-4"><i class="fas fa-file-import me-2"></i>Импорт посещаемости из Microsoft Teams</h2>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Загрузить CSV-файл</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Загрузите CSV-отчёт о присутствии из Microsoft Teams. Формат: <code>Имя, Email, ...</code>
                        Система сопоставит email из Teams с email студентов в базе данных.
                    </div>

                    <form method="POST" action="{{ route('teacher.import.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Занятие <span class="text-danger">*</span></label>
                            <select name="lesson_id" class="form-select @error('lesson_id') is-invalid @enderror">
                                <option value="">— Выберите занятие —</option>
                                @foreach($lessons as $lesson)
                                    <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                        {{ $lesson->date->format('d.m.Y') }} | {{ $lesson->pair_number }}-я пара |
                                        {{ $lesson->group->name }} | {{ $lesson->discipline->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lesson_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CSV-файл <span class="text-danger">*</span></label>
                            <input type="file" name="csv_file" accept=".csv,.txt"
                                   class="form-control @error('csv_file') is-invalid @enderror">
                            @error('csv_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Импортировать
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">История импорта</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Файл</th>
                            <th>Статус</th>
                            <th>Записей</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            @php
                                $badge = match($log->status) {
                                    'completed'  => 'success',
                                    'processing' => 'warning',
                                    'failed'     => 'danger',
                                    default      => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td><small>{{ $log->filename }}</small></td>
                                <td><span class="badge bg-{{ $badge }}">{{ $log->status }}</span></td>
                                <td>{{ $log->records_processed }}</td>
                                <td><small>{{ $log->created_at->format('d.m.Y H:i') }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Нет истории</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
