<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;

class GradeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            // Lower Primary
            ['grade' => 1, 'name' => 'Grade 1', 'stage' => 'Lower Primary', 'min_age' => 6, 'max_age' => 7],
            ['grade' => 2, 'name' => 'Grade 2', 'stage' => 'Lower Primary', 'min_age' => 7, 'max_age' => 8],
            ['grade' => 3, 'name' => 'Grade 3', 'stage' => 'Lower Primary', 'min_age' => 8, 'max_age' => 9],

            // Upper Primary
            ['grade' => 4, 'name' => 'Grade 4', 'stage' => 'Upper Primary', 'min_age' => 9, 'max_age' => 10],
            ['grade' => 5, 'name' => 'Grade 5', 'stage' => 'Upper Primary', 'min_age' => 10, 'max_age' => 11],
            ['grade' => 6, 'name' => 'Grade 6', 'stage' => 'Upper Primary', 'min_age' => 11, 'max_age' => 12],

            // Junior Secondary
            ['grade' => 7, 'name' => 'Grade 7', 'stage' => 'Junior Secondary', 'min_age' => 12, 'max_age' => 13],
            ['grade' => 8, 'name' => 'Grade 8', 'stage' => 'Junior Secondary', 'min_age' => 13, 'max_age' => 14],
            ['grade' => 9, 'name' => 'Grade 9', 'stage' => 'Junior Secondary', 'min_age' => 14, 'max_age' => 15],

            // Senior Secondary
            ['grade' => 10, 'name' => 'Grade 10', 'stage' => 'Senior Secondary', 'min_age' => 15, 'max_age' => 16],
            ['grade' => 11, 'name' => 'Grade 11', 'stage' => 'Senior Secondary', 'min_age' => 16, 'max_age' => 17],
            ['grade' => 12, 'name' => 'Grade 12', 'stage' => 'Senior Secondary', 'min_age' => 17, 'max_age' => 18],
        ];

        foreach ($grades as $grade) {
            GradeLevel::create($grade);
        }

        $this->command->info('Grade levels seeded successfully!');
    }
}
