<x-filament-panels::page>
    <div wire:ignore>
        <div id="calendar" data-events='@json($events)'></div>
    </div>

    @push('scripts')
        @vite('resources/js/calendar.js')
    @endpush
</x-filament-panels::page>
