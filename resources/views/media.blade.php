@extends('layouts.app')

@section('title', 'Media - ACMI')
@section('page_title', 'Media')

@section('content')
    @if  (session('success'))
        <div id="successAlert"
            class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700 transition-all duration-500 ease-in-out">
            {{ session('success') }}
        </div>
    @endif

    @php
        $defaultCategories = $categories->where('is_default', 1);
        $customCategories = $categories->where('is_default', 0);
    @endphp

    <style>
        /* Menyembunyikan scrollbar untuk Chrome, Safari, dan Opera */
        #catSlider::-webkit-scrollbar {
            display: none;
        }
    
        /* Menyembunyikan scrollbar untuk Firefox */
        #catSlider {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;    /* Firefox */
        }
    </style>

    {{-- Category Tabs + Button --}}
    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

        <!-- Wrapper Utama -->
        <div class="relative group flex-1 xl:flex-none">
                
            <!-- Panah Kiri (Id: leftArrow) -->
            <button id="leftArrow" onclick="scrollCat('left')" 
                class="hidden absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 bg-white shadow-md rounded-full flex items-center justify-center text-gray-600 border border-gray-100 transition hover:scale-110">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </button>

            <!-- Container Kategori -->
            <div id="catSlider" class="flex no-scrollbar overflow-x-auto scroll-smooth items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5 max-w-[300px] md:max-w-[500px] lg:max-w-[700px]">
                <a href="{{ route('media') }}"
                    class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('category') ? 'bg-white text-black shadow-sm' : 'text-gray-00 hover:text-black' }}">
                    <span>All</span>
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ isset($counts) ? array_sum($counts) : $media->count() }}
                    </span>
                </a>
            
                <!-- Looping langsung dari variabel $categories yang dikirim Controller -->
                @foreach ($categories as $cat)
                    <a id="slider-cat-{{ $cat->id }}" href="{{ route('media', ['category' => $cat->name]) }}"
                        class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ request('category') == $cat->name ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                        <span>{{ $cat->name }}</span>
                        
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                            {{ $counts[$cat->name] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Panah Kanan (Id: rightArrow) -->
            <button id="rightArrow" onclick="scrollCat('right')" 
                class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 bg-white shadow-md rounded-full flex items-center justify-center text-gray-600 border border-gray-100 transition hover:scale-110">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
        </div>

        {{-- Button kanan --}}
        <div class="flex shrink-0 items-center justify-end gap-3">
            <button onclick="openCategoryModal()"
                class="inline-flex items-center gap-3 rounded-lg bg-orange-500 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-orange-600">
                <span>Add Category</span>
                <i class="fa-solid fa-plus"></i>
            </button>

            <button onclick="openMediaModal()"
                class="inline-flex items-center gap-3 rounded-lg bg-[#0014A8] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-blue-900">
                <span>Add Media</span>
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-7">
        @forelse($media as $item)
            <div class="group relative bg-white rounded-xl shadow-sm p-4 border border-gray-200 transition hover:shadow-md">

                {{-- Edit Delete --}}
                <div
                    class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                <div
                    class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                    <button type="button"
                        onclick="openEditMediaModal('{{ $item->id }}', '{{ $item->title }}', '{{ $item->media_category_id }}')"
                        class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </button>

                    <form action="{{ route('media.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Delete this media?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>

                {{-- Image --}}
                <div class="relative overflow-hidden rounded-lg">
                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                            class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="flex w-full h-48 items-center justify-center bg-gray-100 text-sm text-gray-400">
                            No Image
                        </div>
                    @endif

                    <span
                       
                        class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-[10px] font-bold uppercase text-[#4155C6]">
                        {{ $item->category->name ?? '-' }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="mt-4">
                    <h3 class="font-bold text-lg text-gray-900 leading-tight">
                        {{ $item->title }}
                    </h3>

                    <div
                       
                        class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-gray-800 text-xs italic">
                        Category: {{ $item->category->name ?? '-' }}
                    </div>
                </div>

                {{-- Preview --}}
                @if  ($item->image)
                    <button type="button"
                        onclick="openPreviewModal('{{ asset('storage/' . $item->image) }}', '{{ $item->title }}')"
                        class="block w-full text-center mt-4 bg-[#4155C6] text-white py-2.5 rounded-lg font-medium transition hover:bg-[#3444a1]">
                        Preview
                    </button>
                @else
                    <button type="button" disabled
                        class="block w-full text-center mt-4 bg-gray-300 text-white py-2.5 rounded-lg font-medium cursor-not-allowed">
                        No Preview
                    </button>
                @endif
            </div>
        @empty
            <div
               
                class="col-span-full flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white italic text-gray-400">
                No media available yet.
            </div>
        @endforelse
    </div>

    {{-- Add Category Modal --}}
    <x-modal-popup-category id="categoryModal" title="Manage Categories" closeAction="closeCategoryModal()">
    
        {{-- SEMUA KODE DI BAWAH INI OTOMATIS MASUK KE DALAM {{ $slot }} --}}
        
        {{-- Daftar Kategori yang Ada --}}
        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Existing Categories</p>
            <div id="categoryList" class="max-h-52 overflow-y-auto space-y-2 pr-1">
                @foreach($categories as $category)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-{{ $category->id }}">
                        
                        {{-- Normal State --}}
                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            <button type="button" onclick="askDeleteCategory({{ $category->id }})" class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
        
                        {{-- Confirm State (hidden by default) --}}
                        <div class="hidden items-center justify-between gap-3 confirm-state-{{ $category->id }}">
                            <span class="text-sm font-medium text-red-500 whitespace-nowrap">Delete "{{ $category->name }}"?</span>
                            <div class="flex gap-2 flex-shrink-0">
                                <button type="button" onclick="cancelDeleteCategory({{ $category->id }})" class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">Cancel</button>
                                <button type="button" onclick="confirmDeleteCategory({{ $category->id }})" class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition">Yes, Delete</button>
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
            <form action="{{ route('media.categories.store') }}" method="POST" id="formAddCategory">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" id="newCategoryInput" placeholder="e.g. Technology" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20" required>
                    <button type="submit" class="rounded-lg bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue whitespace-nowrap">+ Add</button>
                </div>
            </form>
        </div>
    
        {{-- Tombol Close di bawah --}}
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeCategoryModal()" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Save Changes
            </button>
        </div>
    
    </x-modal-popup-category>

    {{-- Add Media Modal --}}
    <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div class="rounded-2xl bg-acmi-darkblue px-5 py-3 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Add Media</h2>
                <button type="button" onclick="closeMediaModal()" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ url('/media') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-white">Title</label>
                    <input type="text" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-white">Category</label>
                    <select name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm text-white">Image</label>
                    <input type="file" name="image" required class="w-full rounded-md bg-white px-3 py-2 text-sm">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeMediaModal()"
                        class="rounded-md border border-white/50 px-4 py-2 text-sm text-white hover:bg-white/10">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-md bg-white px-4 py-2 text-sm font-medium text-acmi-darkblue hover:bg-gray-100">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Media Modal --}}
    <div id="editMediaModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-acmi-darkblue p-6 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Edit Media</h2>
                <button type="button" onclick="closeEditMediaModal()" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editMediaForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-white">Title</label>
                    <input type="text" id="editTitle" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-white">Category</label>
                    <select id="editCategory" name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm text-white">Change Image</label>
                    <input type="file" name="image" class="w-full rounded-md bg-white px-3 py-2 text-sm">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditMediaModal()"
                        class="rounded-md border border-white/50 px-4 py-2 text-sm text-white hover:bg-white/10">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-md bg-white px-4 py-2 text-sm font-medium text-acmi-darkblue hover:bg-gray-100">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div id="previewModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 backdrop-blur-sm">
        <div class="relative w-full max-w-3xl rounded-2xl bg-white p-4 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 id="previewTitle" class="text-lg font-semibold text-gray-900">Preview</h2>
                <button type="button" onclick="closePreviewModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="overflow-hidden rounded-xl bg-gray-100">
                <img id="previewImage" src="" alt="Preview Image" class="max-h-[70vh] w-full object-contain">
            </div>
        </div>
    </div>

<script>
    const slider = document.getElementById('catSlider');
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');
    const successAlert = document.getElementById('successAlert');
    const deleteCategoryUrl = "{{ route('media.categories.destroy', ['id' => ':id']) }}";

    function updateArrows() {
        if(!slider) return;
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
    if(slider) {
        slider.addEventListener('scroll', updateArrows);
        window.addEventListener('load', updateArrows);
        window.addEventListener('resize', updateArrows);
    }

    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-10px)';
        }, 2500);
        setTimeout(() => {
            successAlert.remove();
        }, 3000);
    }
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
        document.getElementById('categoryModal').classList.add('flex');
    }
    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('categoryModal').classList.remove('flex');
    }
    function openMediaModal() {
        document.getElementById('mediaModal').classList.remove('hidden');
        document.getElementById('mediaModal').classList.add('flex');
    }
    function closeMediaModal() {
        document.getElementById('mediaModal').classList.add('hidden');
        document.getElementById('mediaModal').classList.remove('flex');
    }
    function openEditMediaModal(id, title, categoryId) {
        document.getElementById('editMediaForm').action = `/media/${id}`;
        document.getElementById('editTitle').value = title;
        document.getElementById('editCategory').value = categoryId;
        document.getElementById('editMediaModal').classList.remove('hidden');
        document.getElementById('editMediaModal').classList.add('flex');
    }
    function closeEditMediaModal() {
        document.getElementById('editMediaModal').classList.add('hidden');
        document.getElementById('editMediaModal').classList.remove('flex');
    }
    function openPreviewModal(imageUrl, title) {
        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('previewTitle').innerText = title;
        document.getElementById('previewModal').classList.remove('hidden');
        document.getElementById('previewModal').classList.add('flex');
    }
    function closePreviewModal() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewModal').classList.remove('flex');
        document.getElementById('previewImage').src = '';
    }
    function addMediaCategory() {
        const input = document.getElementById('mediaCategoryInput');
        const name = input.value.trim();
        if (!name) return;
    
        fetch('{{ route("media.categories.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('categoryModal-list');
                if (list) {
                    const newItem = document.createElement('div');
                    newItem.className = 'rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5';
                    newItem.id = `categoryModal-item-${data.category.id}`;
                    newItem.innerHTML = `
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">${data.category.name}</span>
                            <button type="button"
                                onclick="deleteMediaCategory(${data.category.id})"
                                class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    `;
                    list.appendChild(newItem);
                }
    
                const tabContainer = document.querySelector('.flex.flex-wrap.items-center.gap-2');
                if (tabContainer) {
                    const newTab = document.createElement('div');
                    newTab.className = 'group relative inline-flex items-center';
                    newTab.id = `tab-cat-${data.category.id}`;
                    newTab.innerHTML = `
                        <a href="{{ url('/media?category=' . $cat->slug) }}"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition text-gray-500 hover:text-black">
                            <span>${data.category.name}</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">0</span>
                        </a>
                    `;
                    tabContainer.appendChild(newTab);
                }
    
                input.value = '';
            }
        })
        .catch(err => console.error(err));
    }

    function deleteMediaCategory(id) {
        if (!confirm('Delete this category?')) return;
    
        fetch(`/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`categoryModal-item-${id}`)?.remove();
                document.getElementById(`tab-cat-${id}`)?.remove();
            }
        })
        .catch(err => console.error(err));
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
        const url = deleteCategoryUrl.replace(':id', id); // ← pakai variabel dari Laravel
    
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`); // ← tangkap error 404/500
            return res.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById(`category-item-${id}`)?.remove();
                document.getElementById(`slider-cat-${id}`)?.remove();
                updateArrows();
            }
        })
        .catch(err => console.error('Delete failed:', err)); // ← lihat error di console
    }
</script>
@endsection
