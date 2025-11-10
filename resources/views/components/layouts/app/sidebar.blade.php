<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('components.layouts.partials.head')
        @livewireStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('cms.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            @php
                function menuActive($activeRoute = []): bool {
                    // Check if route name contains the active route
                    foreach ($activeRoute as $route) {
                        if(str_contains(request()->route()->getName(), $route) || request()->routeIs($route)) {
                            return true;
                            break;
                        }
                    }

                    return false;
                }

                // Show dropdown menu if the route is active
                function showDropdown($activeRoute = []): bool {
                    foreach ($activeRoute as $route) {
                        if(str_contains(request()->route()->getName(), $route) || request()->routeIs($route)) {
                            return true;
                            break;
                        }
                    }

                    return false;
                }

                // Echo route
                function echoRoute($url) {
                    try {
                        return route($url);
                    } catch (\Exception $e) {
                        return '#';
                    }
                }

                // Check user roles
                $listMenus = [];

                // Superadmin menu
                if (auth()->user()->hasRole('superadmin')) {
                    $listMenus = \App\Models\Menu\Menu::query()
                        ->with('subMenu')
                        ->where('role_id', auth()->user()->roles->first()->id)
                        ->where('status', \App\Enums\CommonStatusEnum::ACTIVE)
                        ->orderBy('order', 'asc')
                        ->get();
                }
            @endphp
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    @foreach($listMenus as $mainMenu)
                        @if(count($mainMenu->subMenu) > 0)
                            <flux:navlist.group
                                heading="{{ $mainMenu->name }}"
                                expandable
                                :expanded="showDropdown(explode(',', $mainMenu->active_pattern))">
                                @foreach($mainMenu->subMenu as $child)
                                    <flux:navlist.item
                                        href="{{ echoRoute($child->url) }}"
                                        :active="menuActive(explode(',', $child->active_pattern))"
                                        wire:navigate>
                                        {{ $child->name }}
                                    </flux:navlist.item>
                                @endforeach
                            </flux:navlist.group>
                        @else
                            <flux:navlist.item
                                icon="{{ $mainMenu->icon }}"
                                href="{{ echoRoute($mainMenu->url) }}"
                                :active="menuActive(explode(',', $mainMenu->active_pattern))"
                                wire:navigate>
                                {{ $mainMenu->name }}
                            </flux:navlist.item>
                        @endif
                    @endforeach
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="screen-share" href="{{ url('pulse') }}" target="_blank">
                    Laravel Pulse
                </flux:navlist.item>

                <flux:navlist.item icon="scroll-text" href="{{ url('logs') }}" target="_blank">
                    Laravel Logs
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
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
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @livewireScriptConfig
        @fluxScripts
    </body>
</html>
