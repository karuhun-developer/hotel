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
                        Total Channels
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
                            {{ $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->url }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->type }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <flux:badge color="{{ $d->status->color() }}" size="sm">
                                {{ $d->status->label() }}
                            </flux:badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->created_at->format('Y-m-d H:i:s') }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->channels_count }}
                        </x-ui.table.td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if (auth()->user()->can('validate' . $this->modelInstance))
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        color="yellow"
                                        icon="tv"
                                        href="{{ route('cms.m3u.source.channel') }}?source_id={{ $d->id }}"
                                    >
                                        Channels
                                    </flux:button>
                                @endif
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
                    {{ $isUpdate ? 'Update' : 'Create' }} M3U Source Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the M3U source item.' : 'Fill in the details to create a new M3U source item.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:text>The m3u source name.</flux:text>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>URL</flux:label>
                <flux:text>The m3u source URL.</flux:text>
                <flux:input wire:model="url" type="text" />
                <flux:error name="url" />
            </flux:field>

            <flux:field>
                <flux:label>Type</flux:label>
                <flux:text>POST/GET method to fetch the m3u source.</flux:text>
                <flux:select wire:model="type">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>Headers</flux:label>
                <flux:text>Optional headers for the request (JSON format).</flux:text>
                <flux:textarea wire:model="headers" rows="3" />
                <flux:error name="headers" />
            </flux:field>

            <flux:field>
                <flux:label>Body</flux:label>
                <flux:text>Optional body for POST requests (JSON format).</flux:text>
                <flux:textarea wire:model="body" rows="3" />
                <flux:error name="body" />
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
