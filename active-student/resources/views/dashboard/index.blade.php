@extends('layouts.app')

@section('title', 'Панель управления')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Добро пожаловать, {{ auth()->user()->name }}!</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5>Студентов</h5>
                                        <h2>{{ $totalStudents ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>Групп</h5>
                                        <h2>{{ $totalGroups ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5>Дисциплин</h5>
                                        <h2>{{ $totalDisciplines ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5>Занятий сегодня</h5>
                                        <h2>{{ $todayLessons ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <h5>Ваша роль: <strong>{{ auth()->user()->role }}</strong></h5>
                            <p class="mb-0">Email: {{ auth()->user()->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-danger">Выйти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
