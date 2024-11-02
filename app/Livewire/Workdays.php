<?php

namespace App\Livewire;

use App\Services\HRNaptar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Workdays extends Component
{
    public int $year;
    public Collection $days;

    public function mount()
    {
        $this->days = collect();
        $this->year = Carbon::now()->year;
        $this->refreshHRNaptar();
    }

    public function doSth(): void
    {
        //
    }

    public function refreshHRNaptar()
    {
        $hr = HRNaptar::make();
        $this->year = $hr->year;
        $this->days = $hr->days;
    }

    public function render()
    {
        return view('livewire.workdays');
    }
}
