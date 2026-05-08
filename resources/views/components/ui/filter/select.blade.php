@props([
    'data' => [],
    'model' => 'tenantFilter',
    'key' => 'id',
    'value' => 'name',
    'changeEvent' => '$wire.tenantBranchFilter = ""',
    'label' => 'Filter Tenant',
    'placeholder' => '-- Pilih Tenant --',
])
<p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</p>
<div class="flex items-center gap-2">
    <flux:select size="sm" wire:model.live="{{ $model }}" placeholder="Pilih Tenant" class="min-w-48" @change="{{ $changeEvent }}">
        <flux:select.option value="">{{ $placeholder }}</flux:select.option>
        @foreach ($data as $d)
            <flux:select.option value="{{ $d->{$key} }}">
                {{ $d->{$value} }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
