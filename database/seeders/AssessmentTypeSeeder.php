<?php

namespace Database\Seeders;

use App\Models\AssessmentType;
use Illuminate\Database\Seeder;

class AssessmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = [
            [
                'code' => 'CAT1',
                'name' => 'Continuous Assessment Test 1',
                'description' => 'First continuous assessment test conducted mid-term',
                'weight' => 0.2,
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'code' => 'CAT2',
                'name' => 'Continuous Assessment Test 2',
                'description' => 'Second continuous assessment test conducted before end-term',
                'weight' => 0.2,
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'code' => 'EXAM',
                'name' => 'End Term Examination',
                'description' => 'Official end of term examination',
                'weight' => 0.6,
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'code' => 'KPSEA',
                'name' => 'Kenya Primary School Education Assessment',
                'description' => 'National assessment for Grade 6',
                'weight' => 1.0,
                'applicable_grades' => [6],
            ],
            [
                'code' => 'SBA',
                'name' => 'School Based Assessment',
                'description' => 'Continuous assessment for Grades 7 and 8',
                'weight' => 1.0,
                'applicable_grades' => [7, 8],
            ],
            [
                'code' => 'KJSEA',
                'name' => 'Kenya Junior School Education Assessment',
                'description' => 'National assessment for Grade 9',
                'weight' => 1.0,
                'applicable_grades' => [9],
            ],
        ];

        foreach ($assessments as $assessment) {
            AssessmentType::create([
                'code' => $assessment['code'],
                'name' => $assessment['name'],
                'description' => $assessment['description'],
                'weight' => $assessment['weight'],
                'applicable_grades' => json_encode($assessment['applicable_grades']),
                'is_active' => true,
            ]);
        }

        $this->command->info('Assessment types seeded successfully!');
    }
}
