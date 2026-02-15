<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grade',
        'name',
        'stage',
        'min_age',
        'max_age',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the students in this grade level.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'current_grade_level', 'grade');
    }

    /**
     * Get the academic records for this grade level.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class, 'grade_level', 'grade');
    }

    /**
     * Scope a query to only include active grade levels.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
