<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MerchantStatus;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Merchant>
 */
class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'external_id' => fake()->unique()->uuid(),
            'status' => MerchantStatus::ACTIVE,
        ];
    }
}
