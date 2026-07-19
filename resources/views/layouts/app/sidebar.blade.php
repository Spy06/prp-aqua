<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                @auth
                    @if(auth()->user()->role === 'qa')
                        {{-- Menu QA --}}
                        <flux:sidebar.group :heading="__('QA Dashboard')" class="grid">
                            <flux:sidebar.item icon="chart-bar" :href="route('qa.dashboard')" :current="request()->routeIs('qa.dashboard')" wire:navigate>
                                Grafik Temuan
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="list-bullet" :href="route('qa.daftar-temuan')" :current="request()->routeIs('qa.daftar-temuan')" wire:navigate>
                                Daftar Temuan
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="calendar-days" :href="route('qa.rekap')" :current="request()->routeIs('qa.rekap')" wire:navigate>
                                Rekap Periode
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                        <flux:sidebar.group :heading="__('Master Data')" class="grid">
                            <flux:sidebar.item icon="users" :href="route('qa.master.karyawan')" :current="request()->routeIs('qa.master.karyawan')" wire:navigate>
                                Karyawan
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="building-office" :href="route('qa.master.departemen')" :current="request()->routeIs('qa.master.departemen')" wire:navigate>
                                Departemen
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="document-check" :href="route('qa.master.klausul')" :current="request()->routeIs('qa.master.klausul')" wire:navigate>
                                Klausul PRP
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="user-circle" :href="route('qa.master.akun')" :current="request()->routeIs('qa.master.akun')" wire:navigate>
                                Akun User
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @else
                        {{-- Menu Karyawan --}}
                        <flux:sidebar.group :heading="__('Menu')" class="grid">
                            <flux:sidebar.item icon="home" :href="route('beranda')" :current="request()->routeIs('beranda')" wire:navigate>
                                Beranda
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif
                @endauth
            </flux:sidebar.nav>


            <flux:spacer />



            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
