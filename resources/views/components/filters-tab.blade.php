@props([
    'tabs' => [],
])

@php
    $activeStatus = request('status', strtolower($tabs[0]['label'] ?? 'published'));
@endphp

<div class="inline-flex w-fit flex-wrap items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
    @foreach($tabs as $tab)
        @php
            $tabStatus = strtolower($tab['label']);
            $isActive = $activeStatus === $tabStatus;
        @endphp

        <button
            type="button"
            onclick="window.location='?status={{ $tabStatus }}'"
            class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                {{ $isActive ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}"
        >
            <span>{{ $tab['label'] }}</span>
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                {{ $tab['count'] ?? 0 }}
            </span>
        </button>
    @endforeach
</div>