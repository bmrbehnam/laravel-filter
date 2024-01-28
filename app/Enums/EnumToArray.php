<?php

namespace App\Enums;

trait EnumToArray
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
