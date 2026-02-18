<?php

namespace Database\Factories;

use App\Models\SubjectPathwayWeight;
use App\Models\LearningArea;
use App\Models\PathwayCluster;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectPathwayWeightFactory extends Factory
{
    protected $model = SubjectPathwayWeight::class;

    public function definition(): array
    {
        return [
            'learning_area_id' => LearningArea::factory(),
            'pathway_cluster_id' => PathwayCluster::factory(),
            'weight' => $this->faker->randomFloat(2, 0.5, 3.0),
            'is_active' => true,
        ];
    }
}
