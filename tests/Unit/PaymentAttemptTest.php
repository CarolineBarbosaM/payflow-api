<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\PaymentAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAttemptTest extends TestCase
{
    use RefreshDatabase;

    public function test_attempt_belongs_to_payment(): void
    {
        $payment = Payment::factory()->create();

        $attempt = PaymentAttempt::factory()
            ->for($payment)
            ->create();

        $this->assertTrue($attempt->payment->is($payment));
    }

    public function test_attempt_generates_uuidv7(): void
    {
        $attempt = PaymentAttempt::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $attempt->id
        );
    }
}
