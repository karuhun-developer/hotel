<div>
    <flux:modal name="profileModal" class="max-w-2xl md:min-w-2xl" flyout>
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">Tenant Profile</flux:heading>
                <flux:text class="mt-2">Update the tenant profile details below.</flux:text>
            </div>

            <flux:field>
                <flux:label>Running Text</flux:label>
                <flux:text>Text that runs across the top of the tenant's display.</flux:text>
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
                <flux:textarea wire:model="description" rows="3" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Welcome Text</flux:label>
                <flux:text>Welcome message for guests.</flux:text>
                <flux:textarea wire:model="welcome_text" rows="3" />
                <flux:error name="welcome_text" />
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
                <x-file-upload model="main_photo" accept="image/*" />
                <flux:error name="main_photo" />
            </flux:field>

            <flux:field>
                <flux:label>Background Photo</flux:label>
                <flux:text>Max size 50MB.</flux:text>
                <x-file-preview :file="$background_photo" :form_file="$profile?->getFirstMediaUrl('background_photo')" />
                <x-file-upload model="background_photo" accept="image/*" />
                <flux:error name="background_photo" />
            </flux:field>

            <flux:field>
                <flux:label>Intro Video</flux:label>
                <flux:text>Max size 100MB.</flux:text>
                <x-file-preview :file="$intro_video" :form_file="$profile?->getFirstMediaUrl('intro_video')" type="video" />
                <x-file-upload model="intro_video" accept="video/*" />
                <flux:error name="intro_video" />
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save Profile</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
