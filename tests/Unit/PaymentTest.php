<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Merchant;
use App\Models\Payment;
use App\Models\PaymentAttempt;
use App\Models\PaymentHistory;
use App\Models\PaymentSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_belongs_to_merchant(): void
    {
        $merchant = Merchant::factory()->create();
        $payment = Payment::factory()
            ->for($merchant)
            ->create();

        $this->assertTrue($payment->merchant->is($merchant));
    }

    public function test_payment_can_have_many_attempts(): void
    {
        $payment = Payment::factory()->create();

        PaymentAttempt::factory()
            ->count(2)
            ->for($payment)
            ->sequence(
                ['attempt_number' => 1],
                ['attempt_number' => 2],
            )
            ->create();

        $this->assertCount(2, $payment->attempts);
    }

    public function test_payment_can_have_many_histories(): void
    {
        $payment = Payment::factory()->create();

        PaymentHistory::factory()
            ->count(2)
            ->for($payment)
            ->create();

        $this->assertCount(2, $payment->histories);
    }

    public function test_payment_can_have_one_schedule(): void
    {
        $payment = Payment::factory()->create();

        PaymentSchedule::factory()
            ->for($payment)
            ->create();

        $this->assertTrue($payment->schedule->is(PaymentSchedule::query()->first()));
    }
}
