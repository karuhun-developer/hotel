@props([
    'class' => 'px-4 py-3 text-gray-700 dark:text-zinc-300',
])
<td class="{{ $class }}" {!! $attributes !!}>
    {{ $slot }}
</td>
