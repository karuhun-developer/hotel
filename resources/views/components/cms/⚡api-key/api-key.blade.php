<div>
    <div class="flex items-center justify-between mb-4">
        @if (auth()->user()->can('create' . $this->modelInstance))
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="create"
            >
                Generate Api Key
            </flux:button>
        @endif
    </div>

    @if($latestApiKey)
        <div class="bg-green-400/20 dark:bg-green-400/40 text-green-800! dark:text-green-200 p-4 rounded-lg mb-4 flex items-center justify-between">
            <div class="flex-1">
                <p x-ref="apikey" class="font-mono text-sm break-all text-bold">
                    {{ $latestApiKey }}
                </p>
            </div>
            <flux:button
                variant="primary"
                icon="clipboard"
                size="sm"
                class="ml-4"
                x-on:click="
                    navigator.clipboard.writeText($refs.apikey.innerText);
                    window.Toast.fire({
                        icon: 'success',
                        title: 'API Key copied to clipboard.'
                    })
                "
            >
                Copy API Key
            </flux:button>
        </div>
    @endif
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded shadow-sm">
        <x-ui.table.index>
            <x-ui.table.thead>
                <tr>
                    <x-ui.table.th>
                        Name
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
                            {{ $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <flux:badge color="{{ $d->status->color() }}" size="sm">
                                {{ $d->status->label() }}
                            </flux:badge>
                        </x-ui.table.td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if (auth()->user()->can('update' . $this->modelInstance))
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="arrow-path"
                                        @click="$wire.dispatch('confirm', {
                                            function: 'regenerateApiKey',
                                            id: '{{ $d->id }}',
                                        })"
                                    >
                                        Regenerate
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


    <!-- Create / Update Modal -->
    <flux:modal
        name="defaultModal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        variant="flyout"
    >
        <form class="space-y-6" wire:submit.prevent="generateApiKey">
            <div>
                <flux:heading size="lg">
                    {{ $isUpdate ? 'Update' : 'Create' }} API KEY
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the Api Key below.' : 'Fill in the details to create a new Api Key.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
