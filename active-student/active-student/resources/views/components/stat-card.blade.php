@props(['value', 'label', 'icon', 'color' => 'teal'])
@php
    $palette = [
        'teal'   => ['ring' => 'border-teal-500/20',   'icon' => 'bg-teal-500/10 text-teal-400'],
        'violet' => ['ring' => 'border-violet-500/20', 'icon' => 'bg-violet-500/10 text-violet-400'],
        'amber'  => ['ring' => 'border-amber-500/20',  'icon' => 'bg-amber-500/10  text-amber-400'],
        'rose'   => ['ring' => 'border-rose-500/20',   'icon' => 'bg-rose-500/10   text-rose-400'],
        'sky'    => ['ring' => 'border-sky-500/20',    'icon' => 'bg-sky-500/10    text-sky-400'],
    ];
    $p = $palette[$color] ?? $palette['teal'];
@endphp
<div class="glass-card {{ $p['ring'] }} p-5 flex items-center gap-4">
    <div class="shrink-0 rounded-xl p-3 {{ $p['icon'] }}">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    <div class="min-w-0">
        <p class="text-2xl font-bold text-white leading-none">{{ $value }}</p>
        <p class="text-xs text-white/45 mt-1 truncate">{{ $label }}</p>
    </div>
</div>