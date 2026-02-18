<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'weight',
        'applicable_grades',
        'is_active',
    ];

    protected $casts = [
        'applicable_grades' => 'array',
        'is_active' => 'boolean',
    ];

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class, 'assessment_type', 'code');
    }
}
