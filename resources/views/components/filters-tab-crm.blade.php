@props([
    'tabs' => [],
])

@php
    // Otomatis mencari 'value'. Kalau tidak ada, fallback ke 'label' (huruf kecil semua)
    $defaultTab = $tabs[0]['value'] ?? strtolower($tabs[0]['label'] ?? 'all');
    $activeStatus = request('status', $defaultTab);
@endphp

<div class="inline-flex w-fit flex-wrap items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
    @foreach($tabs as $tab)
        @php
            // Fallback Cerdas: Jika 'value' kosong, gunakan 'label' yang di-lowercase dan hilangkan spasinya
            $tabValue  = $tab['value'] ?? str_replace(' ', '-', strtolower($tab['label'] ?? ''));
            
            $isActive  = $activeStatus === $tabValue;
            $isTrash   = $tabValue === 'trash';
            $url       = request()->fullUrlWithQuery(['status' => $tabValue]);
        @endphp
        <a 
            href="{{ $url }}"
            class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                {{ $isActive
                    ? ($isTrash ? 'bg-red-50 text-red-600 shadow-sm' : 'bg-white text-black shadow-sm')
                    : ($isTrash ? 'text-gray-500 hover:text-red-600' : 'text-gray-500 hover:text-black') }}">
            <span>{{ $tab['label'] ?? 'Unknown' }}</span>
            <span class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-medium text-white
                {{ $isTrash ? 'bg-red-500' : 'bg-black' }}">
                {{ $tab['count'] ?? 0 }}
            </span>
        </a>
    @endforeach
</div>