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
                    {{ $isUpdate ? 'Update' : 'Create' }} M3U Channel
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the M3U channel below.' : 'Fill in the details to create a new M3U channel.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Source</flux:label>
                <flux:select wire:model="m3u_source_id" placeholder="Select source ....">
                    <flux:select.option value="">-- Select Source --</flux:select.option>
                    @foreach ($this->sources as $source)
                        <flux:select.option value="{{ $source->id }}">{{ $source->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="m3u_source_id" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Alias</flux:label>
                <flux:input wire:model="alias" type="text" />
                <flux:error name="alias" />
            </flux:field>

            <flux:field>
                <flux:label badge="Required">URL</flux:label>
                <flux:input wire:model="url" type="text" />
                <flux:error name="url" />
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
