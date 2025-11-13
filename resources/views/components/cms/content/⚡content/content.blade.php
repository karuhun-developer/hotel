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
    @if (auth()->user()->isSuperAdmin())
        <div class="flex items-center justify-between mt-5 mb-4">
            <div class="flex items-center gap-4">
                <x-ui.filter.tenant :$tenants />
            </div>
        </div>
    @endif
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
                            {{ $d->tenant_name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->version }}
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
                    {{ $isUpdate ? 'Update' : 'Create' }} Content
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the content below.' : 'Fill in the details to create a new content.' }}
                </flux:text>
            </div>

            @if (auth()->user()->isSuperAdmin())
                <flux:field>
                    <flux:label>Tenant</flux:label>
                    <flux:select wire:model.live="tenant_id" placeholder="Select tenant ....">
                        <flux:select.option value="">-- Select Tenant --</flux:select.option>
                        @foreach ($tenants as $tenant)
                            <flux:select.option value="{{ $tenant->id }}">{{ $tenant->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="tenant_id" />
                </flux:field>
            @endif

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:text>Content name for identification.</flux:text>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Image</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$image" :form_file="$oldData?->getFirstMediaUrl('image')" />
                <x-file-upload model="image" accept="image/*" />
                <flux:error name="image" />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model="status" placeholder="Select Status">
                    <flux:select.option value="">-- Select Status --</flux:select.option>
                    @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                        <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
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
