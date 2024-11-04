<?php

namespace App\Dto;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class ClockifyTimeEntrySynth extends Synth
{
    public static $key = 'clockify_time_entry_dto';

    static function match($target)
    {
        return $target instanceof ClockifyTimeEntry;
    }

    public function dehydrate(ClockifyTimeEntry $target)
    {
        return [$target->toArray(), []];
    }

    public function hydrate($value)
    {
        if (is_array($value)) {
            return ClockifyTimeEntry::fromArray($value);
        }
        return is_array($value) ? ClockifyTimeEntry::fromArray($value) : null;
    }

    public function get(&$target, $key)
    {
        return $target?->{$key};
    }

    public function set(&$target, $key, $value)
    {
        if ($target instanceof ClockifyTimeEntry) {
            // https://livewire.laravel.com/docs/synthesizers#supporting-data-binding
        }
    }
}
