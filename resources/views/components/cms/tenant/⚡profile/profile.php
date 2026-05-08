<?php

use App\Actions\Cms\Tenant\UpdateTenantProfileAction;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantProfile;
use App\Traits\WithMediaCollection;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads, WithMediaCollection;

    public $modelInstance = Tenant::class;

    public $tenant_id;

    public ?TenantProfile $profile = null;

    public $running_text;

    public $primary_color;

    public $description;

    public $welcome_text;

    public $logo_color;

    public $logo_white;

    public $logo_black;

    public $main_photo;

    public $background_photo;

    public $intro_video;

    #[On('show-profile')]
    public function showProfile($id)
    {
        $this->resetProfileData();

        $tenant = Tenant::with('profile')->findOrFail($id);
        $this->tenant_id = $tenant->id;
        $this->profile = $tenant->profile;

        if ($tenant->profile) {
            $this->running_text = $tenant->profile->running_text;
            $this->primary_color = $tenant->profile->primary_color;
            $this->description = $tenant->profile->description;
            $this->welcome_text = $tenant->profile->welcome_text;
        }

        Flux::modal('profileModal')->show();
    }

    public function resetProfileData()
    {
        $this->reset([
            'tenant_id', 'running_text', 'primary_color', 'description', 'welcome_text',
            'logo_color', 'logo_white', 'logo_black', 'main_photo', 'background_photo', 'intro_video',
        ]);
        $this->profile = null;
    }

    public function submit(UpdateTenantProfileAction $action)
    {
        Gate::authorize('update'.$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'running_text' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:2000',
            'welcome_text' => 'nullable|string|max:2000',
            'logo_color' => 'nullable|image:allow_svg|max:51200',
            'logo_white' => 'nullable|image:allow_svg|max:51200',
            'logo_black' => 'nullable|image:allow_svg|max:51200',
            'main_photo' => 'nullable|image:allow_svg|max:51200',
            'background_photo' => 'nullable|image:allow_svg|max:51200',
            'intro_video' => 'nullable|file|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv|max:102400',
        ]);

        $tenant = Tenant::findOrFail($this->tenant_id);
        $profile = $action->handle($tenant, $this->only(['running_text', 'primary_color', 'description', 'welcome_text']));

        // Handle file uploads
        $mediaFields = ['logo_color', 'logo_white', 'logo_black', 'main_photo', 'background_photo', 'intro_video'];
        foreach ($mediaFields as $field) {
            if ($this->{$field} instanceof TemporaryUploadedFile) {
                $this->saveMedia(model: $profile, file: $this->{$field}, collection: $field);
            }
        }

        // Reset file inputs
        $this->reset(['logo_color', 'logo_white', 'logo_black', 'main_photo', 'background_photo', 'intro_video']);

        $this->dispatch('toast', type: 'success', message: 'Tenant profile saved successfully.');
        Flux::modal('profileModal')->close();
    }
};
