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
                            {{ $d->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <flux:badge color="{{ $d->type->color() }}" size="sm">
                                {{ $d->type->label() }}
                            </flux:badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->branch }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->address }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->phone }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->email }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->website }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->default_greeting ?? 'N/A' }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $d->password_setting ?? 'N/A' }}
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
                                        color="yellow"
                                        icon="pencil"
                                        @click="$wire.getProfile('{{ $d->id }}')"
                                    >
                                        Profile
                                    </flux:button>
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
                    {{ $isUpdate ? 'Update' : 'Create' }} Tenant Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the tenant item below.' : 'Fill in the details to create a new tenant item.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Type</flux:label>
                <flux:text> Tenant type either 'hotel' or 'hospital'</flux:text>
                <flux:select wire:model="type" placeholder="Tenant type..">
                    <flux:select.option value="">-- Select Tenant Type --</flux:select.option>
                    @foreach (\App\Enums\TenantTypeEnum::cases() as $type)
                        <flux:select.option value="{{ $type->value }}">{{ $type->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>Branch</flux:label>
                <flux:text> Tenant branch name e.g., 'New York Branch'</flux:text>
                <flux:input wire:model="branch" type="text" />
                <flux:error name="branch" />
            </flux:field>

            <flux:field>
                <flux:label>Address</flux:label>
                <flux:text> Full address of the tenant</flux:text>
                <flux:textarea wire:model="address" />
                <flux:error name="address" />
            </flux:field>

            <flux:field>
                <flux:label>Phone</flux:label>
                <flux:text> Contact phone number e.g., '+1-234-567-8900'</flux:text>
                <flux:input wire:model="phone" type="text" />
                <flux:error name="phone" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:text> Contact email address e.g., 'example@example.com'</flux:text>
                <flux:input wire:model="email" type="email" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Website</flux:label>
                <flux:input wire:model="website" type="text" />
                <flux:error name="website" />
            </flux:field>

            <flux:field>
                <flux:label>Default Greeting</flux:label>
                <flux:input wire:model="default_greeting" type="text" />
                <flux:error name="default_greeting" />
            </flux:field>

            <flux:field>
                <flux:label>Password Setting</flux:label>
                <flux:input wire:model="password_setting" type="text" />
                <flux:error name="password_setting" />
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

    <!-- Tenant Profile Modal -->
    <flux:modal
        name="profileModal"
        class="max-w-2xl md:min-w-2xl"
        @close="closeProfileModal"
        variant="flyout"
    >
        <form class="space-y-6" wire:submit.prevent="submitProfile">
            <div>
                <flux:heading size="lg">
                    Tenant Profile {{ $tenant?->name ?? '' }}
                </flux:heading>
                <flux:text class="mt-2">
                    Update the profile details of the tenant below.
                </flux:text>
            </div>

            <flux:field>
                <flux:label>Tenant</flux:label>
                <flux:text>Profile for tenant item.</flux:text>
                <flux:input value="{{ $tenant?->name }}" type="text" disabled />
            </flux:field>

            <flux:field>
                <flux:label>Running Text</flux:label>
                <flux:text>Text that runs across the top of the tenant's website.</flux:text>
                <flux:input wire:model="running_text" type="text" />
                <flux:error name="running_text" />
            </flux:field>

            <flux:field>
                <flux:label>Primary Color</flux:label>
                <flux:text>Hex code for the primary color e.g., #FF5733</flux:text>
                <flux:input wire:model="primary_color" type="text" placeholder="#FFFFFF" />
                <flux:error name="primary_color" />
            </flux:field>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:text>Brief description of the tenant.</flux:text>
                <x-trix-editor id="id_trix_description" model_name="description" :model="$description" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Logo Color</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$logo_color" :form_file="$profile?->getFirstMediaUrl('logo_color')" />
                <x-file-upload model="logo_color" accept="image/*" />
                <flux:error name="logo_color" />
            </flux:field>

            <flux:field>
                <flux:label>Logo White</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$logo_white" :form_file="$profile?->getFirstMediaUrl('logo_white')" />
                <x-file-upload model="logo_white" accept="image/*" />
                <flux:error name="logo_white" />
            </flux:field>

            <flux:field>
                <flux:label>Logo Black</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$logo_black" :form_file="$profile?->getFirstMediaUrl('logo_black')" />
                <x-file-upload model="logo_black" accept="image/*" />
                <flux:error name="logo_black" />
            </flux:field>

            <flux:field>
                <flux:label>Main Photo</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$main_photo" :form_file="$profile?->getFirstMediaUrl('main_photo')" />
                <x:file-upload model="main_photo" accept="image/*" />
                <flux:error name="main_photo" />
            </flux:field>

            <flux:field>
                <flux:label>Background Photo</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$background_photo" :form_file="$profile?->getFirstMediaUrl('background_photo')" />
                <x:file-upload model="background_photo" accept="image/*" />
                <flux:error name="background_photo" />
            </flux:field>

            <flux:field>
                <flux:label>Intro Video</flux:label>
                <flux:text>Max size 100MB.</flux:text>
                <x-file-preview :file="$intro_video" :form_file="$profile?->getFirstMediaUrl('intro_video')" type="video" />
                <x:file-upload model="intro_video" accept="video/*" />
                <flux:error name="intro_video" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>

        </form>
    </flux:modal>
</div>
