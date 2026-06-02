@props(['class' => '', 'padding' => 'p-6'])
<div {{ $attributes->merge(['class' => "glass-card {$padding} {$class}"]) }}>
    {{ $slot }}
</div>