<?php

namespace App\Enums;

enum OrderStatus: string
{
    use EnumToArray;
    case PENDING = 'pending';
    case PAID = 'paid';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
}
