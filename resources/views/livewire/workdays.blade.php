<div class="flex flex-col gap-5 p-5 w-full">

    {{--  Settings  --}}
    <div class="flex gap-5 w-full">
        <input class="border border-slate-500 p-1 rounded" type="number" name="year" id="year" wire:model="year">
        <select class="border border-slate-500 p-1 rounded" name="month" id="month" wire:model="month">
            <option>All</option>
            @foreach($months as $month)
                <option value="{{ $month->value }}">{{ $month->getLabel() }}</option>
            @endforeach
        </select>
        <button class="border-2 border-slate-400 bg-slate-200 px-2 rounded" wire:click="generate">Generate</button>
        <button class="border-2 border-red-400 bg-red-200 px-2 rounded" wire:click="store">Store</button>
    </div>

    {{--  Clockify editor  --}}
    <div class="flex flex-col gap-1 w-full">
        @foreach($clockifies as $clockify)
            <span class="w-full">
                {{ json_encode($clockify->toArray()) }}
            </span>
        @endforeach
    </div>

    {{--  Calendar preview  --}}
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

</div>
