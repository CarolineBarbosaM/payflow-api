<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentAttemptStatus;
use App\Models\Payment;
use App\Models\PaymentAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentAttempt>
 */
class PaymentAttemptFactory extends Factory
{
    protected $model = PaymentAttempt::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'attempt_number' => 1,
            'status' => PaymentAttemptStatus::PENDING,
            'provider' => fake()->randomElement([
                'provider_a',
                'provider_b',
            ]),
            'external_id' => null,
            'failure_code' => null,
            'failure_message' => null,
            'started_at' => null,
            'finished_at' => null,
        ];
    }
}
