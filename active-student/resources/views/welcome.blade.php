@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="row min-vh-75 align-items-center">
        <div class="col-lg-6">
            <h1 class="display-3 fw-bold mb-4">Активный студент</h1>
            <p class="lead mb-4">
                Автоматизированная система учета посещаемости занятий.
                Простой и удобный инструмент для преподавателей и студентов.
            </p>

            @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Войти в систему
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fas fa-tachometer-alt me-2"></i>Перейти в панель
                </a>
            @endguest
        </div>

        <div class="col-lg-6">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="display-4 text-primary mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5>Управление группами</h5>
                            <p class="text-muted">Создание и редактирование учебных групп</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="display-4 text-success mb-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5>Учет посещаемости</h5>
                            <p class="text-muted">Отмечайте присутствие студентов на занятиях</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .min-vh-75 {
            min-height: 75vh;
        }
    </style>
@endpush
