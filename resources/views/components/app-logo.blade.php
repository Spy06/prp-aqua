@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="PRP Verification" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-indigo-600 text-white">
            <flux:icon.shield-check variant="solid" class="size-5 text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="PRP Verification" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-indigo-600 text-white">
            <flux:icon.shield-check variant="solid" class="size-5 text-white" />
        </x-slot>
    </flux:brand>
@endif
