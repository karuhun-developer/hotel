@props([
    'tenants' => [],
    'model' => 'tenantFilter',
])
<p class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Tenant:</p>
<div class="flex items-center gap-2">
    <flux:select size="sm" wire:model.live="{{ $model }}" placeholder="Select Tenant" class="min-w-48">
        <flux:select.option value="">All Tenants</flux:select.option>
        @foreach ($tenants as $tenant)
            <flux:select.option value="{{ $tenant->id }}">
                {{ $tenant->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
