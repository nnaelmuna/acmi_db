@props(['id', 'title', 'closeAction'])

<div id="{{ $id }}"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div
        class="w-full max-w-md rounded-xl border border-gray-300 bg-white p-6 shadow-xl scale-95 transform transition-transform duration-300">

        {{-- Header Modal --}}
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
            <!-- Tombol silang (X) yang fungsinya dinamis -->
            <button type="button" onclick="{{ $closeAction }}" class="text-gray-400">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{ $slot }}

    </div>
</div>
