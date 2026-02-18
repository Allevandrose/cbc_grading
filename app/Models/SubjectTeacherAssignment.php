<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'learning_area_id',
        'class_arm_id',
        'academic_year_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function classArm()
    {
        return $this->belongsTo(ClassArm::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
