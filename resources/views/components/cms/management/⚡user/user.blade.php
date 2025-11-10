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
                            {{ $d->role }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->email }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->email_verified_at?->format('Y-m-d H:i:s') ?? 'N/A' }}
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

                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        color="orange"
                                        icon="key"
                                        @click="$wire.changePassword('{{ $d->id }}')"
                                    >
                                        Change Password
                                    </flux:button>
                                @endif
                                @if (auth()->user()->can('validate' . $this->modelInstance))
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        color="yellow"
                                        icon="envelope-open"
                                        @click="$wire.dispatch('confirm', {
                                            function: 'verifyEmail',
                                            id: '{{ $d->id }}',
                                        })"
                                    >
                                        Validate Email
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
                    {{ $isUpdate ? 'Update' : 'Create' }} User Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the user item below.' : 'Fill in the details to create a new user item.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Role</flux:label>
                <flux:select wire:model="role" placeholder="Select role ....">
                    <flux:select.option value="">-- Select Role --</flux:select.option>
                    @foreach ($roles as $role)
                        <flux:select.option value="{{ $role->name }}">{{ $role->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="role" />
            </flux:field>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" />
                <flux:error name="email" />
            </flux:field>

            @if (!$isUpdate)
                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input wire:model="password" type="password" />
                    <flux:error name="password" />
                </flux:field>
            @endif

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Change Password Modal -->
    <flux:modal
        name="changePasswordModal"
        class="max-w-md md:min-w-md"
        @close="closeModal('changePasswordModal')"
        variant="flyout"
    >
        <form class="space-y-6" wire:submit.prevent="changePasswordSubmit">
            <div>
                <flux:heading size="lg">
                    Change User Password
                </flux:heading>
                <flux:text class="mt-2">
                    Fill in the new password for the user item below.
                </flux:text>
            </div>

            <flux:field>
                <flux:label>New Password</flux:label>
                <flux:input wire:model="password" type="password" />
                <flux:error name="password" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Change Password</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
