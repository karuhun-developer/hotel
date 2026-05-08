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
                    {{ $isUpdate ? 'Update' : 'Create' }} Room
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the room below.' : 'Fill in the details to create a new room.' }}
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
                <flux:label badge="Required">Room Type</flux:label>
                <flux:select wire:model="room_type_id" placeholder="Select room type ....">
                    <flux:select.option value="">-- Select Room Type --</flux:select.option>
                    @foreach ($this->roomTypes as $roomType)
                        <flux:select.option value="{{ $roomType->id }}">{{ $roomType->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="room_type_id" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">No</flux:label>
                <flux:input wire:model="no" type="text" />
                <flux:error name="no" />
            </flux:field>

            <flux:field>
                <flux:label>Guest Name</flux:label>
                <flux:input wire:model="guest_name" type="text" />
                <flux:error name="guest_name" />
            </flux:field>

            <flux:field>
                <flux:label>Greeting</flux:label>
                <flux:input wire:model="greeting" type="text" />
                <flux:error name="greeting" />
            </flux:field>

            <flux:field>
                <flux:label>Device Name</flux:label>
                <flux:input wire:model="device_name" type="text" />
                <flux:error name="device_name" />
            </flux:field>

            <flux:field>
                <flux:label>Is Birthday</flux:label>
                <flux:checkbox wire:model="is_birthday" />
                <flux:error name="is_birthday" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
