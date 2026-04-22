@extends('layouts.app')

@section('title', $user->exists ? 'Редактировать пользователя' : 'Новый пользователь')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-user me-2"></i>
            {{ $user->exists ? 'Редактировать пользователя' : 'Новый пользователь' }}
        </h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-body">
            <form method="POST"
                  action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
                @csrf
                @if($user->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">ФИО <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Пароль {{ $user->exists ? '' : '*' }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ $user->exists ? 'Оставьте пустым, чтобы не менять' : '' }}">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Повторите пароль</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Роль <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror"
                            onchange="toggleStudentFields()">
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Студент</option>
                        <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Преподаватель</option>
                        <option value="dean" {{ old('role', $user->role) === 'dean' ? 'selected' : '' }}>Учебная часть</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Администратор</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="studentFields">
                    <div class="mb-3">
                        <label class="form-label">Группа</label>
                        <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                            <option value="">— Выберите группу —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}"
                                    {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ $group->faculty }}, {{ $group->course }} курс)
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Номер студенческого билета</label>
                        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror"
                               value="{{ old('student_id', $user->student_id) }}" placeholder="ST001">
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teams Email</label>
                    <input type="email" name="teams_email" class="form-control @error('teams_email') is-invalid @enderror"
                           value="{{ old('teams_email', $user->teams_email) }}" placeholder="user@university.edu">
                    <div class="form-text">Email в Microsoft Teams для автоматического сопоставления при импорте</div>
                    @error('teams_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ $user->exists ? 'Сохранить' : 'Создать' }}
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleStudentFields() {
            const role = document.getElementById('roleSelect').value;
            document.getElementById('studentFields').style.display = role === 'student' ? 'block' : 'none';
        }
        toggleStudentFields();
    </script>
    @endpush
@endsection
