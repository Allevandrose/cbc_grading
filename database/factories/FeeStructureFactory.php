<?php

namespace Database\Factories;

use App\Models\FeeStructure;
use App\Models\GradeLevel;
use App\Models\Term;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeStructureFactory extends Factory
{
    protected $model = FeeStructure::class;

    public function definition(): array
    {
        $tuition = $this->faker->numberBetween(15000, 30000);
        $activity = $this->faker->numberBetween(2000, 5000);
        $transport = $this->faker->numberBetween(0, 8000);
        $boarding = $this->faker->numberBetween(0, 15000);
        $uniform = $this->faker->numberBetween(0, 3000);
        $other = $this->faker->numberBetween(0, 2000);

        $total = $tuition + $activity + $transport + $boarding + $uniform + $other;

        return [
            'grade_level_id' => GradeLevel::factory(),
            'term_id' => Term::factory(),
            'academic_year_id' => AcademicYear::getCurrent() ?? AcademicYear::factory(),
            'tuition_fee' => $tuition,
            'activity_fee' => $activity,
            'transport_fee' => $transport,
            'boarding_fee' => $boarding,
            'uniform_fee' => $uniform,
            'other_fees' => $other,
            'total' => $total,
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'is_active' => true,
        ];
    }
}
