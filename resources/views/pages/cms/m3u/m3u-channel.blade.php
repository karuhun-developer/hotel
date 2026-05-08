<?php

use App\Models\M3u\M3uSource;
use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.m3u.channel');

render(function (View $view) {
    $m3uSource = null;
    $title = 'M3U Channel Management';
    $description = 'Manage the M3U channels here.';

    if (request()->has('source_id')) {
        $m3uSource = M3uSource::findOrFail(request()->get('source_id'));
        $title = 'M3U Channels for ' . $m3uSource->name;
        $description = 'Manage channels from this M3U source. You can edit aliases, toggle status, or manage channel images.';
    }

    $breadcrumbs = [
        ['label' => 'M3U', 'url' => '#'],
        ['label' => 'Sources', 'url' => route('cms.m3u.index')],
        ['label' => $m3uSource ? 'Channels for ' . $m3uSource->name : 'All Channels', 'url' => null],
    ];

    $view->with(compact('title', 'description', 'breadcrumbs', 'm3uSource'));
}); ?>

<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('cms.m3u.index') }}"
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
            <flux:text>{{ $description }}</flux:text>
        </div>
        <livewire:cms.m3u.channel.table :sourceId="$m3uSource?->id" />
    </div>
</x-layouts.app>
