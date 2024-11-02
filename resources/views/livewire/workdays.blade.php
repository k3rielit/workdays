<div class="grid grid-cols-7 gap-1 p-1">
    @forelse($days as $day)
        <div class="w-full flex gap-1 {{ $day->type->getBackgroundColor(true) }}">
            <div class="{{ $day->type->getBackgroundColor() }} w-1"></div>
            <span class="font-bold">
                {{ $year }}.{{ $day->month->value }}.{{ $day->day }}
            </span>
            <span>{{ $day->type->getLabel() }}</span>
        </div>
    @empty
        <p>Me? Ha ha, nothing, just hangin' around...</p>
    @endforelse
</div>
