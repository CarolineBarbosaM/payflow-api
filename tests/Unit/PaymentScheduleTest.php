<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\PaymentSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_belongs_to_payment(): void
    {
        $payment = Payment::factory()->create();

        $schedule = PaymentSchedule::factory()
            ->for($payment)
            ->create();

        $this->assertTrue($schedule->payment->is($payment));
    }

    public function test_schedule_generates_uuidv7(): void
    {
        $schedule = PaymentSchedule::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $schedule->id
        );
    }
}
