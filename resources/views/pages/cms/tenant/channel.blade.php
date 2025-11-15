<?php

use App\Models\Tenant\Tenant;
use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.tenant.channel');

// Page title and breadcrumbs
render(function (View $view) {
    // Get tenant
    $tenant = Tenant::findOrFail(request()->get('tenant_id'));

    // Page title and breadcrumbs
    $title = 'Enabled Channels for ' . $tenant->name;
    $description = 'Manage the enabled channels for the tenant "' . $tenant->name . '". You can add or remove channels as needed to control where this tenant\'s content is available.';

    $breadcrumbs = [
        [
            'label' => 'Tenants',
            'url' => route('cms.tenant.index')
        ],
        [
            'label' => 'Enabled Channels',
            'url' => null
        ]
    ];

    $view->with(compact('title', 'description', 'breadcrumbs', 'tenant'));
}); ?>

<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('cms.tenant.index') }}"
                    size="sm"
                    variant="primary"
                    icon="arrow-left"

                />
                <h1 class="text-3xl font-bold">{{ $title }}</h1>
            </div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('cms.dashboard') }}" icon="home" />
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb['url'])
                        <flux:breadcrumbs.item href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item>{{ $breadcrumb['label'] }}</flux:breadcrumbs.item>
                    @endif
                @endforeach
            </flux:breadcrumbs>
        </div>
        <div class="border-gray-200 mb-6">
            <flux:text>
                {{ $description }}
            </flux:text>
        </div>
        <livewire:cms.tenant.channel :$tenant />
    </div>
</x-layouts.app>
