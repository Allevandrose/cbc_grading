<?php

namespace Database\Seeders;

use App\Models\LearningArea;
use App\Models\PathwayCluster;
use App\Models\SubjectPathwayWeight;
use Illuminate\Database\Seeder;

class SubjectPathwayWeightSeeder extends Seeder
{
    public function run(): void
    {
        $stem = PathwayCluster::where('code', 'STEM')->first();
        $social = PathwayCluster::where('code', 'SOCIAL')->first();
        $arts = PathwayCluster::where('code', 'ARTS')->first();

        $subjects = LearningArea::all();

        $weights = [
            // STEM weights
            ['subject_code' => 'MATH', 'cluster' => $stem, 'weight' => 2.0],
            ['subject_code' => 'SCI', 'cluster' => $stem, 'weight' => 2.0],
            ['subject_code' => 'INTSCI', 'cluster' => $stem, 'weight' => 2.0],
            ['subject_code' => 'PRETECH', 'cluster' => $stem, 'weight' => 1.8],
            ['subject_code' => 'AGRI', 'cluster' => $stem, 'weight' => 1.5],
            ['subject_code' => 'COMP', 'cluster' => $stem, 'weight' => 1.8],

            // Social Sciences weights
            ['subject_code' => 'ENG', 'cluster' => $social, 'weight' => 2.0],
            ['subject_code' => 'KIS', 'cluster' => $social, 'weight' => 2.0],
            ['subject_code' => 'SST', 'cluster' => $social, 'weight' => 2.0],
            ['subject_code' => 'SOCIAL', 'cluster' => $social, 'weight' => 2.0],
            ['subject_code' => 'BUS', 'cluster' => $social, 'weight' => 1.8],
            ['subject_code' => 'CRE', 'cluster' => $social, 'weight' => 1.5],

            // Arts weights
            ['subject_code' => 'CRE', 'cluster' => $arts, 'weight' => 2.0],
            ['subject_code' => 'PE', 'cluster' => $arts, 'weight' => 2.0],
            ['subject_code' => 'HE', 'cluster' => $arts, 'weight' => 1.5],
        ];

        foreach ($weights as $weight) {
            $subject = $subjects->where('code', $weight['subject_code'])->first();
            if ($subject) {
                SubjectPathwayWeight::create([
                    'learning_area_id' => $subject->id,
                    'pathway_cluster_id' => $weight['cluster']->id,
                    'weight' => $weight['weight'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Subject pathway weights seeded successfully!');
    }
}
