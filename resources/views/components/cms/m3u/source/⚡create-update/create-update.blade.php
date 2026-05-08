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
                    {{ $isUpdate ? 'Update' : 'Create' }} M3U Source
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the M3U source below.' : 'Fill in the details to create a new M3U source.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">URL</flux:label>
                <flux:input wire:model="url" type="text" />
                <flux:error name="url" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Type</flux:label>
                <flux:select wire:model="type">
                    <flux:select.option value="GET">GET</flux:select.option>
                    <flux:select.option value="POST">POST</flux:select.option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>Headers</flux:label>
                <flux:textarea wire:model="headers" />
                <flux:error name="headers" />
            </flux:field>

            <flux:field>
                <flux:label>Body</flux:label>
                <flux:textarea wire:model="body" />
                <flux:error name="body" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Status</flux:label>
                <flux:select wire:model="status">
                    @foreach (\App\Enums\CommonStatusEnum::cases() as $statusOption)
                        <flux:select.option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</flux:select.option>
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
