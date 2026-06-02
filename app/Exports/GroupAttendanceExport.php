<?php

namespace App\Exports;

use App\Models\Group;
use App\Models\Lesson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GroupAttendanceExport implements FromCollection, WithHeadings, WithTitle
{
    protected $group;
    protected $dateFrom;
    protected $dateTo;

    public function __construct(Group $group, $dateFrom, $dateTo)
    {
        $this->group = $group;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $lessons = Lesson::where('group_id', $this->group->id)
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->with(['attendances'])
            ->get();

        $students = $this->group->students()->orderBy('name')->get();

        return $students->map(function ($student) use ($lessons) {
            $records = $lessons->flatMap->attendances->where('student_id', $student->id);
            $total = $lessons->count();
            $present = $records->whereIn('status', ['present', 'late'])->count();

            return [
                'name' => $student->name,
                'total' => $total,
                'present' => $present,
                'absent' => $records->where('status', 'absent')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        });
    }

    public function headings(): array
    {
        return ['Студент', 'Всего занятий', 'Присутствовал', 'Прогулы', 'Болезнь', 'Посещаемость %'];
    }

    public function title(): string
    {
        return 'Группа ' . $this->group->name;
    }
}