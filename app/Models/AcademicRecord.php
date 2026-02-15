<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'learning_area_id',
        'grade_level',
        'term',
        'year',
        'score',
        'assessment_type',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the student that owns this record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the learning area for this record.
     */
    public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    /**
     * Get the term for this record.
     */
    public function term()
    {
        return $this->belongsTo(Term::class, 'term', 'term_number')
            ->where('year', $this->year);
    }

    /**
     * Scope a query to filter by term and year.
     */
    public function scopeTermYear($query, $term, $year)
    {
        return $query->where('term', $term)->where('year', $year);
    }

    /**
     * Scope a query to filter by assessment type.
     */
    public function scopeAssessmentType($query, $type)
    {
        return $query->where('assessment_type', $type);
    }
}
