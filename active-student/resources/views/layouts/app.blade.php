<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Активный студент') - Активный студент</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f8; }
        .navbar-brand { font-weight: 700; font-size: 1.5rem; color: #4361ee !important; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
        .btn-primary { background-color: #4361ee; border-color: #4361ee; }
        .btn-primary:hover { background-color: #3f37c9; border-color: #3f37c9; }
        .footer { background: white; padding: 20px 0; margin-top: 50px; border-top: 1px solid #dee2e6; }
        .nav-link.active { color: #4361ee !important; font-weight: 600; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-graduation-cap me-2"></i>Активный студент
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('dashboard')) active @endif" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Панель
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(request()->is('admin/*')) active @endif" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>Администрирование
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.groups.index') }}"><i class="fas fa-users me-2"></i>Группы</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.disciplines.index') }}"><i class="fas fa-book me-2"></i>Дисциплины</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-user-cog me-2"></i>Пользователи</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->isTeacher())
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('teacher/lessons*')) active @endif" href="{{ route('teacher.lessons.index') }}">
                                <i class="fas fa-chalkboard-teacher me-1"></i>Мои занятия
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('teacher/import*')) active @endif" href="{{ route('teacher.import.index') }}">
                                <i class="fas fa-file-import me-1"></i>Импорт из Teams
                            </a>
                        </li>
                    @endif

                    @if(!Auth::user()->isStudent())
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('reports*')) active @endif" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar me-1"></i>Отчёты
                            </a>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->role }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Выход
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Вход</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} Активный студент. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0">Студенты: Асатулин В.А. | Геребен Я.В. | Птухин Т.И.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
