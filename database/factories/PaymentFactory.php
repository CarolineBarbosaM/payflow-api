<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Merchant;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'merchant_id' => Merchant::factory(),
            'external_id' => fake()->unique()->uuid(),
            'amount' => fake()->randomFloat(2, 1, 10000),
            'currency' => 'BRL',
            'status' => PaymentStatus::PENDING,
            'scheduled_at' => null,
        ];
    }
}
