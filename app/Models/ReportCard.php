<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term_id',
        'academic_year_id',
        'report_number',
        'subject_grades',
        'total_marks',
        'average',
        'overall_grade',
        'position',
        'teacher_comments',
        'principal_comments',
        'file_path',
        'is_approved',
        'is_published',
    ];

    protected $casts = [
        'subject_grades' => 'array',
        'total_marks' => 'decimal:2',
        'average' => 'decimal:2',
        'is_approved' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
