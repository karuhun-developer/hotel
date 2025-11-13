<?php

use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.m3u.source');

// Page title and breadcrumbs
render(function (View $view) {
    // Page title and breadcrumbs
    $title = 'M3U Sources';
    $description = 'Manage the application\'s source M3U playlists from here. You can add, edit, or remove M3U sources as needed to keep your content up to date.';
    $breadcrumbs = [
        [
            'label' => 'M3U Sources',
            'url' => null,
        ],
    ];

    $view->with(compact('title', 'description', 'breadcrumbs'));
}); ?>


<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-3xl font-bold">{{ $title }}</h1>
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
        <livewire:cms.m3u.source />
    </div>
</x-layouts.app>
