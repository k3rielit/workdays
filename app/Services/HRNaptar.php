<?php

namespace App\Services;

use App\Dto\Day;
use App\Enums\DayType;
use App\Enums\Month;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Collection as DomCollection;
use PHPHtmlParser\Dom\HtmlNode;

/**
 * @property int $year The current year by default.
 * @property Collection<Day> $days Every day in the given year.
 */
class HRNaptar
{
    public int $year;
    public Collection $days;

    public function __construct()
    {
        $this->year = Carbon::now()->year;
        $this->days = collect();
        $this->load();
    }

    public static function make(): static
    {
        return new static();
    }

    public function year(int $year): static
    {
        $this->year = $year;
        $this->load();
        return $this;
    }

    public function getUrl(): string
    {
        return "www.hrportal.hu/munkaido-{$this->year}.html";
    }

    public function getCacheKey(): string
    {
        return "hrnaptar-{$this->year}";
    }

    public function load(bool $forceScrape = false): static
    {
        $key = $this->getCacheKey();
        $ttl = now()->addHours(24);
        if ($forceScrape === true) {
            Cache::put($key, $this->scrape(), $ttl);
        }
        $this->days = Cache::remember($key, $ttl, fn() => $this->scrape());
        return $this;
    }

    public function scrape(): Collection
    {
        /**
         * Load the DOM from the URL.
         */
        $days = collect();
        $dom = new Dom;
        try {
            $dom->loadFromUrl($this->getUrl());
        } catch (Throwable) {
            return $days;
        }

        /**
         * Parse the DOM.
         * @var DomCollection<HtmlNode> $monthNodes
         * @var HtmlNode $monthNode
         * @var DomCollection<HtmlNode> $dayNodes
         * @var HtmlNode $dayNode
         */
        $monthNodes = $dom->find('div.calbox');
        if (!$monthNodes instanceof DomCollection) {
            return $days;
        }
        foreach ($monthNodes as $index => $monthNode) {
            $monthEnum = Month::tryFrom($index + 1);
            $dayNodes = $monthNode->find('.caltr > div');
            foreach ($dayNodes as $dayNode) {
                $dayNumber = intval(trim($dayNode->text()));
                $dayType = DayType::fromHRNaptar($dayNode->getAttribute('class') ?? '');
                if ($dayNumber > 0 && $dayType instanceof DayType) {
                    $days->push(Day::make($dayType, $monthEnum, $dayNumber));
                }
            }
        }

        return $days;
    }
}
