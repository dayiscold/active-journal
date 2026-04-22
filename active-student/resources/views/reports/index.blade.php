@extends('layouts.app')

@section('title', 'Отчёты')

@section('content')
    <h2 class="mb-4"><i class="fas fa-chart-bar me-2"></i>Отчёты по посещаемости</h2>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Отчёт по группе</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Статистика посещаемости всех студентов группы за выбранный период.</p>
                    <form method="GET" action="#">
                        <div class="mb-3">
                            <label class="form-label">Группа</label>
                            <select name="group_id" id="groupSelect" class="form-select" onchange="goToGroupReport(this)">
                                <option value="">— Выберите группу —</option>
                                @foreach($groups as $group)
                                    <option value="{{ route('reports.group', $group) }}">
                                        {{ $group->name }} — {{ $group->faculty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Отчёт по студенту</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Личная статистика посещаемости по всем дисциплинам.</p>
                    <div class="mb-3">
                        <label class="form-label">Студент</label>
                        <select class="form-select" onchange="goToStudentReport(this)">
                            <option value="">— Выберите студента —</option>
                            @foreach($students as $student)
                                <option value="{{ route('reports.student', $student) }}">
                                    {{ $student->name }} — {{ $student->group?->name ?? 'Без группы' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function goToGroupReport(sel) {
            if (sel.value) window.location = sel.value;
        }
        function goToStudentReport(sel) {
            if (sel.value) window.location = sel.value;
        }
    </script>
    @endpush
@endsection
