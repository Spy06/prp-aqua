@if(auth()->check() && (auth()->user()->role === 'qa' || auth()->user()->isSuperAdmin()))
    <x-layouts::qa :title="'Detail Temuan #' . $temuan->id . ' — SIVERA'">
        <livewire:detail-temuan :temuan="$temuan" />
    </x-layouts::qa>
@else
    <x-layouts::app :title="'Detail Temuan #' . $temuan->id . ' — SIVERA'">
        <livewire:detail-temuan :temuan="$temuan" />
    </x-layouts::app>
@endif
