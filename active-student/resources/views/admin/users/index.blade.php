@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-cog me-2"></i>Пользователи</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Добавить пользователя
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Группа</th>
                    <th>Номер студ. билета</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $badge = match($user->role) {
                                    'admin'   => 'danger',
                                    'teacher' => 'warning',
                                    'student' => 'info',
                                    'dean'    => 'secondary',
                                    default   => 'light',
                                };
                                $label = match($user->role) {
                                    'admin'   => 'Администратор',
                                    'teacher' => 'Преподаватель',
                                    'student' => 'Студент',
                                    'dean'    => 'Учебная часть',
                                    default   => $user->role,
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                        </td>
                        <td>{{ $user->group?->name ?? '—' }}</td>
                        <td>{{ $user->student_id ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Удалить пользователя {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @if($user->role === 'student')
                                <a href="{{ route('reports.student', $user) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Нет пользователей</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
