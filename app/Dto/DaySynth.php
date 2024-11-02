<?php

namespace App\Dto;

use App\Enums\DayType;
use App\Enums\Month;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class DaySynth extends Synth
{
    public static $key = 'day_dto';

    static function match($target)
    {
        return $target instanceof Day;
    }

    public function dehydrate(Day $target)
    {
        return [[
            'type' => $target->type->value,
            'month' => $target->month->value,
            'day' => $target->day,
        ], []];
    }

    public function hydrate($value)
    {
        $type = DayType::tryFrom($value['type'] ?? '');
        $month = Month::tryFrom($value['month'] ?? 0);
        $day = intval($value['day'] ?? 0);
        return $day > 0 && $type instanceof DayType ? Day::make($type, $month, $day) : null;
    }
}
