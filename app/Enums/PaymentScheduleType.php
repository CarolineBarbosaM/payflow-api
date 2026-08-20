<?php

namespace App\Enums;

enum PaymentScheduleType: string
{
    case ONE_TIME = 'one_time';
    case RECURRING = 'recurring';
}
