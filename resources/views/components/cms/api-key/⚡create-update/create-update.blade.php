<div>
    <flux:modal
        name="defaultModal"
        class="max-w-md md:min-w-md"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">Generate API Key</flux:heading>
                <flux:text class="mt-2">
                    Fill in the name to generate a new API Key.
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Generate</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
