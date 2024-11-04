<?php

namespace App\Livewire;

use App\Dto\ClockifyTimeEntry;
use App\Dto\Day;
use App\Enums\Month;
use App\Services\HRNaptar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Workdays extends Component
{
    public int $year;
    public Collection $months;
    public int|null $month = null;
    public Collection $days;
    public Collection $clockifies;

    public function mount()
    {
        $this->months = collect(Month::cases());
        $this->year = Carbon::now()->year;
        $this->days = collect();
        $this->clockifies = collect();
        $this->generate();
    }

    public function generate()
    {
        $this->days = HRNaptar::make()->year($this->year)->getDays(Month::tryFrom($this->month));
        $this->clockifies = $this->days->filter(fn(Day $day) => $day->type->isWorkday())->map(function (Day $day) {
            return ClockifyTimeEntry::make()
                ->setStart(Carbon::today()->setYear($this->year)->setMonth($this->month)->setDay($day->day)->setHour(8))
                ->setEnd(Carbon::today()->setYear($this->year)->setMonth($this->month)->setDay($day->day)->setHour(16));
        });
    }

    public function store()
    {
        $this->clockifies->each(fn(ClockifyTimeEntry $dto) => $dto->store());
    }

    public function render()
    {
        return view('livewire.workdays');
    }
}
