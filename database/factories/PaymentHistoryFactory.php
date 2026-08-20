<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Payment;
use App\Models\PaymentHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentHistory>
 */
class PaymentHistoryFactory extends Factory
{
    protected $model = PaymentHistory::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'event' => fake()->randomElement([
                'created',
                'processing',
                'succeeded',
                'failed',
            ]),
            'from_status' => null,
            'to_status' => null,
            'attempt_id' => null,
            'metadata' => [],
        ];
    }
}
