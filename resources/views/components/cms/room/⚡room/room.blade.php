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
                    <x-loop-th :$searchBy :$paginationOrder :$paginationOrderBy />
                    <x-ui.table.th>
                        Actions
                    </x-ui.table.th>
                </tr>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($data as $d)
                    <tr>
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
                            {{ $d->guest_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->greeting }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->device_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->created_at->format('Y-m-d H:i:s') }}
                        </x-ui.table.td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if (auth()->user()->can('update' . $this->modelInstance))
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="pencil"
                                        @click="$wire.update('{{ $d->id }}')"
                                    >
                                        Edit
                                    </flux:button>
                                @endif
                                @if (auth()->user()->can('delete' . $this->modelInstance))
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        icon="trash"
                                        @click="$wire.dispatch('confirm', {
                                            function: 'delete',
                                            id: '{{ $d->id }}',
                                        })"
                                    >
                                        Delete
                                    </flux:button>
                                @endif
                            </div>
                        </td>
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


    <!-- Create / Update Modal -->
    <flux:modal
        name="defaultModal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        variant="flyout"
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">
                    {{ $isUpdate ? 'Update' : 'Create' }} Room Type Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the room type item below.' : 'Fill in the details to create a new room type item.' }}
                </flux:text>
            </div>

            @if (auth()->user()->isSuperAdmin())
                <flux:field>
                    <flux:label>Tenant</flux:label>
                    <flux:select wire:model.live="tenant_id" placeholder="Select tenant ...." wire:change="getRoomTypes">
                        <flux:select.option value="">-- Select Tenant --</flux:select.option>
                        @foreach ($tenants as $tenant)
                            <flux:select.option value="{{ $tenant->id }}">{{ $tenant->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="tenant_id" />
                </flux:field>
            @endif

            <flux:field>
                <flux:label>Room Type</flux:label>
                <flux:text>Select the room type for this room.</flux:text>
                <flux:select id="room_type_id" wire:model.live="room_type_id" placeholder="Select room type ....">
                    <flux:select.option value="">-- Select Room Type --</flux:select.option>
                    @foreach ($roomTypes as $roomType)
                        <flux:select.option value="{{ $roomType->id }}">{{ $roomType->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="room_type_id" />
            </flux:field>

            <flux:field>
                <flux:label>No</flux:label>
                <flux:text>Room number or identifier.</flux:text>
                <flux:input wire:model="no" type="text" />
                <flux:error name="no" />
            </flux:field>

            <flux:field>
                <flux:label>Greeting</flux:label>
                <flux:text>Greeting message for IPTV display.</flux:text>
                <flux:input wire:model="greeting" type="text" />
                <flux:error name="greeting" />
            </flux:field>

            <flux:field>
                <flux:label>Device Name</flux:label>
                <flux:text>Device name for IPTV.</flux:text>
                <flux:input wire:model="device_name" type="text" />
                <flux:error name="device_name" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
