<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Merchant;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchantTest extends TestCase
{
    use RefreshDatabase;

    public function test_merchant_can_have_many_payments(): void
    {
        $merchant = Merchant::factory()->create();

        Payment::factory()
            ->count(2)
            ->for($merchant)
            ->create();

        $this->assertCount(2, $merchant->payments);
    }

    public function test_merchant_generates_uuidv7(): void
    {
        $merchant = Merchant::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $merchant->id
        );
    }
}
