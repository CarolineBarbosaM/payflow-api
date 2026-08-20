<?php

declare(strict_types=1);

namespace App\Enums;

enum MerchantStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
