@if(auth()->check() && (auth()->user()->role === 'qa' || auth()->user()->isSuperAdmin()))
    <x-layouts::qa title="Lapor Temuan Saya — SIVERA">
        <livewire:switch-tampilan />
    </x-layouts::qa>
@else
    <x-layouts::app title="Beranda — SIVERA">
        <livewire:switch-tampilan />
    </x-layouts::app>
@endif
