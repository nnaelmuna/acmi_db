@extends('layouts.app')

@section('title', 'Product - ACMI')
@section('page_title', 'Product')

<style>
    /* Menyembunyikan scrollbar untuk Chrome, Safari, dan Opera */
    #catSlider::-webkit-scrollbar {
        display: none;
    }

    /* Menyembunyikan scrollbar untuk Firefox */
    #catSlider {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>

@section('content')
    @if (session('success'))
        <div id="successAlert" class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

        {{-- BAGIAN KIRI: Status Tabs --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- Status Tabs --}}
            <div class="inline-flex items-center gap-1 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
                <a href="{{ route('product.index', ['status' => 'published'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status', 'published') === 'published' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Published</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['published'] ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('product.index', ['status' => 'draft'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'draft' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Draft</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['draft'] ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('product.index', ['status' => 'archived'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'archived' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Archived</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['archived'] ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('product.index', ['status' => 'trash'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'trash' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Trash</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-medium text-white">
                        {{ $statusCounts['trash'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        {{-- Filter Dropdown, Add Category & New Product --}}
        <div class="flex items-center gap-1">

            {{-- Filter Dropdown --}}
            <x-filters-dropdown-category :categories="$categories" routeName="product.index" />

            {{-- Tombol Tambah Kategori --}}
            <button type="button" onclick="openCategoryModal()"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 shadow-sm transition hover:text-acmi-blueprimer">
                <i class="fa-solid fa-plus text-sm"></i>
            </button>

            {{-- Tombol New Product --}}
            <a href="{{ route('product.create') }}"
                class="inline-flex ml-4 h-11 items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue">
                <span>New Product</span>
                <i class="fa-solid fa-plus"></i>
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-7">
        @forelse ($products as $item)
            <div class="group relative bg-white rounded-xl shadow-sm p-4 border border-gray-200 transition hover:shadow-md">

                {{-- Edit & Delete (hanya non-trash) --}}
                @if (request('status') !== 'trash')
                    <div
                        class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                        <a href="{{ route('product.edit', $item->id) }}"
                            class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </a>
                        <button type="button"
                            onclick="openDeleteModal('{{ route('product.destroy', $item->id) }}', 'Are you sure want to delete this item?')"
                            class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                @endif

                {{-- Image --}}
                <div class="relative overflow-hidden rounded-lg">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/engine.jpg') }}"
                        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                    <span
                        class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-[10px] font-bold uppercase text-[#4155C6]">
                        {{ is_array($item->category) ? implode(', ', $item->category) : $item->category }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="mt-4">
                    <h3 class="font-bold text-lg text-gray-900 leading-tight">{{ $item->title }}</h3>
                    <p class="text-gray-900 font-semibold text-sm mt-1">{{ $item->company_name }}</p>
                    <div
                        class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-gray-800 text-xs italic">
                        CEO: {{ $item->ceo_name }}
                    </div>
                </div>

                {{-- Bottom Buttons --}}
                @if (request('status') === 'trash')
                    <form action="{{ route('product.restore', $item->id) }}" method="POST" class="w-full mt-4">
                        @csrf
                        <button type="submit"
                            class="block w-full text-center border border-blue-900 text-acmi-blueprimer py-2.5 rounded-lg font-medium transition hover:bg-acmi-blueprimer hover:text-white">
                            Restore
                        </button>
                    </form>
                    <form action="{{ route('product.forceDelete', $item->id) }}" method="POST" class="w-full mt-2"
                        id="forceDelete-{{ $item->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="openDeleteModal('{{ route('product.forceDelete', $item->id) }}', 'HAPUS PERMANEN? DATA INI TIDAK BISA DIKEMBALIKAN!')"
                            class="block w-full text-center border border-red-600 text-red-600 py-2.5 rounded-lg font-medium transition hover:bg-red-600 hover:text-white">
                            Permanently Delete
                        </button>
                    </form>
                @else
                    <a href="{{ route('product.show', $item->id) }}"
                        class="block w-full text-center mt-4 bg-acmi-blueprimer text-white py-2.5 rounded-lg font-medium transition hover:bg-acmi-darkblue">
                        Detail
                    </a>
                @endif

            </div>
        @empty
            <div
                class="col-span-full flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white italic text-gray-400">
                No Product available yet.
            </div>
        @endforelse
    </div>



    <x-modal-popup-category id="categoryModal" title="Manage Categories" closeAction="closeCategoryModal()">


        {{-- Daftar Kategori yang Ada --}}
        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Existing Categories</p>
            <div id="categoryList" class="max-h-52 overflow-y-auto space-y-2 pr-1">
                @foreach ($categories as $category)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5"
                        id="category-item-{{ $category->id }}">

                        {{-- Normal State --}}
                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            <button type="button" onclick="askDeleteCategory({{ $category->id }})"
                                class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>

                        {{-- Confirm State (hidden by default) --}}
                        <div class="hidden items-center justify-between gap-3 confirm-state-{{ $category->id }}">
                            <span class="text-sm font-medium text-red-500 whitespace-nowrap">Delete
                                "{{ $category->name }}"?</span>
                            <div class="flex gap-2 flex-shrink-0">
                                <button type="button" onclick="cancelDeleteCategory({{ $category->id }})"
                                    class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">Cancel</button>
                                <button type="button" onclick="confirmDeleteCategory({{ $category->id }})"
                                    class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition">Yes,
                                    Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Divider --}}
        <div class="mb-5 border-t border-gray-200"></div>

        {{-- Form Tambah Kategori Baru --}}
        <div>
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Add New Category</p>
            <form action="{{ route('product.categories.store') }}" method="POST" id="formAddCategory">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" id="newCategoryInput" placeholder="e.g. Technology"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
                        required>
                    <button type="submit"
                        class="rounded-lg bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue whitespace-nowrap">+
                        Add</button>
                </div>
            </form>
        </div>
    </x-modal-popup-category>

    {{-- Form Rahasia buat eksekusi Delete --}}
    <form id="delete-item-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        // timer notif
        const successAlert = document.getElementById('successAlert');

        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
            }, 2500);

            setTimeout(() => {
                successAlert.remove();
            }, 3000);
        }

        const slider = document.getElementById('catSlider');
        const leftArrow = document.getElementById('leftArrow');
        const rightArrow = document.getElementById('rightArrow');

        function updateArrows() {
            if (!slider) return;
            const scrollLeft = slider.scrollLeft;
            const maxScroll = slider.scrollWidth - slider.clientWidth;

            // Munculkan/Sembunyikan Panah Kiri
            if (scrollLeft <= 5) {
                leftArrow.classList.add('hidden');
            } else {
                leftArrow.classList.remove('hidden');
            }

            // Munculkan/Sembunyikan Panah Kanan
            if (scrollLeft >= maxScroll - 5) {
                rightArrow.classList.add('hidden');
            } else {
                rightArrow.classList.remove('hidden');
            }
        }

        function scrollCat(direction) {
            const amount = 200;
            slider.scrollBy({
                left: direction === 'left' ? -amount : amount,
                behavior: 'smooth'
            });
        }

        // Jalankan saat scroll dan saat halaman pertama kali dibuka
        if (slider) {
            slider.addEventListener('scroll', updateArrows);
            window.addEventListener('load', updateArrows);
            window.addEventListener('resize', updateArrows);
        }

        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            const modalBox = modal.querySelector('.scale-95') || modal.children;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            const modalBox = modal.querySelector('.scale-100') || modal.children;

            if (modalBox) {
                modalBox.classList.remove('scale-100');
                modalBox.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Fungsi delete category via AJAX
        function askDeleteCategory(id) {
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.confirm-state-${id}`).forEach(el => {
                el.classList.remove('hidden');
                el.classList.add('flex', 'w-full');
            });
        }

        function cancelDeleteCategory(id) {
            document.querySelectorAll(`.confirm-state-${id}`).forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('flex', 'w-full');
            });
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function confirmDeleteCategory(id) {
            fetch(`/product-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Hapus dari list di modal
                        document.getElementById(`category-item-${id}`)?.remove();
                    }
                });
        }

        // get data pas detail
        function openDetailModal(data) {
            document.getElementById('detail_title').innerText = data.title ?? '-';
            document.getElementById('detail_company').innerText = data.company_name ?? '-';
            // Kalau data.category itu array, gabungin pake join
            document.getElementById('detail_category').innerText = Array.isArray(data.category) ?
                data.category.join(', ') :
                (data.category ?? '-');
            document.getElementById('detail_ceo').innerText = data.ceo_name ?? '-';

            document.getElementById('detail_image').src = data.image ?
                `/storage/${data.image}` :
                `/assets/images/engine.jpg`;

            const modal = document.getElementById('detailModal');
            const box = document.getElementById('detailBox');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const box = document.getElementById('detailBox');

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function toggleFilterDropdown() {
            const dropdown = document.getElementById('filterDropdown');
            const chevron = document.getElementById('filterChevron');
            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        // Tutup dropdown kalau klik luar
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('filterDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('filterDropdown').classList.add('hidden');
                document.getElementById('filterChevron').classList.remove('rotate-180');
            }
        });
    </script>
@endpush
