<?php

use App\Models\M3u\M3uSource;
use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.m3u.source.channel');

// Page title and breadcrumbs
render(function (View $view) {
    // Get m3u source
    $m3uSource = M3uSource::findOrFail(request()->get('source_id'));

    // Page title and breadcrumbs
    $title = 'M3U Channels for ' . $m3uSource->name;
    $description = 'Manage the application\'s source M3U channels from here. You can add, edit, or remove M3U channels as needed to keep your content up to date.';
    $breadcrumbs = [
        [
            'label' => 'M3U Sources',
            'url' => route('cms.m3u.source'),
        ],
        [
            'label' => 'Channels for ' . $m3uSource->name,
            'url' => null,
        ]
    ];

    $view->with(compact('title', 'description', 'breadcrumbs', 'm3uSource'));
}); ?>


<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('cms.m3u.source') }}"
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
        <livewire:cms.m3u.channel :$m3uSource />
    </div>
</x-layouts.app>
