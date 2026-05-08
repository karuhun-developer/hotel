<div>
    <flux:modal name="defaultModal" class="max-w-2xl md:min-w-2xl" flyout>
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">{{ $isUpdate ? 'Update' : 'Create' }} Tenant</flux:heading>
                <flux:text class="mt-2">{{ $isUpdate ? 'Update the details of the tenant below.' : 'Fill in the details to create a new tenant.' }}</flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Type</flux:label>
                <flux:select wire:model="type">
                    @foreach(\App\Enums\TenantTypeEnum::cases() as $t)
                        <flux:select.option value="{{ $t->value }}">{{ $t->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Branch</flux:label>
                <flux:input wire:model="branch" type="text" />
                <flux:error name="branch" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Address</flux:label>
                <flux:textarea wire:model="address" />
                <flux:error name="address" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Phone</flux:label>
                <flux:input wire:model="phone" type="text" />
                <flux:error name="phone" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input wire:model="email" type="email" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Website</flux:label>
                <flux:input wire:model="website" type="url" />
                <flux:error name="website" />
            </flux:field>

            <flux:field>
                <flux:label>Default Greeting</flux:label>
                <flux:textarea wire:model="default_greeting" />
                <flux:error name="default_greeting" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Password Setting</flux:label>
                <flux:input wire:model="password_setting" type="text" />
                <flux:error name="password_setting" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Status</flux:label>
                <flux:select wire:model="status">
                    @foreach(\App\Enums\CommonStatusEnum::cases() as $s)
                        <flux:select.option value="{{ $s->value }}">{{ $s->label() }}</flux:select.option>
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
