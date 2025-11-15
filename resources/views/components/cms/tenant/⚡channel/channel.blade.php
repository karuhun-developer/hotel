
<div>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Select M3U Source for Channel Listing</p>
            <div class="flex items-center gap-2">
                <flux:select size="sm" wire:model.live="filterM3uSource" placeholder="Filter by M3U Source">
                    <flux:select.option value="">All Sources</flux:select.option>
                    @foreach ($m3uSources as $source)
                        <flux:select.option value="{{ $source->id }}">{{ $source->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <flux:button
                variant="primary"
                icon="check"
                wire:click="activateAll">
                Activate All
            </flux:button>
            <flux:button
                variant="danger"
                icon="x-mark"
                wire:click="deactivateAll">
                Deactivate All
            </flux:button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded shadow-sm">
        <x-ui.table.index>
            <x-ui.table.thead>
                <tr>
                    <x-ui.table.th>
                        Source
                    </x-ui.table.th>
                    <x-ui.table.th>
                        Channel Name
                    </x-ui.table.th>
                    <x-ui.table.th>
                        URL
                    </x-ui.table.th>
                    <x-ui.table.th>
                        Status
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
                            {{ $d->source_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->tenant_channel_alias ? $d->tenant_channel_alias : $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $d->url }}
                            </span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if($d->tenant_channel_id)
                                <flux:badge color="green" size="sm">
                                    Active
                                </flux:badge>
                            @else
                                <flux:badge color="red" size="sm">
                                    Inactive
                                </flux:badge>
                            @endif
                        </x-ui.table.td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                @if (auth()->user()->can('update' . $this->modelInstance))
                                    @if($d->tenant_channel_id)
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            color="yellow"
                                            icon="pencil"
                                            wire:click="update('{{ $d->tenant_channel_id }}')"
                                        >
                                            Edit Channel
                                        </flux:button>
                                        <flux:button
                                            size="sm"
                                            variant="danger"
                                            icon="x-mark"
                                            wire:click="deactivateChannel('{{ $d->m3u_source_id }}', '{{ $d->id }}')"
                                        >
                                            Deactivate Channel
                                        </flux:button>
                                    @else
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            icon="check"
                                            wire:click="activateChannel('{{ $d->m3u_source_id }}', '{{ $d->id }}')"
                                        >
                                            Activate Channel
                                        </flux:button>
                                    @endif
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
                    {{ $isUpdate ? 'Update' : 'Create' }} Tenant Channel
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the tenant channel.' : 'Fill in the details to create a new tenant channel.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:text>The tenant channel name as it will appear to end users.</flux:text>
                <flux:input wire:model="alias" type="text" />
                <flux:error name="alias" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
