<?php

namespace Database\Factories;

use App\Models\SchoolSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolSettingFactory extends Factory
{
    protected $model = SchoolSetting::class;

    public function definition(): array
    {
        return [
            'school_name' => $this->faker->company() . ' School',
            'school_code' => $this->faker->unique()->bothify('SCH-####'),
            'registration_number' => $this->faker->bothify('MOE/####/####'),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'logo_path' => 'logos/school-logo.png',
            'principal_name' => $this->faker->name(),
            'is_active' => true,
        ];
    }
}
