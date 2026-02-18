<?php

namespace Database\Factories;

use App\Models\AssessmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentTypeFactory extends Factory
{
    protected $model = AssessmentType::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomElement(['CAT1', 'CAT2', 'EXAM', 'KPSEA', 'SBA', 'KJSEA']),
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(),
            'weight' => $this->faker->randomFloat(2, 0.5, 2.0),
            'applicable_grades' => json_encode([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'is_active' => true,
        ];
    }
}
