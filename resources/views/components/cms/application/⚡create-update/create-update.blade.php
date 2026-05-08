<div>
    <!-- Create / Update Modal -->
    <flux:modal
        name="defaultModal"
        class="max-w-2xl md:min-w-2xl"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">
                    {{ $isUpdate ? 'Update' : 'Create' }} Application
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the application below.' : 'Fill in the details to create a new application.' }}
                </flux:text>
            </div>

            @if (auth()->user()->isSuperAdmin())
                <flux:field>
                    <flux:label badge="Required">Tenant</flux:label>
                    <flux:select wire:model.live="tenant_id" placeholder="Select tenant ....">
                        <flux:select.option value="">-- Select Tenant --</flux:select.option>
                        @foreach ($this->tenants as $tenant)
                            <flux:select.option value="{{ $tenant->id }}">{{ $tenant->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="tenant_id" />
                </flux:field>
            @endif

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Package Name</flux:label>
                <flux:input wire:model="package_name" type="text" />
                <flux:error name="package_name" />
            </flux:field>

            <flux:field>
                <flux:label>Image</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$image" :form_file="$record?->getFirstMediaUrl('image')" />
                <x-file-upload model="image" accept="image/*" />
                <flux:error name="image" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
