<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчёт по студенту {{ $user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        .info { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1e293b; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .stats { margin: 20px 0; }
        .stat { display: inline-block; margin-right: 30px; }
    </style>
</head>
<body>
    <h1>{{ $user->name }}</h1>
    <p class="info">{{ $user->group?->name ?? 'Без группы' }} | Студенческий билет: {{ $user->student_id ?? '—' }}</p>
    
    <div class="stats">
        <div class="stat"><strong>Посещаемость:</strong> {{ $percentage }}%</div>
        <div class="stat"><strong>Всего записей:</strong> {{ $total }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Дата</th>
                <th>Пара</th>
                <th>Дисциплина</th>
                <th>Преподаватель</th>
                <th>Статус</th>
                <th>Причина</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr>
                <td>{{ optional($att->lesson)->date?->format('d.m.Y') ?? '—' }}</td>
                <td>{{ optional($att->lesson)->pair_number ?? '—' }}</td>
                <td>{{ optional(optional($att->lesson)->discipline)->name ?? '—' }}</td>
                <td>{{ optional(optional($att->lesson)->teacher)->name ?? '—' }}</td>
                <td>{{ $att->status }}</td>
                <td>{{ $att->reason ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>