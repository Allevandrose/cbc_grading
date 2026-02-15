<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningArea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'applicable_grades',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'applicable_grades' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the academic records for this learning area.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    /**
     * Scope a query to only include active learning areas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by applicable grade.
     */
    public function scopeForGrade($query, $grade)
    {
        return $query->whereJsonContains('applicable_grades', $grade);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
