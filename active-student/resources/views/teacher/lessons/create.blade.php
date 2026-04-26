@extends('layouts.app')
@section('title', 'Новое занятие')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Новое занятие</h1>
    <a href="{{ route('teacher.lessons.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад
    </a>
</div>

<div class="max-w-lg">
    <div class="glass-card p-6">
        <form method="POST" action="{{ route('teacher.lessons.store') }}">
            @csrf

            <div class="space-y-5">

                <div>
                    <label for="group_id" class="label-text">
                        Группа <span class="text-rose-400">*</span>
                    </label>
                    <select id="group_id"
                            name="group_id"
                            class="select-glass @error('group_id') error @enderror"
                            aria-describedby="{{ $errors->has('group_id') ? 'group-error' : '' }}">
                        <option value="">— Выберите группу —</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->faculty }})
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')
                        <p id="group-error" class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discipline_id" class="label-text">
                        Дисциплина <span class="text-rose-400">*</span>
                    </label>
                    <select id="discipline_id"
                            name="discipline_id"
                            class="select-glass @error('discipline_id') error @enderror">
                        <option value="">— Выберите дисциплину —</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>
                                {{ $discipline->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('discipline_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date" class="label-text">
                            Дата <span class="text-rose-400">*</span>
                        </label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ old('date', date('Y-m-d')) }}"
                               class="input-glass @error('date') error @enderror">
                        @error('date')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pair_number" class="label-text">
                            Пара <span class="text-rose-400">*</span>
                        </label>
                        <select id="pair_number"
                                name="pair_number"
                                class="select-glass @error('pair_number') error @enderror">
                            @php
                                $pairTimes = [1=>'8:30',2=>'10:15',3=>'12:00',4=>'14:15',5=>'16:00',6=>'17:40',7=>'19:15'];
                            @endphp
                            @foreach($pairTimes as $num => $time)
                                <option value="{{ $num }}" {{ old('pair_number', 1) == $num ? 'selected' : '' }}>
                                    {{ $num }}-я · {{ $time }}
                                </option>
                            @endforeach
                        </select>
                        @error('pair_number')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full justify-center">
                        <i class="fas fa-arrow-right"></i>
                        Создать и отметить посещаемость
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection