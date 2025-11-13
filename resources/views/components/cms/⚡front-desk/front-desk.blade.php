<div>
    <div class="flex items-center justify-between mb-4">
        @if (auth()->user()->can('create' . $this->modelInstance))
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="create"
            >
                Create
            </flux:button>
        @endif
    </div>
    <div class="flex items-center justify-between mt-5 mb-4">
        @if (auth()->user()->isSuperAdmin())
            <div class="flex items-center gap-4">
                <x-ui.filter.tenant :$tenants />
            </div>
        @endif
        @if(auth()->user()->isHotelStaff())
            <div class="flex items-center gap-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Room Type:</p>
                <div class="flex items-center gap-2">
                    <flux:select size="sm" wire:model.live="filterRoomType" placeholder="Select Room Type" class="min-w-48">
                        <flux:select.option value="">All Room Types</flux:select.option>
                        @foreach ($roomTypes as $rt)
                            <flux:select.option value="{{ $rt->id }}">{{ $rt->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
        @endif
    </div>
    <div class="flex items-center justify-between mt-5 mb-4 gap-4">
        <div class="flex items-center gap-2">
            <p class="text-sm text-gray-600">Show</p>
            <flux:select size="sm" wire:model.live.debounce="paginate" placeholder="Per Page">
                <option value="10">10 Per Page</option>
                <option value="25">25 Per Page</option>
                <option value="50">50 Per Page</option>
                <option value="100">100 Per Page</option>
            </flux:select>
        </div>

        <div class="flex items-center gap-2">
            <flux:input.group>
                <flux:input
                    size="sm"
                    icon="magnifying-glass"
                    type="text"
                    placeholder="Search ...."
                    wire:model.live.debounce="search"
                    class="max-w-xs"
                />
            </flux:input.group>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded shadow-sm">
        <x-ui.table.index>
            <x-ui.table.thead>
                <tr>
                    <x-ui.table.th>
                        Actions
                    </x-ui.table.th>
                    <x-loop-th :$searchBy :$paginationOrder :$paginationOrderBy />
                </tr>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($data as $d)
                    <tr>
                        <td class="px-4 py-3 text-gray-700 dark:text-zinc-300">
                            @if (!$d->guest_name)
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    color="yellow"
                                    icon="clock"
                                    disable="{{ $d->guest_name ? 'true' : 'false' }}"
                                    @click="$wire.checkIn({{ $d->id }})"
                                >
                                    Check In
                                </flux:button>
                            @else
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    icon="arrow-uturn-left"
                                    disable="{{ ! $d->guest_name ? 'true' : 'false' }}"
                                    @click="$wire.dispatch('confirm', {
                                        function: 'checkOut',
                                        id: '{{ $d->id }}',
                                    })"
                                >
                                    Check Out
                                </flux:button>
                            @endif
                        </td>
                        <x-ui.table.td>
                            {{ $d->tenant_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->room_type_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->no }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {!! $d->guest_name ? '<span class="text-green-600 font-semibold">' . $d->guest_name . '</span>' : '<span class="text-gray-500 italic">Empty</span>' !!}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {!! $d->is_birthday ? '<span class="text-green-600 font-semibold">Yes</span>' : '<span class="text-red-600 font-semibold">No</span>' !!}
                        </x-ui.table.td>
                    </tr>
                @empty
                    <tr>
                        <x-ui.table.td colspan="99" class="px-4 py-3 text-gray-700 dark:text-zinc-300 text-center">No results found.</x-ui.table.td>
                    </tr>
                @endforelse
            </x-ui.table.tbody>
        </x-ui.table.index>
    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>

    <!-- Check in/out Modal -->
    <flux:modal
        name="defaultModal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        variant="default"
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">
                    Check In Room {{ $roomData?->roomType->name }} - {{ $roomData?->no }}
                </flux:heading>
                <flux:text class="mt-2">
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Guest Name</flux:label>
                <flux:text>Guest name to show on the IPTV display.</flux:text>
                <flux:input wire:model="guest_name" type="text" />
                <flux:error name="guest_name" />
            </flux:field>

            <flux:field>
                <flux:label>Is Birthday</flux:label>
                <flux:text>Is the guest celebrating a birthday?</flux:text>
                <flux:select wire:model="is_birthday" >
                    <flux:select.option value="0">No</flux:select.option>
                    <flux:select.option value="1">Yes</flux:select.option>
                </flux:select>
                <flux:error name="is_birthday" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
