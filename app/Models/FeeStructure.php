<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level_id',
        'term_id',
        'academic_year_id',
        'tuition_fee',
        'activity_fee',
        'transport_fee',
        'boarding_fee',
        'uniform_fee',
        'other_fees',
        'total',
        'due_date',
        'is_active',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'activity_fee' => 'decimal:2',
        'transport_fee' => 'decimal:2',
        'boarding_fee' => 'decimal:2',
        'uniform_fee' => 'decimal:2',
        'other_fees' => 'decimal:2',
        'total' => 'decimal:2',
        'due_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
