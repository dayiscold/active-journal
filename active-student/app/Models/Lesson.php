<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'discipline_id', 'teacher_id', 'date', 'pair_number'];

    protected $casts = [
        'date' => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
