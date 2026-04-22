@extends('layouts.app')

@section('title', $discipline->exists ? 'Редактировать дисциплину' : 'Новая дисциплина')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-book me-2"></i>
            {{ $discipline->exists ? 'Редактировать дисциплину' : 'Новая дисциплина' }}
        </h2>
        <a href="{{ route('admin.disciplines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card" style="max-width:500px">
        <div class="card-body">
            <form method="POST"
                  action="{{ $discipline->exists ? route('admin.disciplines.update', $discipline) : route('admin.disciplines.store') }}">
                @csrf
                @if($discipline->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">Название <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $discipline->name) }}" placeholder="Высшая математика">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Код <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $discipline->code) }}" placeholder="MATH101">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ $discipline->exists ? 'Сохранить' : 'Создать' }}
                </button>
            </form>
        </div>
    </div>
@endsection
