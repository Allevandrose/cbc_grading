<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(5000, 50000);

        return [
            'student_id' => Student::factory(),
            'fee_structure_id' => FeeStructure::factory(),
            'receipt_number' => $this->faker->unique()->bothify('RCP-####-####'),
            'amount_paid' => $amount,
            'balance' => $this->faker->numberBetween(0, 20000),
            'payment_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'payment_method' => $this->faker->randomElement(['Cash', 'M-Pesa', 'Bank Transfer', 'Cheque']),
            'transaction_reference' => $this->faker->bothify('TRX-########'),
            'notes' => $this->faker->optional()->sentence(),
            'recorded_by' => User::where('role', 'accountant')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'accountant']),
            'is_verified' => $this->faker->boolean(80),
        ];
    }
}
