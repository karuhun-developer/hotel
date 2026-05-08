<?php

use function Laravel\Folio\name;

name('cms.dashboard');

?>

<x-layouts.app :title="__('Dashboard')">
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-3xl font-bold">Dashboard</h1>
        </div>
        <livewire:cms.dashboard />
    </div>
</x-layouts.app>
