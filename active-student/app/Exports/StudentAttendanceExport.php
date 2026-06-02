<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithTitle
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        $attendances = Attendance::where('student_id', $this->user->id)
            ->with(['lesson.discipline', 'lesson.teacher'])
            ->get()
            ->sortByDesc(fn($a) => optional($a->lesson)->date);

        return $attendances->map(function ($att) {
            return [
                'date' => optional($att->lesson)->date?->format('d.m.Y') ?? '—',
                'pair' => optional($att->lesson)->pair_number ?? '—',
                'discipline' => optional(optional($att->lesson)->discipline)->name ?? '—',
                'teacher' => optional(optional($att->lesson)->teacher)->name ?? '—',
                'status' => $att->status,
                'reason' => $att->reason ?? '—',
            ];
        });
    }

    public function headings(): array
    {
        return ['Дата', 'Пара', 'Дисциплина', 'Преподаватель', 'Статус', 'Причина'];
    }

    public function title(): string
    {
        return 'Студент ' . $this->user->name;
    }
}