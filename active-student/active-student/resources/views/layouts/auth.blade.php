<!DOCTYPE html>
<html lang="ru" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Активный студент') — Активный студент</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    {{-- ambient glow --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute top-1/4 -left-24 w-[500px] h-[500px] bg-teal-500/8 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 -right-24 w-[500px] h-[500px] bg-violet-500/8 rounded-full blur-[120px]"></div>
    </div>

    @yield('content')
</body>
</html>