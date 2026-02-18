<?php

namespace Database\Factories;

use App\Models\PathwayCluster;
use Illuminate\Database\Eloquent\Factories\Factory;

class PathwayClusterFactory extends Factory
{
    protected $model = PathwayCluster::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomElement(['STEM', 'SOCIAL', 'ARTS']),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'career_opportunities' => json_encode($this->faker->words(5)),
            'is_active' => true,
        ];
    }
}
