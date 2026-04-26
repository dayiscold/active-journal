<!DOCTYPE html>
<html lang="ru" class="{{ auth()->user()?->theme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Активный студент') — Активный студент</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body x-data="{ mobileOpen: false }" class="min-h-screen">

    {{-- ambient glow --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] rounded-full blur-[160px]" style="background:var(--glow-a);"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] rounded-full blur-[160px]" style="background:var(--glow-b);"></div>
    </div>

    {{-- ─── Navbar ──────────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-50 border-b transition-colors"
            style="background:var(--nav-bg);backdrop-filter:blur(24px);border-color:var(--border);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group" aria-label="Активный студент — главная">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-violet-500 flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:shadow-teal-500/35 transition-shadow">
                        <i class="fas fa-graduation-cap text-white text-xs"></i>
                    </div>
                    <span class="hidden sm:block font-semibold text-sm" style="color:var(--text);">Активный студент</span>
                </a>

                {{-- Desktop nav --}}
                @auth
                <nav class="hidden md:flex items-center gap-0.5" aria-label="Основная навигация">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                       aria-current="{{ request()->is('dashboard') ? 'page' : 'false' }}">
                        <i class="fas fa-tachometer-alt text-xs"></i> Панель
                    </a>

                    @if(Auth::user()->isAdmin())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @keydown.escape="open = false"
                                :aria-expanded="open"
                                class="nav-link {{ request()->is('admin/*') ? 'active' : '' }}">
                            <i class="fas fa-cog text-xs"></i>
                            Администрирование
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute top-full left-0 mt-1.5 w-52 rounded-xl border py-1 shadow-xl"
                             style="background:var(--dropdown-bg);backdrop-filter:blur(20px);border-color:var(--border);">
                            <a href="{{ route('admin.groups.index') }}" class="dropdown-item">
                                <i class="fas fa-users w-4 icon-subtle"></i> Группы
                            </a>
                            <a href="{{ route('admin.disciplines.index') }}" class="dropdown-item">
                                <i class="fas fa-book w-4 icon-subtle"></i> Дисциплины
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="dropdown-item">
                                <i class="fas fa-user-cog w-4 icon-subtle"></i> Пользователи
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->isTeacher())
                    <a href="{{ route('teacher.lessons.index') }}"
                       class="nav-link {{ request()->is('teacher/lessons*') ? 'active' : '' }}"
                       aria-current="{{ request()->is('teacher/lessons*') ? 'page' : 'false' }}">
                        <i class="fas fa-chalkboard-teacher text-xs"></i> Мои занятия
                    </a>
                    @endif

                    @if(!Auth::user()->isStudent())
                    <a href="{{ route('reports.index') }}"
                       class="nav-link {{ request()->is('reports*') ? 'active' : '' }}"
                       aria-current="{{ request()->is('reports*') ? 'page' : 'false' }}">
                        <i class="fas fa-chart-bar text-xs"></i> Отчёты
                    </a>
                    @endif
                </nav>

                <div class="flex items-center gap-2">
                    {{-- User dropdown (desktop) --}}
                    <div class="hidden md:block relative"
                         x-data="themeToggle('{{ Auth::user()->theme ?? 'light' }}')"
                         @click.away="open = false">

                        {{-- Toggle (compact sun/moon) --}}
                        <button @click="toggle()"
                                :aria-label="isDark ? 'Переключить на светлую тему' : 'Переключить на тёмную тему'"
                                :title="isDark ? 'Светлая тема' : 'Тёмная тема'"
                                class="p-2 rounded-lg border transition-all duration-200"
                                style="background:var(--surface);border-color:var(--border);">
                            <i class="fas text-xs"
                               :class="isDark ? 'fa-sun text-amber-400' : 'fa-moon text-violet-400'"></i>
                        </button>
                    </div>

                    {{-- User menu --}}
                    <div class="hidden md:block relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @keydown.escape="open = false"
                                :aria-expanded="open"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl border text-sm transition-all duration-150"
                                style="background:var(--surface);border-color:var(--border);">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-teal-400 to-violet-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="max-w-32 truncate" style="color:var(--text-muted);">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 icon-subtle" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-1.5 w-52 rounded-xl border py-1 shadow-xl"
                             style="background:var(--dropdown-bg);backdrop-filter:blur(20px);border-color:var(--border);">
                            <div class="px-4 py-2" style="border-bottom:1px solid var(--border-subtle);">
                                <p class="text-xs" style="color:var(--text-subtle);">
                                    @switch(Auth::user()->role)
                                        @case('admin')   Администратор @break
                                        @case('teacher') Преподаватель @break
                                        @case('student') Студент       @break
                                        @case('dean')    Учебная часть @break
                                        @default {{ Auth::user()->role }}
                                    @endswitch
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-left !text-rose-500 hover:!bg-rose-500/10">
                                    <i class="fas fa-sign-out-alt w-4"></i> Выход
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Hamburger (mobile) --}}
                    <button @click="mobileOpen = !mobileOpen"
                            :aria-expanded="mobileOpen"
                            aria-controls="mobile-menu"
                            aria-label="Меню"
                            class="md:hidden p-2 rounded-lg border transition-colors"
                            style="background:var(--surface);border-color:var(--border);">
                        <i class="fas w-4 icon-subtle" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-primary btn-sm">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </a>
                @endauth
            </div>
        </div>

        {{-- Mobile menu --}}
        @auth
        <div id="mobile-menu"
             x-show="mobileOpen"
             @keydown.escape.window="mobileOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden px-4 py-3 space-y-1 border-t"
             style="border-color:var(--border-subtle);background:var(--nav-bg);">
            <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                <i class="fas fa-tachometer-alt w-4 mr-1 icon-subtle"></i> Панель
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.groups.index') }}" class="mobile-nav-link">
                    <i class="fas fa-users w-4 mr-1 icon-subtle"></i> Группы
                </a>
                <a href="{{ route('admin.disciplines.index') }}" class="mobile-nav-link">
                    <i class="fas fa-book w-4 mr-1 icon-subtle"></i> Дисциплины
                </a>
                <a href="{{ route('admin.users.index') }}" class="mobile-nav-link">
                    <i class="fas fa-user-cog w-4 mr-1 icon-subtle"></i> Пользователи
                </a>
            @endif
            @if(Auth::user()->isTeacher())
                <a href="{{ route('teacher.lessons.index') }}" class="mobile-nav-link">
                    <i class="fas fa-chalkboard-teacher w-4 mr-1 icon-subtle"></i> Мои занятия
                </a>
            @endif
            @if(!Auth::user()->isStudent())
                <a href="{{ route('reports.index') }}" class="mobile-nav-link">
                    <i class="fas fa-chart-bar w-4 mr-1 icon-subtle"></i> Отчёты
                </a>
            @endif
            <div class="pt-2" style="border-top:1px solid var(--border-subtle);">
                {{-- Theme toggle (mobile) --}}
                <div x-data="themeToggle('{{ Auth::user()->theme ?? 'light' }}')" class="mb-1">
                    <button @click="toggle()"
                            class="mobile-nav-link flex items-center gap-2 w-full text-left">
                        <i class="fas w-4" :class="isDark ? 'fa-sun text-amber-400' : 'fa-moon text-violet-400'"></i>
                        <span x-text="isDark ? 'Светлая тема' : 'Тёмная тема'"></span>
                    </button>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link !text-rose-500 w-full text-left">
                        <i class="fas fa-sign-out-alt w-4 mr-1"></i> Выход
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </header>

    {{-- ─── Flash messages ──────────────────────────────────────────────────── --}}
    @if(session('success') || session('error') || $errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-3" x-data>
        @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             role="alert"
             class="flex items-center gap-3 px-4 py-3.5 rounded-xl border"
             style="background:rgba(20,184,166,.1);border-color:rgba(20,184,166,.2);">
            <i class="fas fa-check-circle text-teal-500 shrink-0"></i>
            <span class="text-sm text-teal-600 flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-teal-500/50 hover:text-teal-500 transition-colors ml-auto" aria-label="Закрыть">
                <i class="fas fa-xmark text-xs"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             role="alert"
             class="flex items-center gap-3 px-4 py-3.5 rounded-xl border"
             style="background:rgba(244,63,94,.08);border-color:rgba(244,63,94,.18);">
            <i class="fas fa-exclamation-circle text-rose-500 shrink-0"></i>
            <span class="text-sm text-rose-600 flex-1">{{ session('error') }}</span>
            <button @click="show = false" class="text-rose-500/50 hover:text-rose-500 transition-colors ml-auto" aria-label="Закрыть">
                <i class="fas fa-xmark text-xs"></i>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             role="alert"
             class="px-4 py-3.5 rounded-xl border"
             style="background:rgba(244,63,94,.08);border-color:rgba(244,63,94,.18);">
            <div class="flex items-start gap-3">
                <i class="fas fa-triangle-exclamation text-rose-500 shrink-0 mt-0.5"></i>
                <ul class="text-sm text-rose-600 space-y-0.5 flex-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="show = false" class="text-rose-500/50 hover:text-rose-500 transition-colors" aria-label="Закрыть">
                    <i class="fas fa-xmark text-xs"></i>
                </button>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ─── Main content ────────────────────────────────────────────────────── --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────────────────── --}}
    <footer class="mt-16 py-6" style="border-top:1px solid var(--border-subtle);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs" style="color:var(--text-subtle);">
            <p>&copy; {{ date('Y') }} Активный студент</p>
            <p>Асатулин В.А. · Геребен Я.В. · Птухин Т.И.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>