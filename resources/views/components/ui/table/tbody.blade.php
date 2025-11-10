@props([
    'class' => 'bg-white dark:bg-zinc-900 divide-y divide-gray-100 dark:divide-zinc-800',
])
<tbody class="{{ $class }}" {!! $attributes !!}>
    {{ $slot }}
</tbody>
