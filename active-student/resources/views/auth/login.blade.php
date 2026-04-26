@extends('layouts.auth')
@section('title', 'Вход в систему')

@section('content')
<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-violet-500 flex items-center justify-center shadow-2xl shadow-teal-500/25 mb-4">
            <i class="fas fa-graduation-cap text-white text-xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">Активный студент</h1>
        <p class="text-sm text-white/40 mt-1">Система учёта посещаемости</p>
    </div>

    <div class="glass-card p-8">
        <h2 class="text-lg font-semibold text-white mb-6">Вход в систему</h2>

        <form method="POST" action="{{ route('login') }}" x-data>
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="email" class="label-text">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-xs icon-subtle"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               class="input-glass pl-10 @error('email') error @enderror"
                               placeholder="admin@example.com"
                               aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}">
                    </div>
                    @error('email')
                        <p id="email-error" class="mt-1.5 text-xs text-rose-400" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label-text">Пароль</label>
                    <div class="relative" x-data="{ show: false }">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-xs icon-subtle"></i>
                        <input :type="show ? 'text' : 'password'"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               class="input-glass pl-10 pr-12 @error('password') error @enderror"
                               placeholder="••••••••"
                               aria-describedby="{{ $errors->has('password') ? 'pw-error' : '' }}">
                        <button type="button"
                                @click="show = !show"
                                :aria-label="show ? 'Скрыть пароль' : 'Показать пароль'"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded transition-colors hover:bg-white/5 icon-subtle">
                            <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p id="pw-error" class="mt-1.5 text-xs text-rose-400" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           class="w-4 h-4 rounded border accent-teal-500"
                           style="background:var(--input-bg);border-color:var(--border);">
                    <label for="remember" class="text-sm select-none muted-text">
                        Запомнить меня
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full justify-center btn-lg">
                    <i class="fas fa-sign-in-alt"></i>
                    Войти
                </button>
            </div>
        </form>
    </div>

    <div class="mt-4 glass-card p-4">
        <p class="hint-text mb-2">Тестовые учётные записи:</p>
        <div class="grid grid-cols-2 gap-1 text-xs muted-text">
            <span>admin@example.com</span>
            <span>teacher@example.com</span>
            <span>p.petrov@teams.university.ru</span>
            <span class="col-span-2 mt-0.5 text-white/25">пароль: <code class="text-teal-400/70">password</code></span>
        </div>
    </div>

</div>
@endsection
