<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчёт по группе {{ $group->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1e293b; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .info { color: #666; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Группа {{ $group->name }}</h1>
    <p class="info">{{ $group->faculty }} | Период: {{ $dateFrom }} — {{ $dateTo }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Студент</th>
                <th>Занятий</th>
                <th>Присутствовал</th>
                <th>Прогулы</th>
                <th>Болезнь</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $row)
            <tr>
                <td>{{ $row['student']->name }}</td>
                <td>{{ $row['total'] }}</td>
                <td>{{ $row['present'] }}</td>
                <td>{{ $row['absent'] }}</td>
                <td>{{ $row['sick'] }}</td>
                <td>{{ $row['percentage'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>