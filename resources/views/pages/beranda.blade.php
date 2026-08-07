@php
    $isPicUser = auth()->check() && auth()->user()->role === 'karyawan' && (
        \App\Models\Temuan::where('pic_id', auth()->id())->exists() ||
        \App\Models\Karyawan::where('nik', auth()->user()->nik)->where('status_aktif', true)->exists()
    );
@endphp
@if(auth()->check() && (auth()->user()->role === 'qa' || auth()->user()->isSuperAdmin() || $isPicUser))
    <x-layouts::qa title="Lapor & Temuan PIC — SIVERA">
        <livewire:switch-tampilan />
    </x-layouts::qa>
@else
    <x-layouts::app title="Beranda — SIVERA">
        <livewire:switch-tampilan />
    </x-layouts::app>
@endif
