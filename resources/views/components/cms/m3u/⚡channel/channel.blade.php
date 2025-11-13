<div>
    <div class="flex items-center gap-3 mt-5 mb-4">
        <div class="flex items-center gap-4">
            <flux:button
                size="sm"
                variant="primary"
                color="yellow"
                icon="paper-airplane"
                @click="$wire.callApi()"
            >
                Call API
            </flux:button>
        </div>
        <div class="flex items-center gap-4">
            <flux:button
                size="sm"
                variant="primary"
                icon="check"
            >
                Activate All Channels
            </flux:button>
        </div>
        <div class="flex items-center gap-4">
            <flux:button
                size="sm"
                variant="primary"
                color="red"
                icon="x-mark"
            >
                Deactivate All Channels
            </flux:button>
        </div>
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
                        Image
                    </x-ui.table.th>
                    <x-ui.table.th>
                        Actions
                    </x-ui.table.th>
                </tr>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($data as $d)
                    <tr>
                        <x-ui.table.td>
                            {{ $d->alias ? $d->alias : $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->url }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <flux:badge color="{{ $d->status->color() }}" size="sm">
                                {{ $d->status->label() }}
                            </flux:badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if ($d->getFirstMediaUrl('image'))
                                <img
                                    src="{{ $d->getFirstMediaUrl('image') }}"
                                    alt="{{ $d->name }}"
                                    class="w-16 h-16 object-cover rounded"
                                />
                            @else
                                <span class="text-gray-500 italic">No Image</span>
                            @endif
                        </x-ui.table.td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if (auth()->user()->can('update' . $this->modelInstance))
                                    @if ($d->status == \App\Enums\CommonStatusEnum::ACTIVE)
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            color="red"
                                            icon="x-mark"
                                            @click="$wire.dispatch('confirm', {
                                                function: 'toggleStatus',
                                                id: '{{ $d->id }}',
                                            })"
                                        >
                                            Deactivate
                                        </flux:button>
                                    @else
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            color="green"
                                            icon="check"
                                            @click="$wire.dispatch('confirm', {
                                                function: 'toggleStatus',
                                                id: '{{ $d->id }}',
                                            })"
                                        >
                                            Activate
                                        </flux:button>
                                    @endif
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="pencil"
                                        @click="$wire.update('{{ $d->id }}')"
                                    >
                                        Edit
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
                    {{ $isUpdate ? 'Update' : 'Create' }} M3U Source Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the M3U source item.' : 'Fill in the details to create a new M3U source item.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:text>The m3u source name.</flux:text>
                <flux:input wire:model="alias" type="text" />
                <flux:error name="alias" />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>
                <flux:text>Select the status of the m3u source.</flux:text>
                <flux:select wire:model="status">
                    @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="status" />
            </flux:field>


            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
