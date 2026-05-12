@props([
    'categories' => [],
    'routeName' => '',
])

<div class="relative" id="filterDropdownWrapper">
    <button type="button" onclick="toggleFilterDropdown()"
        class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
        <span>{{ request('category') ?? 'Filter' }}</span>
        <i class="fa-solid fa-chevron-down text-xs transition duration-200" id="filterChevron"></i>
    </button>

    <div id="filterDropdown"
        class="absolute right-0 top-full z-50 mt-2 hidden w-56 rounded-2xl border border-gray-200 bg-white p-3 shadow-lg">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Category</p>

        <div class="space-y-1">
            <a href="{{ route($routeName, array_merge(request()->except('category', 'page'), ['status' => request('status', 'published')])) }}"
                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition
                {{ !request('category') ? 'bg-acmi-blueprimer/10 text-acmi-blueprimer font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                All Categories
            </a>

            @foreach ($categories as $cat)
                <a href="{{ route($routeName, array_merge(request()->except('category', 'page'), ['category' => $cat->name, 'status' => request('status', 'published')])) }}"
                    class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition
                    {{ request('category') == $cat->name ? 'bg-acmi-blueprimer/10 text-acmi-blueprimer font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>