<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Welcome') }} - {{ config('app.name', 'PRP Verification') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 flex flex-col items-center justify-center font-sans antialiased text-zinc-900 dark:text-zinc-100">
        
        <div class="max-w-md w-full px-6 py-12 bg-white dark:bg-zinc-800 shadow-sm sm:rounded-xl border border-zinc-200 dark:border-zinc-700 text-center">
            
            <div class="flex justify-center mb-6">
                <!-- Simple Logo / Icon placeholder -->
                <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <flux:icon.shield-check variant="solid" class="text-white w-8 h-8" />
                </div>
            </div>
            
            <h1 class="text-2xl font-semibold mb-2 tracking-tight">PRP Verification</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mb-8">Sistem Pelaporan dan Verifikasi Pre-Requisite Programs Plant.</p>

            <div class="flex flex-col gap-4">
                @auth
                    <flux:button variant="primary" href="{{ route('dashboard') }}" wire:navigate class="w-full justify-center">
                        Masuk ke Dashboard
                    </flux:button>
                @else
                    <flux:button variant="primary" href="{{ route('login') }}" wire:navigate class="w-full justify-center">
                        Log in
                    </flux:button>
                @endauth
            </div>

        </div>

        <div class="mt-8 text-sm text-zinc-400 dark:text-zinc-500">
            &copy; {{ date('Y') }} PRP Verification System. All rights reserved.
        </div>
        
        @fluxScripts
    </body>
</html>
