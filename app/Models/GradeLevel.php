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

    // --- RELATIONSHIPS ---

    /**
     * Get the class arms (e.g., 7A, 7B) for this grade level.
     */
    public function classArms()
    {
        return $this->hasMany(ClassArm::class, 'grade_level_id');
    }

    /**
     * Get the students in this grade level.
     * Note: This links to the 'current_grade_level' integer (e.g., 7)
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

    // --- SCOPES ---

    /**
     * Scope a query to only include active grade levels.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
