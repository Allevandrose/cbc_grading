<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $year = $this->faker->unique()->numberBetween(2020, 2030);

        return [
            'name' => $year . ' Academic Year',
            'year' => $year,
            'start_date' => $this->faker->dateTimeBetween("{$year}-01-01", "{$year}-02-01"),
            'end_date' => $this->faker->dateTimeBetween("{$year}-11-01", "{$year}-12-31"),
            'is_current' => false,
            'is_active' => true,
        ];
    }
}
