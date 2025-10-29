@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo();
    
    // Debug sementara
    // dd($brandName, $brandLogo);
@endphp

{{-- Debug visual --}}
{{-- <div class="text-red-500 text-xs">
    Name: {{ $brandName ?? 'NULL' }}, 
    Logo: {{ $brandLogo ?? 'NULL' }}
</div> --}}

<div class="flex items-center gap-3">
    @if ($brandLogo)
        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="h-8 w-auto" />
    @endif

    @if ($brandName)
        <span class="text-xl font-bold">{{ $brandName }}</span>
    @endif
</div>