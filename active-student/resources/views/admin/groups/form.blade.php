@extends('layouts.app')

@section('title', $group->exists ? 'Редактировать группу' : 'Новая группа')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-users me-2"></i>
            {{ $group->exists ? 'Редактировать группу' : 'Новая группа' }}
        </h2>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card" style="max-width:500px">
        <div class="card-body">
            <form method="POST"
                  action="{{ $group->exists ? route('admin.groups.update', $group) : route('admin.groups.store') }}">
                @csrf
                @if($group->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">Название группы <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $group->name) }}" placeholder="ИС-101">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Факультет <span class="text-danger">*</span></label>
                    <input type="text" name="faculty" class="form-control @error('faculty') is-invalid @enderror"
                           value="{{ old('faculty', $group->faculty) }}" placeholder="Факультет информационных технологий">
                    @error('faculty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Курс <span class="text-danger">*</span></label>
                    <select name="course" class="form-select @error('course') is-invalid @enderror">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('course', $group->course) == $i ? 'selected' : '' }}>{{ $i }} курс</option>
                        @endfor
                    </select>
                    @error('course')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ $group->exists ? 'Сохранить' : 'Создать' }}
                </button>
            </form>
        </div>
    </div>
@endsection
