<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_ACCOUNTANT = 'accountant';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
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
            'onboarding_completed_at' => 'datetime',
        ];
    }

    // --- RELATIONSHIPS ---

    public function teacherClassAssignments()
    {
        return $this->hasMany(TeacherClassAssignment::class, 'teacher_id');
    }

    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacherAssignment::class, 'teacher_id');
    }

    // --- HIERARCHY & PRIVACY LOGIC ---

    /**
     * Get ClassArm IDs the teacher is assigned to.
     * (e.g. IDs for Grade 7A, Grade 8B)
     */
    public function getMyClassArmIdsAttribute(): Collection
    {
        return $this->teacherClassAssignments()
            ->where('academic_year_id', $this->getCurrentAcademicYearId())
            ->where('is_active', true)
            ->pluck('class_arm_id');
    }

    /**
     * Get Grade Level IDs (Integers: 7, 8, 9...)
     * We derive this from ClassArms so we can filter the 'students' table correctly.
     */
    public function getMyGradeIdsAttribute(): Collection
    {
        // Get the ClassArm objects to access their grade_level
        $classArms = ClassArm::whereIn('id', $this->my_class_arm_ids)->get();

        // Extract just the grade level integer (e.g. 7)
        return $classArms->pluck('grade_level')->unique();
    }

    /**
     * Get Subject IDs the teacher is allowed to see based on hierarchy.
     */
    public function getMySubjectIdsAttribute(): Collection
    {
        $currentYearId = $this->getCurrentAcademicYearId();
        $myClassArmIds = $this->my_class_arm_ids;

        // 1. HEADTEACHER CHECK
        // Column is 'is_head_teacher', not 'is_headteacher'
        $isHeadTeacher = $this->teacherClassAssignments()
            ->where('academic_year_id', $currentYearId)
            ->where('is_head_teacher', true)
            ->exists();

        if ($isHeadTeacher) {
            return LearningArea::pluck('id');
        }

        // 2. FORM TEACHER CHECK
        // Column is 'is_form_teacher', not 'is_class_teacher'
        $myFormClassAssignments = $this->teacherClassAssignments()
            ->where('academic_year_id', $currentYearId)
            ->where('is_form_teacher', true)
            ->get();

        if ($myFormClassAssignments->isNotEmpty()) {
            // Get the ClassArm IDs for which they are form teacher
            $formClassArmIds = $myFormClassAssignments->pluck('class_arm_id');

            // See all subjects assigned to these specific classes
            return SubjectTeacherAssignment::whereIn('class_arm_id', $formClassArmIds)
                ->where('academic_year_id', $currentYearId)
                ->pluck('learning_area_id')
                ->unique();
        }

        // 3. SUBJECT TEACHER (Default)
        return $this->subjectTeacherAssignments()
            ->where('academic_year_id', $currentYearId)
            ->pluck('learning_area_id')
            ->unique();
    }

    private function getCurrentAcademicYearId()
    {
        return AcademicYear::where('is_current', true)->first()?->id ?? 1;
    }

    // --- HELPERS ---

    public function isClassTeacher($classArmId = null): bool
    {
        // Column is 'is_form_teacher'
        $query = $this->teacherClassAssignments()->where('is_form_teacher', true);
        return $classArmId ? $query->where('class_arm_id', $classArmId)->exists() : $query->exists();
    }

    public function isSeniorTeacher(): bool
    {
        // Column is 'is_head_teacher'
        return $this->teacherClassAssignments()->where('is_head_teacher', true)->exists();
    }

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }
    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }
}
