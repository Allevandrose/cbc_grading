<?php

namespace Database\Factories;

use App\Models\ReportCard;
use App\Models\Student;
use App\Models\Term;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportCardFactory extends Factory
{
    protected $model = ReportCard::class;

    public function definition(): array
    {
        $subjects = [];
        $total = 0;
        $count = 0;

        // Generate random subject grades
        for ($i = 1; $i <= 8; $i++) {
            $score = $this->faker->numberBetween(40, 98);
            $subjects["subject_{$i}"] = [
                'name' => $this->faker->word(),
                'score' => $score,
                'grade' => $this->getGradeFromScore($score),
            ];
            $total += $score;
            $count++;
        }

        $average = $count > 0 ? round($total / $count, 2) : 0;

        return [
            'student_id' => Student::factory(),
            'term_id' => Term::factory(),
            'academic_year_id' => AcademicYear::getCurrent() ?? AcademicYear::factory(),
            'report_number' => $this->faker->unique()->bothify('RPT-####-####'),
            'subject_grades' => json_encode($subjects),
            'total_marks' => $total,
            'average' => $average,
            'overall_grade' => $this->getGradeFromScore($average),
            'position' => $this->faker->optional()->numberBetween(1, 40),
            'teacher_comments' => $this->faker->optional()->paragraph(),
            'principal_comments' => $this->faker->optional()->paragraph(),
            'file_path' => $this->faker->optional()->filePath(),
            'is_approved' => $this->faker->boolean(80),
            'is_published' => $this->faker->boolean(70),
        ];
    }

    private function getGradeFromScore($score): string
    {
        return match (true) {
            $score >= 90 => 'EE1',
            $score >= 75 => 'EE2',
            $score >= 58 => 'ME1',
            $score >= 41 => 'ME2',
            $score >= 31 => 'AE1',
            $score >= 21 => 'AE2',
            $score >= 11 => 'BE1',
            default => 'BE2',
        };
    }
}
