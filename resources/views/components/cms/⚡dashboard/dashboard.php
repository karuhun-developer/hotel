<?php

use App\Enums\TenantTypeEnum;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use App\Models\Tenant\Room;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function totalHotels()
    {
        return Tenant::where('type', TenantTypeEnum::HOTEL)->count();
    }

    #[Computed]
    public function totalHospitals()
    {
        return Tenant::where('type', TenantTypeEnum::HOSPITAL)->count();
    }

    #[Computed]
    public function totalM3uSources()
    {
        return M3uSource::count();
    }

    #[Computed]
    public function totalM3uChannels()
    {
        return M3uChannel::count();
    }

    #[Computed]
    public function totalUsers()
    {
        if (auth()->user()->isSuperAdmin()) {
            return User::count();
        }

        return User::whereHas('tenant', function ($query) {
            $query->where('tenant_id', auth()->user()->tenant?->tenant_id);
        })->count();
    }

    #[Computed]
    public function totalRooms()
    {
        if (auth()->user()->isSuperAdmin()) {
            return Room::count();
        }

        return Room::where('tenant_id', auth()->user()->tenant?->tenant_id)->count();
    }
};
