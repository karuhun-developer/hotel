<?php

namespace App\Traits;

use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Tenant;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

trait WithFilterTenantDateRange
{
    #[Url(as: 'tenant', except: '')]
    public $tenantFilter = '';

    #[Url(as: 'start', except: '')]
    public $startDateFilter = '';

    #[Url(as: 'end', except: '')]
    public $endDateFilter = '';

    #[Computed]
    public function tenants()
    {
        return Tenant::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    public function applyFilterTenantDateRange(Model|Builder $model, string $tenantField = 'tenant_id', string $dateField = 'created_at')
    {
        return $model
            ->when($this->tenantFilter != '', function ($query) use ($tenantField) {
                $query->where($tenantField, $this->tenantFilter);
            })
            ->when($this->startDateFilter != '', function ($query) use ($dateField) {
                $query->whereDate($dateField, '>=', $this->startDateFilter);
            })
            ->when($this->endDateFilter != '', function ($query) use ($dateField) {
                $query->whereDate($dateField, '<=', $this->endDateFilter);
            });
    }
}
