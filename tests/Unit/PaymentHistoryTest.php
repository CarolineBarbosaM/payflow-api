<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\PaymentAttempt;
use App\Models\PaymentHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_belongs_to_payment(): void
    {
        $payment = Payment::factory()->create();

        $history = PaymentHistory::factory()
            ->for($payment)
            ->create();

        $this->assertTrue($history->payment->is($payment));
    }

    public function test_history_can_reference_an_attempt(): void
    {
        $payment = Payment::factory()->create();
        $attempt = PaymentAttempt::factory()
            ->for($payment)
            ->create();

        $history = PaymentHistory::factory()
            ->for($payment)
            ->create([
                'attempt_id' => $attempt->id,
            ]);

        $this->assertTrue($history->attempt->is($attempt));
    }
}
