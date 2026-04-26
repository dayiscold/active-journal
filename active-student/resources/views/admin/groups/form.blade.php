@extends('layouts.app')
@section('title', $group->exists ? 'Редактировать группу' : 'Новая группа')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">
        {{ $group->exists ? 'Редактировать группу' : 'Новая группа' }}
    </h1>
    <a href="{{ route('admin.groups.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

<div class="max-w-md">
    <div class="glass-card p-6">
        <form method="POST"
              action="{{ $group->exists ? route('admin.groups.update', $group) : route('admin.groups.store') }}">
            @csrf
            @if($group->exists) @method('PUT') @endif

            <div class="space-y-5">

                <div>
                    <label for="name" class="label-text">
                        Название группы <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $group->name) }}"
                           class="input-glass @error('name') error @enderror"
                           placeholder="ИС-101">
                    @error('name')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="faculty" class="label-text">
                        Факультет <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="faculty" name="faculty"
                           value="{{ old('faculty', $group->faculty) }}"
                           class="input-glass @error('faculty') error @enderror"
                           placeholder="Факультет информационных технологий">
                    @error('faculty')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="course" class="label-text">
                        Курс <span class="text-rose-400">*</span>
                    </label>
                    <select id="course" name="course"
                            class="select-glass @error('course') error @enderror">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('course', $group->course) == $i ? 'selected' : '' }}>
                                {{ $i }} курс
                            </option>
                        @endfor
                    </select>
                    @error('course')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        {{ $group->exists ? 'Сохранить изменения' : 'Создать группу' }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection