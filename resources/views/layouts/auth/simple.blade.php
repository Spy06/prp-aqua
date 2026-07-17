<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 font-sans antialiased text-zinc-900 dark:text-zinc-100 flex flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full max-w-[384px] flex-col gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium mb-6" wire:navigate>
                <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <flux:icon.shield-check variant="solid" class="text-white w-8 h-8" />
                </div>
                <span class="text-xl font-semibold tracking-tight text-center mt-2">{{ config('app.name', 'PRP Verification') }}</span>
            </a>
            <div class="bg-white dark:bg-zinc-800 p-8 shadow-sm sm:rounded-xl border border-zinc-200 dark:border-zinc-700 flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
