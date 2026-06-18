@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-8 flex items-center justify-center gap-4 pb-5">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="text-gray-300 pointer-events-none px-2">
                <i class="fa-solid fa-chevron-left text-sm font-bold"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="text-black hover:text-acmi-blueprimer px-2 transition duration-200">
                <i class="fa-solid fa-chevron-left text-sm font-bold"></i>
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
                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-acmi-blueprimer bg-[#E0E0E0] text-sm font-semibold text-black">
                    {{ $link['label'] }}
                </span>
            @else
                <a href="{{ $link['url'] }}"
                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-acmi-blueprimer bg-white text-sm font-semibold text-black hover:bg-gray-50 transition duration-200">
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="text-black hover:text-acmi-blueprimer px-2 transition duration-200">
                <i class="fa-solid fa-chevron-right text-sm font-bold"></i>
            </a>
        @else
            <span class="text-gray-300 pointer-events-none px-2">
                <i class="fa-solid fa-chevron-right text-sm font-bold"></i>
            </span>
        @endif

    </div>
@endif

