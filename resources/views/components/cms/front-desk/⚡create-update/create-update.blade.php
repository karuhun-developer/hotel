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
                    {{ $isUpdate ? 'Update' : 'Create' }} Front Desk
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the front desk below.' : 'Fill in the details to create a new front desk.' }}
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
                <flux:label badge="Required">Room</flux:label>
                <flux:select wire:model="room_id" placeholder="Select room ....">
                    <flux:select.option value="">-- Select Room --</flux:select.option>
                    @foreach ($this->rooms as $room)
                        <flux:select.option value="{{ $room->id }}">{{ $room->no }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="room_id" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Guest Name</flux:label>
                <flux:input wire:model="guest_name" type="text" />
                <flux:error name="guest_name" />
            </flux:field>

            <flux:field>
                <flux:label>Check In</flux:label>
                <flux:input wire:model="check_in" type="datetime-local" />
                <flux:error name="check_in" />
            </flux:field>

            <flux:field>
                <flux:label>Check Out</flux:label>
                <flux:input wire:model="check_out" type="datetime-local" />
                <flux:error name="check_out" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
