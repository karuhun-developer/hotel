@props([
    'class' => 'min-w-full divide-y divide-gray-200 dark:divide-zinc-900 text-sm',
])
<table class="{{ $class }}" {!! $attributes !!}>
    {{ $slot }}
</table>
