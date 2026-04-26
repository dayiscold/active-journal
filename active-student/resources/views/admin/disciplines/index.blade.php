@extends('layouts.app')
@section('title', 'Дисциплины')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Дисциплины</h1>
    <a href="{{ route('admin.disciplines.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Добавить дисциплину
    </a>
</div>

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Код</th>
                    <th class="text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
            @forelse($disciplines as $discipline)
                <tr>
                    <td class="text-white/30 text-xs">{{ $loop->iteration }}</td>
                    <td class="font-medium text-white/85">{{ $discipline->name }}</td>
                    <td>
                        <code class="px-2 py-0.5 rounded-md text-xs font-mono text-teal-300"
                              style="background:rgba(20,184,166,.1);">
                            {{ $discipline->code }}
                        </code>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.disciplines.edit', $discipline) }}"
                               class="btn-secondary btn-sm btn-icon"
                               title="Редактировать"
                               aria-label="Редактировать {{ $discipline->name }}">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.disciplines.destroy', $discipline) }}"
                                  @submit.prevent="if(confirm('Удалить дисциплину?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn-danger btn-sm btn-icon"
                                        title="Удалить"
                                        aria-label="Удалить {{ $discipline->name }}">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-16">
                        <div class="flex flex-col items-center gap-2 text-white/30">
                            <i class="fas fa-book text-3xl"></i>
                            <p>Дисциплин пока нет</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection