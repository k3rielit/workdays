<?php

namespace App\Dto;

use App\Enums\DayType;
use App\Enums\Month;

class Day
{
    public DayType $type;
    public Month $month;
    public int $day;

    public function __construct(DayType $type, Month $month, int $day)
    {
        $this->type = $type;
        $this->month = $month;
        $this->day = $day;
    }

    public static function make(DayType $type, Month $month, int $day): static
    {
        return new static($type, $month, $day);
    }
}
