<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'course', 'faculty'];

    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'group_discipline')
            ->withPivot('teacher_id', 'semester')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
