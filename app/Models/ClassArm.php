<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassArm extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level_id',
        'name',
        'code',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'current_class', 'code');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherClassAssignment::class);
    }

    public function subjectAssignments()
    {
        return $this->hasMany(SubjectTeacherAssignment::class);
    }
}
