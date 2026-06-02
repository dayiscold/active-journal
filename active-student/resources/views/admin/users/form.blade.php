@extends('layouts.app')
@section('title', $user->exists ? 'Редактировать пользователя' : 'Новый пользователь')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">
        {{ $user->exists ? 'Редактировать пользователя' : 'Новый пользователь' }}
    </h1>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

<div class="max-w-xl"
     x-data="{ role: '{{ old('role', $user->role ?? 'student') }}' }">
    <div class="glass-card p-6">
        <form method="POST"
              action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if($user->exists) @method('PUT') @endif

            <div class="space-y-5">

                <div>
                    <label for="name" class="label-text">
                        ФИО <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}"
                           class="input-glass @error('name') error @enderror"
                           placeholder="Иванов Иван Иванович">
                    @error('name')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="label-text">
                        Email <span class="text-rose-400">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           class="input-glass @error('email') error @enderror"
                           placeholder="user@example.com">
                    @error('email')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="label-text">
                            Пароль {{ $user->exists ? '' : '*' }}
                        </label>
                        <input type="password" id="password" name="password"
                               class="input-glass @error('password') error @enderror"
                               placeholder="{{ $user->exists ? 'Оставьте пустым' : '••••••••' }}">
                        @error('password')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="label-text">
                            Подтверждение
                        </label>
                        <input type="password" id="password_confirmation"
                               name="password_confirmation"
                               class="input-glass"
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="role" class="label-text">
                        Роль <span class="text-rose-400">*</span>
                    </label>
                    <select id="role" name="role"
                            x-model="role"
                            class="select-glass @error('role') error @enderror">
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Студент</option>
                        <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Преподаватель</option>
                        <option value="dean"    {{ old('role', $user->role) === 'dean'    ? 'selected' : '' }}>Учебная часть</option>
                        <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Администратор</option>
                    </select>
                    @error('role')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                {{-- Student-only fields --}}
                <div x-show="role === 'student'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="space-y-5 pt-1">

                    <div class="divider-h"></div>

                    <div>
                        <label for="group_id" class="label-text">
                            Группа
                        </label>
                        <select id="group_id" name="group_id"
                                class="select-glass @error('group_id') error @enderror">
                            <option value="">— Выберите группу —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}"
                                    {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ $group->faculty }}, {{ $group->course }} курс)
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="student_id" class="label-text">
                            Номер студенческого билета
                        </label>
                        <input type="text" id="student_id" name="student_id"
                               value="{{ old('student_id', $user->student_id) }}"
                               class="input-glass @error('student_id') error @enderror"
                               placeholder="ST001">
                        @error('student_id')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="pt-1">
                    <div class="divider-h mb-5"></div>
                    <label for="teams_email" class="label-text">
                        Teams Email
                    </label>
                    <input type="email" id="teams_email" name="teams_email"
                           value="{{ old('teams_email', $user->teams_email) }}"
                           class="input-glass @error('teams_email') error @enderror"
                           placeholder="user@university.edu">
                    <p class="hint-text">Email в Microsoft Teams для автоматического сопоставления при импорте CSV</p>
                    @error('teams_email')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        {{ $user->exists ? 'Сохранить изменения' : 'Создать пользователя' }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection