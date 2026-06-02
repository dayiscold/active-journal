@extends('layouts.app')
@section('title', 'Пользователи')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Пользователи</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Добавить
    </a>
</div>

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Группа</th>
                    <th>Студ. билет</th>
                    <th class="text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="text-white/30 text-xs">{{ $loop->iteration }}</td>
                    <td class="font-medium text-white/85">{{ $user->name }}</td>
                    <td class="text-white/50 text-xs">{{ $user->email }}</td>
                    <td><x-badge :role="$user->role" /></td>
                    <td class="text-white/50">{{ $user->group?->name ?? '—' }}</td>
                    <td class="font-mono text-xs text-white/40">{{ $user->student_id ?? '—' }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn-secondary btn-sm btn-icon"
                               title="Редактировать"
                               aria-label="Редактировать {{ $user->name }}">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            @if($user->role === 'student')
                            <a href="{{ route('reports.student', $user) }}"
                               class="btn-amber btn-sm btn-icon"
                               title="Отчёт"
                               aria-label="Отчёт {{ $user->name }}">
                                <i class="fas fa-chart-bar text-xs"></i>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  @submit.prevent="if(confirm('Удалить пользователя {{ addslashes($user->name) }}?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn-danger btn-sm btn-icon"
                                        title="Удалить"
                                        aria-label="Удалить {{ $user->name }}">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-16">
                        <div class="flex flex-col items-center gap-2 text-white/30">
                            <i class="fas fa-users text-3xl"></i>
                            <p>Пользователей пока нет</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection