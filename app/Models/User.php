<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ... (your existing constants and fillable are fine) ...

    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_ACCOUNTANT = 'accountant';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        // You might need this later for the "First Time Setup" check
        'onboarding_completed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'onboarding_completed_at' => 'datetime', // Add this
        ];
    }

    // --- START OF NEW CODE ---

    /**
     * Relationship: Classes assigned to this teacher
     */
    public function teacherClassAssignments()
    {
        // Assumes table 'teacher_class_assignments' has user_id and grade_level_id
        // We also fetch the 'is_class_teacher' flag if it exists in that table
        return $this->hasMany(\App\Models\TeacherClassAssignment::class, 'user_id');
    }

    /**
     * Relationship: Subjects assigned to this teacher
     */
    public function subjectTeacherAssignments()
    {
        // Assumes table 'subject_teacher_assignments' has user_id and learning_area_id
        return $this->hasMany(\App\Models\SubjectTeacherAssignment::class, 'user_id');
    }

    /**
     * Helper: Get all Grade IDs this teacher teaches (or is class teacher of)
     */
    public function getMyGradeIdsAttribute()
    {
        // If the relationship exists, pluck the IDs
        return $this->teacherClassAssignments->pluck('grade_level_id')->unique();
    }

    /**
     * Helper: Get all Subject IDs this teacher teaches
     */
    public function getMySubjectIdsAttribute()
    {
        return $this->subjectTeacherAssignments->pluck('learning_area_id')->unique();
    }

    /**
     * Helper: Check if this user is a "Class Teacher" for any specific grade
     * (We will refine this once I see your migration file)
     */
    public function isClassTeacher($gradeLevelId = null)
    {
        $query = $this->teacherClassAssignments()->where('is_class_teacher', true);

        if ($gradeLevelId) {
            return $query->where('grade_level_id', $gradeLevelId)->exists();
        }

        return $query->exists();
    }

    /**
     * Helper: Check if user is a Headteacher or Deputy
     * (This usually comes from the User table role or a specific flag in assignments)
     */
    public function isSeniorTeacher()
    {
        // Option A: If you store this in the users table (e.g. role 'headteacher')
        // return in_array($this->role, ['headteacher', 'deputy_headteacher']);

        // Option B: If you store this in the assignment table (waiting for your file)
        return $this->teacherClassAssignments()->where('is_headteacher', true)->exists();
    }

    // --- END OF NEW CODE ---

    // ... (your existing role checks are fine) ...

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }
}
