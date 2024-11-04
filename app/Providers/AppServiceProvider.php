<?php

namespace App\Providers;

use App\Dto\ClockifyTimeEntrySynth;
use App\Dto\DaySynth;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    protected array $synthesizers = [
        DaySynth::class,
        ClockifyTimeEntrySynth::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->synthesizers as $synthesizer) {
            Livewire::propertySynthesizer($synthesizer);
        }
    }
}
