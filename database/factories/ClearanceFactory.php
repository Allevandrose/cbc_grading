<?php

namespace Database\Factories;

use App\Models\Clearance;
use App\Models\Student;
use App\Models\Term;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClearanceFactory extends Factory
{
    protected $model = Clearance::class;

    public function definition(): array
    {
        $feeCleared = $this->faker->boolean(70);
        $libraryCleared = $this->faker->boolean(90);
        $labCleared = $this->faker->boolean(85);
        $sportsCleared = $this->faker->boolean(80);
        $disciplineCleared = $this->faker->boolean(95);

        $overallCleared = $feeCleared && $libraryCleared && $labCleared && $sportsCleared && $disciplineCleared;

        return [
            'student_id' => Student::factory(),
            'term_id' => Term::factory(),
            'academic_year_id' => AcademicYear::getCurrent() ?? AcademicYear::factory(),
            'fee_cleared' => $feeCleared,
            'library_cleared' => $libraryCleared,
            'lab_cleared' => $labCleared,
            'sports_cleared' => $sportsCleared,
            'discipline_cleared' => $disciplineCleared,
            'overall_cleared' => $overallCleared,
            'clearance_date' => $overallCleared ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'remarks' => $this->faker->optional()->sentence(),
            'cleared_by' => $overallCleared ? User::where('role', 'accountant')->inRandomOrder()->first() : null,
        ];
    }
}
