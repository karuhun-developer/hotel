<div>
    @if(auth()->user()->isSuperAdmin())
        {{-- Superadmin Dashboard --}}
        <div class="grid gap-4 md:grid-cols-4 mb-6">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                        <flux:icon.building-office class="size-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Hotels</p>
                        <p class="text-2xl font-bold">{{ $this->totalHotels }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900">
                        <flux:icon.building-library class="size-5 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Hospitals</p>
                        <p class="text-2xl font-bold">{{ $this->totalHospitals }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                        <flux:icon.tv class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">M3U Sources</p>
                        <p class="text-2xl font-bold">{{ $this->totalM3uSources }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                        <flux:icon.signal class="size-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">M3U Channels</p>
                        <p class="text-2xl font-bold">{{ $this->totalM3uChannels }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Hotel Admin / Receptionist Dashboard --}}
        <div class="grid gap-4 md:grid-cols-2 mb-6">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                        <flux:icon.users class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold">{{ $this->totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                        <flux:icon.home class="size-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Rooms</p>
                        <p class="text-2xl font-bold">{{ $this->totalRooms }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Front Desk --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-semibold mb-4">Front Desk</h3>
            <livewire:cms.front-desk.table />
        </div>
    @endif
</div>
