@props([
    'searchBy',
    'paginationOrderBy',
    'paginationOrder',
])
@foreach($searchBy as $th)
    @if(!isset($th['hide']))
        <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-zinc-300 w-32" wire:click="changeOrder('{{ $th['field'] }}')">
            @if($paginationOrderBy === $th['field'])
                @if($paginationOrder === 'asc')
                    <span class="ml-2 text-gray-500">↑</span>
                @else
                    <span class="ml-2 text-gray-500">↓</span>
                @endif
            @endif
            {{ $th['name'] }}
        </th>
    @endif
@endforeach
