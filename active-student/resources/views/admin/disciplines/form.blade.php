@extends('layouts.app')
@section('title', $discipline->exists ? 'Редактировать дисциплину' : 'Новая дисциплина')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">
        {{ $discipline->exists ? 'Редактировать дисциплину' : 'Новая дисциплина' }}
    </h1>
    <a href="{{ route('admin.disciplines.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

<div class="max-w-md">
    <div class="glass-card p-6">
        <form method="POST"
              action="{{ $discipline->exists ? route('admin.disciplines.update', $discipline) : route('admin.disciplines.store') }}">
            @csrf
            @if($discipline->exists) @method('PUT') @endif

            <div class="space-y-5">

                <div>
                    <label for="name" class="label-text">
                        Название <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $discipline->name) }}"
                           class="input-glass @error('name') error @enderror"
                           placeholder="Высшая математика">
                    @error('name')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="code" class="label-text">
                        Код дисциплины <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="code" name="code"
                           value="{{ old('code', $discipline->code) }}"
                           class="input-glass font-mono @error('code') error @enderror"
                           placeholder="MATH101">
                    @error('code')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        {{ $discipline->exists ? 'Сохранить изменения' : 'Создать дисциплину' }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection