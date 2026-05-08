<div>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Source</p>
            <flux:select size="sm" wire:model.live="filterM3uSource" placeholder="Filter Source">
                <option value="">All Sources</option>
                @foreach ($this->m3uSources as $source)
                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                @endforeach
            </flux:select>
        </div>
        <div class="flex items-center gap-2">
            @can('update' . $this->modelInstance)
                <flux:button variant="primary" icon="check" wire:click="activateAll">
                    Activate All
                </flux:button>
                <flux:button variant="danger" icon="x-mark" wire:click="deactivateAll">
                    Deactivate All
                </flux:button>
            @endcan
        </div>
    </div>

    <flux:table class="min-w-full">
        <flux:table.columns>
            <flux:table.column>Source</flux:table.column>
            <flux:table.column>Channel Name</flux:table.column>
            <flux:table.column>URL</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse($data as $d)
                <flux:table.row>
                    <flux:table.cell>{{ $d->source_name }}</flux:table.cell>
                    <flux:table.cell>{{ $d->tenant_channel_alias ?? $d->name }}</flux:table.cell>
                    <flux:table.cell>
                        <span class="text-sm text-gray-500">{{ Str::limit($d->url, 40) }}</span>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($d->tenant_channel_id)
                            <flux:badge color="green" size="sm">Active</flux:badge>
                        @else
                            <flux:badge color="red" size="sm">Inactive</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @can('update' . $this->modelInstance)
                            @if($d->tenant_channel_id)
                                <div class="flex items-center gap-2">
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="pencil"
                                        @click="
                                            $flux.modal('aliasModal').show();
                                            $wire.dispatch('set-action', { id: '{{ $d->tenant_channel_id }}' });
                                        "
                                    >
                                        Alias
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        icon="x-mark"
                                        wire:click="deactivateChannel('{{ $d->m3u_source_id }}', '{{ $d->id }}')"
                                    >
                                        Deactivate
                                    </flux:button>
                                </div>
                            @else
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    icon="check"
                                    wire:click="activateChannel('{{ $d->m3u_source_id }}', '{{ $d->id }}')"
                                >
                                    Activate
                                </flux:button>
                            @endif
                        @endcan
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="999" align="center" variant="strong">
                        No channels found.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <!-- Alias Edit Modal -->
    <flux:modal name="aliasModal" class="max-w-md md:min-w-md" flyout>
        <form class="space-y-6" wire:submit.prevent="submitAlias">
            <div>
                <flux:heading size="lg">Edit Channel Alias</flux:heading>
                <flux:text class="mt-2">Set a custom display name for this channel.</flux:text>
            </div>

            <flux:field>
                <flux:label>Alias</flux:label>
                <flux:text>The channel name as it will appear to end users.</flux:text>
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
