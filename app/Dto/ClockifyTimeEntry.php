<?php

namespace App\Dto;

use App\Enums\ClockifyTimeEntryType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * https://docs.clockify.me/#tag/Time-entry
 */
class ClockifyTimeEntry
{
    public bool $billable = true;
    public array $customAttributes = [];
    public array $customFields = [];
    public string $description = 'Documents module';
    public Carbon|null $end = null;
    public string|null $projectId = null;
    public Carbon|null $start = null;
    public array $tagIds = [];
    public string|null $taskId = null;
    public ClockifyTimeEntryType $type;

    public function __construct()
    {
        $this->type = ClockifyTimeEntryType::getDefault();
        $this->projectId = config('clockify.project_id');
    }

    public static function make(): static
    {
        return new static();
    }

    public function setBillable(bool|null $billable): static
    {
        $this->billable = $billable ?? true;
        return $this;
    }

    public function setCustomAttributes(array|null $customAttributes): static
    {
        $this->customAttributes = $customAttributes ?? [];
        return $this;
    }

    public function setCustomFields(array|null $customFields): static
    {
        $this->customFields = $customFields ?? [];
        return $this;
    }

    public function setDescription(string|null $description): static
    {
        $this->description = $description ?? '';
        return $this;
    }

    public function setEnd(Carbon|string|null $end): static
    {
        $this->end = match (true) {
            $end instanceof Carbon => $end,
            strlen($end) > 0 => Carbon::parse($end),
            default => null,
        };
        return $this;
    }

    public function setProjectId(string|null $projectId): static
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function setStart(Carbon|string|null $start): static
    {
        $this->start = match (true) {
            $start instanceof Carbon => $start,
            strlen($start) > 0 => Carbon::parse($start),
            default => null,
        };
        return $this;
    }

    public function setTagIds(array|null $tagIds): static
    {
        $this->tagIds = $tagIds ?? [];
        return $this;
    }

    public function setTaskId(string|null $taskId): static
    {
        $this->taskId = $taskId;
        return $this;
    }

    public function setType(ClockifyTimeEntryType|string|null $type): static
    {
        $this->type = match (true) {
            $type instanceof ClockifyTimeEntryType => $type,
            strlen($type) > 0 => ClockifyTimeEntryType::tryFrom($type) ?? $this->type,
            default => $this->type,
        };
        return $this;
    }

    public function toArray(): array
    {
        /**
         * Required fields
         */
        $result = [
            'billable' => $this->billable,
            'start' => $this->start?->toISOString(),
            'end' => $this->end?->toISOString(),
            'description' => $this->description,
            'type' => $this->type->value,
        ];

        /**
         * Optional fields
         */
        if ($this->projectId) {
            $result['projectId'] = $this->projectId;
        }
        if ($this->taskId) {
            $result['taskId'] = $this->taskId;
        }
        if (!empty($this->customAttributes)) {
            $result['customAttributes'] = $this->customAttributes;
        }
        if (!empty($this->customFields)) {
            $result['customFields'] = $this->customFields;
        }
        if (!empty($this->tagIds)) {
            $result['tagIds'] = $this->tagIds;
        }
        return $result;
    }

    public static function fromArray(array $state): static
    {
        return static::make()
            ->setBillable($state['billable'] ?? null)
            ->setCustomAttributes($state['customAttributes'] ?? null)
            ->setCustomFields($state['customFields'] ?? null)
            ->setDescription($state['description'] ?? null)
            ->setEnd($state['end'] ?? null)
            ->setProjectId($state['projectId'] ?? null)
            ->setStart($state['start'] ?? null)
            ->setTagIds($state['tagIds'] ?? null)
            ->setTaskId($state['taskId'] ?? null)
            ->setType($state['type'] ?? null);
    }

    /**
     * Store this entry using Clockify-s API.
     */
    public function store()
    {
        $workspace = config('clockify.workspace_id');
        $token = config('clockify.api_token');
        $url = config('clockify.url');
        if ($workspace && $token && $url) {
            $response = Http::withHeader('x-api-key', $token)->post("{$url}/v1/workspaces/{$workspace}/time-entries", $this->toArray());
        }
    }
}
