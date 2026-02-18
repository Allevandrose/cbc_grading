<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term_id',
        'academic_year_id',
        'fee_cleared',
        'library_cleared',
        'lab_cleared',
        'sports_cleared',
        'discipline_cleared',
        'overall_cleared',
        'clearance_date',
        'remarks',
        'cleared_by',
    ];

    protected $casts = [
        'fee_cleared' => 'boolean',
        'library_cleared' => 'boolean',
        'lab_cleared' => 'boolean',
        'sports_cleared' => 'boolean',
        'discipline_cleared' => 'boolean',
        'overall_cleared' => 'boolean',
        'clearance_date' => 'date',
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

    public function clearer()
    {
        return $this->belongsTo(User::class, 'cleared_by');
    }
}
