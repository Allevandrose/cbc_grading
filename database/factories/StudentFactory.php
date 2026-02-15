<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName();
        $grade = $this->faker->numberBetween(1, 9);

        return [
            'upi_number' => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1) . $this->faker->unique()->numberBetween(10000000, 99999999)),
            'admission_number' => 'ADM' . $this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $firstName,
            'middle_name' => $this->faker->optional(0.3)->firstName(),
            'last_name' => $lastName,
            'date_of_birth' => Carbon::now()->subYears(6 + ($grade - 1))->subMonths($this->faker->numberBetween(1, 11)),
            'gender' => $gender,
            'birth_certificate_number' => 'B' . $this->faker->unique()->numberBetween(10000000, 99999999),
            'nationality' => 'Kenyan',
            'phone' => '07' . $this->faker->numberBetween(10000000, 99999999),
            'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
            'address' => 'P.O. Box ' . $this->faker->numberBetween(100, 999) . '-' . $this->faker->numberBetween(100, 999),
            'current_grade_level' => $grade,
            'current_class' => $grade . $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'enrollment_year' => Carbon::now()->year - ($grade - 1),
            'graduation_year' => null,
            'parent_name' => $this->faker->name(),
            'parent_phone' => '07' . $this->faker->numberBetween(10000000, 99999999),
            'parent_email' => $this->faker->email(),
            'parent_relationship' => $this->faker->randomElement(['Father', 'Mother', 'Guardian']),
            'is_active' => true,
            'is_graduated' => false,
        ];
    }

    /**
     * Indicate that the student is in a specific grade.
     */
    public function inGrade(int $grade): static
    {
        return $this->state(fn(array $attributes) => [
            'current_grade_level' => $grade,
            'current_class' => $grade . $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'enrollment_year' => Carbon::now()->year - ($grade - 1),
        ]);
    }

    /**
     * Indicate that the student is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the student is graduated.
     */
    public function graduated(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_graduated' => true,
            'graduation_year' => Carbon::now()->subYear()->year,
        ]);
    }
}
