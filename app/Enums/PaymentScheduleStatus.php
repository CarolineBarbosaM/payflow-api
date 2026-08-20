<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentScheduleStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
