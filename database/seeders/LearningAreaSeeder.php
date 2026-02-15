<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningArea;

class LearningAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $learningAreas = [
            // Core Subjects (All Grades)
            [
                'code' => 'ENG',
                'name' => 'English',
                'category' => 'CORE',
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'description' => 'English Language and Literature'
            ],
            [
                'code' => 'KIS',
                'name' => 'Kiswahili',
                'category' => 'CORE',
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'description' => 'Kiswahili Language'
            ],
            [
                'code' => 'MATH',
                'name' => 'Mathematics',
                'category' => 'STEM',
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'description' => 'Mathematics'
            ],

            // Upper Primary (Grade 4-6)
            [
                'code' => 'SCI',
                'name' => 'Science and Technology',
                'category' => 'STEM',
                'applicable_grades' => [4, 5, 6],
                'description' => 'Integrated Science and Technology'
            ],
            [
                'code' => 'SST',
                'name' => 'Social Studies',
                'category' => 'SOCIAL',
                'applicable_grades' => [4, 5, 6],
                'description' => 'Social Studies'
            ],
            [
                'code' => 'AGR',
                'name' => 'Agriculture and Nutrition',
                'category' => 'STEM',
                'applicable_grades' => [4, 5, 6],
                'description' => 'Agriculture and Nutrition'
            ],
            [
                'code' => 'CRE',
                'name' => 'Creative Arts',
                'category' => 'ARTS',
                'applicable_grades' => [4, 5, 6],
                'description' => 'Creative Arts'
            ],
            [
                'code' => 'IRE',
                'name' => 'Religious Education (IRE/CRE/HRE)',
                'category' => 'CORE',
                'applicable_grades' => [4, 5, 6, 7, 8, 9],
                'description' => 'Islamic, Christian, or Hindu Religious Education'
            ],

            // Junior Secondary (Grade 7-9)
            [
                'code' => 'INTSCI',
                'name' => 'Integrated Science',
                'category' => 'STEM',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Integrated Science'
            ],
            [
                'code' => 'PRETECH',
                'name' => 'Pre-Technical Studies',
                'category' => 'STEM',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Pre-Technical and Pre-Career Education'
            ],
            [
                'code' => 'SOCIAL',
                'name' => 'Social Studies',
                'category' => 'SOCIAL',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Social Studies'
            ],
            [
                'code' => 'BUS',
                'name' => 'Business Studies',
                'category' => 'SOCIAL',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Business Studies'
            ],
            [
                'code' => 'AGRI',
                'name' => 'Agriculture',
                'category' => 'STEM',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Agriculture'
            ],
            [
                'code' => 'HE',
                'name' => 'Home Economics',
                'category' => 'STEM',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Home Economics'
            ],
            [
                'code' => 'COMP',
                'name' => 'Computer Science',
                'category' => 'STEM',
                'applicable_grades' => [7, 8, 9],
                'description' => 'Computer Science'
            ],
            [
                'code' => 'PE',
                'name' => 'Physical Education',
                'category' => 'ARTS',
                'applicable_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'description' => 'Physical Education and Sports'
            ],
        ];

        foreach ($learningAreas as $area) {
            LearningArea::create($area);
        }

        $this->command->info('Learning areas seeded successfully!');
    }
}
