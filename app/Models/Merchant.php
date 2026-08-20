<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MerchantStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'external_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => MerchantStatus::class,
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
