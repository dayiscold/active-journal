@extends('layouts.app')

@section('title', 'Новое занятие')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle me-2"></i>Новое занятие</h2>
        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card" style="max-width:500px">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.lessons.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Группа <span class="text-danger">*</span></label>
                    <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                        <option value="">— Выберите группу —</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->faculty }})
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Дисциплина <span class="text-danger">*</span></label>
                    <select name="discipline_id" class="form-select @error('discipline_id') is-invalid @enderror">
                        <option value="">— Выберите дисциплину —</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>
                                {{ $discipline->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('discipline_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Дата <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', date('Y-m-d')) }}">
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Номер пары <span class="text-danger">*</span></label>
                    <select name="pair_number" class="form-select @error('pair_number') is-invalid @enderror">
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('pair_number', 1) == $i ? 'selected' : '' }}>{{ $i }}-я пара</option>
                        @endfor
                    </select>
                    @error('pair_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Создать и отметить посещаемость
                </button>
            </form>
        </div>
    </div>
@endsection
