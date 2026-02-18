<?php

namespace Database\Factories;

use App\Models\TeacherClassAssignment;
use App\Models\User;
use App\Models\ClassArm;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherClassAssignmentFactory extends Factory
{
    protected $model = TeacherClassAssignment::class;

    public function definition(): array
    {
        return [
            'teacher_id' => User::where('role', 'teacher')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'teacher']),
            'class_arm_id' => ClassArm::factory(),
            'academic_year_id' => AcademicYear::getCurrent() ?? AcademicYear::factory(),
            'is_form_teacher' => $this->faker->boolean(30),
            'is_active' => true,
        ];
    }
}
