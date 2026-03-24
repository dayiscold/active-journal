@extends('layouts.app')

@section('title', 'Отметка посещаемости')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-check-circle me-2"></i>Отметка посещаемости
        </h2>
        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                {{ $lesson->discipline->name }} |
                Группа {{ $lesson->group->name }} |
                {{ $lesson->date->format('d.m.Y') }} |
                {{ $lesson->pair_number }}-я пара
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.attendance.update', $lesson) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Статус</th>
                            <th>Причина (если отсутствует)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    {{ $student->name }}
                                    <br>
                                    <small class="text-muted">{{ $student->student_id }}</small>
                                </td>
                                <td>
                                    <select name="attendance[{{ $student->id }}][status]" class="form-select status-select" data-student="{{ $student->id }}">
                                        <option value="present" {{ $student->attendance && $student->attendance->status == 'present' ? 'selected' : '' }}>
                                            ✅ Присутствовал
                                        </option>
                                        <option value="late" {{ $student->attendance && $student->attendance->status == 'late' ? 'selected' : '' }}>
                                            ⏰ Опоздал
                                        </option>
                                        <option value="absent" {{ $student->attendance && $student->attendance->status == 'absent' ? 'selected' : '' }}>
                                            ❌ Отсутствовал
                                        </option>
                                        <option value="sick" {{ $student->attendance && $student->attendance->status == 'sick' ? 'selected' : '' }}>
                                            🤒 Болезнь
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text"
                                           name="attendance[{{ $student->id }}][reason]"
                                           class="form-control reason-input"
                                           data-student="{{ $student->id }}"
                                           value="{{ $student->attendance && $student->attendance->status != 'present' ? $student->attendance->reason : '' }}"
                                           placeholder="Причина отсутствия">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Сохранить посещаемость
                    </button>
                    <button type="button" class="btn btn-success btn-lg" onclick="markAll('present')">
                        <i class="fas fa-check-double me-2"></i>Все присутствуют
                    </button>
                    <button type="button" class="btn btn-warning btn-lg" onclick="markAll('late')">
                        <i class="fas fa-clock me-2"></i>Все опоздали
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Показывать/скрывать поле причины в зависимости от статуса
            document.querySelectorAll('.status-select').forEach(select => {
                const studentId = select.dataset.student;
                const reasonInput = document.querySelector(`.reason-input[data-student="${studentId}"]`);

                function toggleReason() {
                    if (select.value === 'present') {
                        reasonInput.disabled = true;
                        reasonInput.value = '';
                        reasonInput.placeholder = 'Не требуется';
                    } else {
                        reasonInput.disabled = false;
                        reasonInput.placeholder = 'Причина отсутствия';
                    }
                }

                select.addEventListener('change', toggleReason);
                toggleReason();
            });

            // Функция для отметки всех студентов
            function markAll(status) {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = status;
                    const event = new Event('change');
                    select.dispatchEvent(event);
                });
            }
        </script>
    @endpush
