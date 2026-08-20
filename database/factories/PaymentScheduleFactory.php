<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentScheduleFrequency;
use App\Enums\PaymentScheduleStatus;
use App\Enums\PaymentScheduleType;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentSchedule>
 */
class PaymentScheduleFactory extends Factory
{
    protected $model = PaymentSchedule::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'type' => PaymentScheduleType::ONE_TIME,
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'frequency' => null,
            'interval' => null,
            'day_of_month' => null,
            'timezone' => 'America/Sao_Paulo',
            'status' => PaymentScheduleStatus::ACTIVE,
            'ends_at' => null,
        ];
    }
}
