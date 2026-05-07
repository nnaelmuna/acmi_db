@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-8 flex items-center justify-center gap-3 pb-5">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="text-gray-300">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="text-gray-800 hover:text-acmi-blueprimer">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($paginator->linkCollection() as $link)
            @if ($link['label'] == '&laquo; Previous' || $link['label'] == 'Next &raquo;')
                @continue
            @endif

            @if ($link['url'] === null)
                <span class="px-1 text-gray-500">...</span>
            @elseif ($link['active'])
                <span
                    class="flex h-9 min-w-9 items-center justify-center rounded-full bg-gray-200 px-3 text-sm font-medium text-gray-800">
                    {{ $link['label'] }}
                </span>
            @else
                <a href="{{ $link['url'] }}"
                    class="flex h-9 min-w-9 items-center justify-center rounded-full border border-gray-900 px-3 text-sm font-medium text-gray-900 hover:bg-gray-100">
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="text-gray-800 hover:text-acmi-blueprimer">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @else
            <span class="text-gray-300">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        @endif

    </div>
@endif