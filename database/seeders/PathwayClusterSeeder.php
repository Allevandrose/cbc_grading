<?php

namespace Database\Seeders;

use App\Models\PathwayCluster;
use Illuminate\Database\Seeder;

class PathwayClusterSeeder extends Seeder
{
    public function run(): void
    {
        $clusters = [
            [
                'code' => 'STEM',
                'name' => 'Science, Technology, Engineering and Mathematics',
                'description' => 'For learners with strengths in mathematics and sciences. Leads to careers in engineering, medicine, computer science, and related fields.',
                'career_opportunities' => json_encode([
                    'Engineering',
                    'Medicine',
                    'Computer Science',
                    'Architecture',
                    'Laboratory Technology',
                    'Actuarial Science',
                ]),
            ],
            [
                'code' => 'SOCIAL',
                'name' => 'Social Sciences',
                'description' => 'For learners with strengths in humanities and languages. Leads to careers in law, education, business, and social services.',
                'career_opportunities' => json_encode([
                    'Law',
                    'Teaching',
                    'Business Administration',
                    'Economics',
                    'Psychology',
                    'Journalism',
                ]),
            ],
            [
                'code' => 'ARTS',
                'name' => 'Arts and Sports Science',
                'description' => 'For learners with talents in creative arts and sports. Leads to careers in performing arts, design, sports management, and entertainment.',
                'career_opportunities' => json_encode([
                    'Performing Arts',
                    'Graphic Design',
                    'Sports Management',
                    'Music Production',
                    'Fashion Design',
                    'Physical Education',
                ]),
            ],
        ];

        foreach ($clusters as $cluster) {
            PathwayCluster::create($cluster);
        }

        $this->command->info('Pathway clusters seeded successfully!');
    }
}
