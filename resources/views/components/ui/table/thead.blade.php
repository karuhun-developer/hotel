@props([
    'class' => 'bg-gray-50 dark:bg-zinc-900'
])
<thead class="{{ $class }}" {!! $attributes !!}>
    {{ $slot }}
</thead>
