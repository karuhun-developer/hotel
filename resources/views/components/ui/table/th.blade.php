@props([
    'class' => 'px-4 py-3 text-left font-medium text-gray-700 dark:text-zinc-300 w-32',
])
<th class="{{ $class }}" {!! $attributes !!}>
    {{ $slot }}
</th>
