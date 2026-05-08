<?php

use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.content.content-item');

render(function (View $view) {
    $title = 'Content Item Management';
    $description = 'Manage the content items here.';
    $breadcrumbs = [
        ['label' => 'Content', 'url' => '#'],
        ['label' => 'Content Items', 'url' => null],
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
            <flux:text>{{ $description }}</flux:text>
        </div>
        <livewire:cms.content.content-item.table />
    </div>
</x-layouts.app>
