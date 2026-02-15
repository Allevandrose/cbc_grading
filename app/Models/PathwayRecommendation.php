<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathwayRecommendation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'wes_percentage',
        'final_grade',
        'final_points',
        'cluster_scores',
        'recommended_pathway',
        'subject_breakdown',
        'term',
        'year',
        'is_finalized',
        'recommendation_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'wes_percentage' => 'decimal:2',
        'final_points' => 'integer',
        'cluster_scores' => 'array',
        'subject_breakdown' => 'array',
        'is_finalized' => 'boolean',
        'recommendation_date' => 'date',
    ];

    /**
     * Get the student that owns this recommendation.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope a query to only include finalized recommendations.
     */
    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }

    /**
     * Scope a query to filter by term and year.
     */
    public function scopeTermYear($query, $term, $year)
    {
        return $query->where('term', $term)->where('year', $year);
    }

    /**
     * Scope a query to filter by pathway.
     */
    public function scopePathway($query, $pathway)
    {
        return $query->where('recommended_pathway', $pathway);
    }

    /**
     * Get the pathway name with formatting.
     */
    public function getPathwayNameAttribute(): string
    {
        return match ($this->recommended_pathway) {
            'STEM' => 'Science, Technology, Engineering & Mathematics',
            'SOCIAL' => 'Social Sciences',
            'ARTS' => 'Arts & Sports Science',
            default => $this->recommended_pathway,
        };
    }

    /**
     * Get the grade descriptor from final_grade.
     */
    public function getGradeDescriptorAttribute(): string
    {
        return match ($this->final_grade) {
            'EE1', 'EE2' => 'Exceeding Expectations',
            'ME1', 'ME2' => 'Meeting Expectations',
            'AE1', 'AE2' => 'Approaching Expectations',
            'BE1', 'BE2' => 'Below Expectations',
            default => 'Unknown',
        };
    }
}
