<?php

namespace App\Enums;

enum DayType: string
{
    case Holiday = 'holiday';
    case MovedRestDay = 'moved_rest_day';
    case MovedWorkday = 'moved_workday';
    case Workday = 'workday';
    case RestDay = 'rest_day';

    public function isWorkday(): bool
    {
        return in_array($this, [
            self::Workday,
            self::MovedWorkday,
        ]);
    }

    public function isRestday(): bool
    {
        return !$this->isWorkday();
    }

    public function getLabel(): string|null
    {
        return match ($this) {
            self::Holiday => 'Munkaszüneti nap',
            self::MovedRestDay => 'Áthelyezett pihenőnap',
            self::MovedWorkday => 'Áthelyezett munkanap',
            self::Workday => 'Munkanap',
            self::RestDay => 'Heti pihenőnap',
            default => null,
        };
    }

    public function getBackgroundColor(bool $lighter = false): string
    {
        return match ($this) {
            self::Holiday => $lighter ? 'bg-rose-400' : 'bg-rose-600',
            self::MovedRestDay => $lighter ? 'bg-yellow-300' : 'bg-yellow-400',
            self::MovedWorkday => $lighter ? 'bg-slate-400' : 'bg-slate-600',
            self::Workday => $lighter ? 'bg-slate-100' : 'bg-slate-200',
            self::RestDay => $lighter ? 'bg-violet-400' : 'bg-violet-600',
            default => null,
        };
    }

    /**
     * Used to parse the raw HTML from HRNaptarhttps://www.hrportal.hu/munkaido-2024.html
     * @param string $name A class, indicating the type of day.
     * @return self|null
     */
    public static function fromHRNaptar(string $name): self|null
    {
        return match ($name) {
            'caltdred' => self::Holiday,
            'caltdsarga' => self::MovedRestDay,
            'caltdszurke' => self::MovedWorkday,
            'caltd' => self::Workday,
            'caltdb' => self::RestDay,
            default => null,
        };
    }
}
