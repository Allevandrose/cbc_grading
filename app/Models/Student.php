<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'upi_number',
        'admission_number',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'birth_certificate_number',
        'nationality',
        'phone',
        'email',
        'address',
        'current_grade_level',
        'current_class',
        'enrollment_year',
        'graduation_year',
        'parent_name',
        'parent_phone',
        'parent_email',
        'parent_relationship',
        'is_active',
        'is_graduated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_graduated' => 'boolean',
        'enrollment_year' => 'integer',
        'graduation_year' => 'integer',
    ];

    /**
     * Get the student's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    /**
     * Get the student's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Get the academic records for this student.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    /**
     * Get the pathway recommendations for this student.
     */
    public function pathwayRecommendations()
    {
        return $this->hasMany(PathwayRecommendation::class);
    }

    /**
     * Get the latest pathway recommendation.
     */
    public function latestRecommendation()
    {
        return $this->hasOne(PathwayRecommendation::class)->latestOfMany();
    }

    /**
     * Get the grade level of this student.
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'current_grade_level', 'grade');
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by grade level.
     */
    public function scopeInGrade($query, $grade)
    {
        return $query->where('current_grade_level', $grade);
    }

    /**
     * Scope a query to search students.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('upi_number', 'like', "%{$search}%")
                ->orWhere('admission_number', 'like', "%{$search}%");
        });
    }
}
