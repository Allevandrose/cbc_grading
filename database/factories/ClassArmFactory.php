<?php

namespace Database\Factories;

use App\Models\ClassArm;
use App\Models\GradeLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassArmFactory extends Factory
{
    protected $model = ClassArm::class;

    public function definition(): array
    {
        $gradeLevel = GradeLevel::inRandomOrder()->first() ?? GradeLevel::factory()->create();
        $grade = $gradeLevel->grade;
        $letter = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F']);

        return [
            'grade_level_id' => $gradeLevel->id,
            'name' => $letter,
            'code' => $grade . $letter,
            'capacity' => $this->faker->numberBetween(40, 50),
            'is_active' => true,
        ];
    }
}
