@props(['status' => null, 'role' => null])
@php
    $map = [
        'present' => ['cls' => 'badge-teal',   'label' => 'Присутствовал'],
        'absent'  => ['cls' => 'badge-rose',   'label' => 'Отсутствовал'],
        'late'    => ['cls' => 'badge-amber',  'label' => 'Опоздал'],
        'sick'    => ['cls' => 'badge-sky',    'label' => 'Болезнь'],
        'admin'   => ['cls' => 'badge-rose',   'label' => 'Администратор'],
        'teacher' => ['cls' => 'badge-violet', 'label' => 'Преподаватель'],
        'student' => ['cls' => 'badge-teal',   'label' => 'Студент'],
        'dean'    => ['cls' => 'badge-slate',  'label' => 'Учебная часть'],
    ];
    $key  = $status ?? $role ?? '';
    $item = $map[$key] ?? ['cls' => 'badge-slate', 'label' => $key];
@endphp
<span class="badge {{ $item['cls'] }}">
    {{ $slot->isNotEmpty() ? $slot : $item['label'] }}
</span>