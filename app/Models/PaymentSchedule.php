<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentScheduleFrequency;
use App\Enums\PaymentScheduleStatus;
use App\Enums\PaymentScheduleType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentSchedule extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'payment_id',
        'type',
        'scheduled_at',
        'frequency',
        'interval',
        'day_of_month',
        'timezone',
        'status',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => PaymentScheduleType::class,
            'scheduled_at' => 'datetime',
            'frequency' => PaymentScheduleFrequency::class,
            'status' => PaymentScheduleStatus::class,
            'ends_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
