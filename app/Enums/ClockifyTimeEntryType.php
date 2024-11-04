<?php

namespace App\Enums;

enum ClockifyTimeEntryType: string
{
    case Regular = 'REGULAR';
    case Break = 'BREAK';

    public static function getDefault(): self
    {
        return self::Regular;
    }
}
