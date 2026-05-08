<div>
    <div class="flex items-center justify-between mb-4">
        @can('create' . $this->modelInstance)
            <flux:button
                variant="primary"
                icon="plus"
                @click="
                    $flux.modal('defaultModal').show();
                    $wire.dispatch('set-action');
                "
            >
                Create
            </flux:button>
        @endcan
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

    <flux:table :paginate="$data" class="min-w-full">
        <flux:table.columns>
            <flux:table.column>Actions</flux:table.column>
            <x-loop-th :$searchBy :$paginationOrder :$paginationOrderBy />
            <flux:table.column>Total Channels</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse($data as $d)
                <flux:table.row>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down" size="sm">Options</flux:button>
                            <flux:menu>
                                @can('update' . $this->modelInstance)
                                    <flux:menu.item
                                        variant="default"
                                        icon="pencil"
                                        @click="
                                            $flux.modal('defaultModal').show();
                                            $wire.dispatch('set-action', {
                                                id: '{{ $d->id }}',
                                            });
                                        ">
                                        Update
                                    </flux:menu.item>
                                @endcan
                                <flux:menu.item
                                    variant="default"
                                    icon="tv"
                                    href="{{ route('cms.m3u.channel') }}?source_id={{ $d->id }}">
                                    Channels
                                </flux:menu.item>
                                @can('update' . $this->modelInstance)
                                    <flux:menu.item
                                        variant="default"
                                        icon="arrow-path"
                                        @click="$wire.dispatch('confirm', {
                                            function: 'fetchChannels',
                                            id: '{{ $d->id }}',
                                            title: 'Fetch Channels',
                                            message: 'Fetch channels from this M3U source? This will call the API and sync channels.',
                                        })">
                                        Fetch Channels
                                    </flux:menu.item>
                                @endcan
                                @can('delete' . $this->modelInstance)
                                    <flux:menu.item
                                        variant="danger"
                                        icon="trash"
                                        @click="$wire.dispatch('confirm', {
                                                function: 'delete',
                                                id: '{{ $d->id }}',
                                        })">
                                        Delete
                                    </flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                    <flux:table.cell>{{ $d->name }}</flux:table.cell>
                    <flux:table.cell>{{ Str::limit($d->url, 50) }}</flux:table.cell>
                    <flux:table.cell>{{ $d->type }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $d->status->color() }}" size="sm">{{ $d->status->label() }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $d->created_at->format('Y-m-d H:i:s') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="blue" size="sm">{{ $d->channels_count }}</flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="999" align="center" variant="strong">
                        No data found.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <livewire:cms.m3u.source.create-update lazy />
</div>
