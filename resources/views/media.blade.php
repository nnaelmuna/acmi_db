@extends('layouts.app')

@section('title', 'Media - ACMI')
@section('page_title', 'Media')

@section('content')
    @if (session('success'))
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
        #catSlider::-webkit-scrollbar {
            display: none;
        }

        #catSlider {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- Top Action Section --}}
    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

        {{-- BAGIAN KIRI: Status Tabs --}}
        <div class="flex flex-wrap items-center gap-2">
            <x-filters-tab :tabs="$tabs" />
        </div>

        {{-- BAGIAN KANAN: Add Category, Filter Dropdown, Add Media --}}
        <div class="flex flex-wrap items-center gap-3 xl:justify-end">
            
            {{-- Tombol Tambah Kategori --}}
            <button type="button" onclick="openCategoryModal()"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-400 shadow-sm transition hover:text-acmi-blueprimer">
                <i class="fa-solid fa-plus text-sm"></i>
            </button>

            {{-- Filter Dropdown Component --}}
            <div class="relative">
                <x-filters-dropdown-category :categories="$categories" routeName="media" />
            </div>

            {{-- Tombol Add Media --}}
            <button onclick="openMediaModal()"
                class="inline-flex h-11 items-center gap-3 rounded-xl bg-acmi-blueprimer px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue">
                <span>Add Media</span>
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-7">
        @forelse($media as $item)
            <div class="group relative bg-white rounded-xl shadow-sm p-4 border border-gray-200 transition hover:shadow-md">

                <div
                    class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                    <button type="button"
                        onclick="openEditMediaModal('{{ $item->id }}', '{{ $item->title }}', '{{ $item->media_category_id }}', '{{ $item->image ? asset('storage/' . $item->image) : '' }}')"
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

                <div class="relative overflow-hidden rounded-lg">
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

                <div class="mt-4">
                    <h3 class="font-bold text-lg text-gray-900 leading-tight">
                        {{ $item->title }}
                    </h3>

                    <div
                        class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-gray-800 text-xs italic">
                        Category: {{ $item->category->name ?? '-' }}
                    </div>
                </div>

                @if ($item->image)
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

    {{-- Category Modal --}}
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

                        {{-- Confirm State --}}
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
            {{-- Sesuaikan route ini dengan route kategori media di web.php kamu --}}
            <form action="{{ route('media.categories.store') ?? url('/media-categories') }}" method="POST" id="formAddCategory">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" id="newCategoryInput" placeholder="e.g. Campaign"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
                        required>
                    <button type="submit"
                        class="rounded-lg bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue whitespace-nowrap">+
                        Add</button>
                </div>
            </form>
        </div>
    </x-modal-popup-category>

    {{-- Add Media Modal --}}
    <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Add Media</h2>

                <button type="button" onclick="closeMediaModal()" class="text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ url('/media') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <select name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                    <x-form-status-buttons />
            </form>
        </div>
    </div>

    {{-- Edit Media Modal --}}
    <div id="editMediaModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Edit Media</h2>
                <button type="button" onclick="closeEditMediaModal()" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark text-gray-600"></i>
                </button>
            </div>

            <form id="editMediaForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-gray-800">Title</label>
                    <input type="text" id="editTitle" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm text-gray-800">Category</label>
                    <select id="editCategory" name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    {{-- Current Image --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-sm text-gray-700">Current Image</label>
                        <img id="editImagePreview" src=""
                            class="hidden h-40 w-full rounded-xl object-cover border border-gray-200">
                    </div>

                    {{-- Change Image --}}
                    <div class="mb-5">
                        <label class="mb-2 block text-sm text-gray-700">Change Image</label>
                        <input type="file" name="image"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                    </div>
                </div>

                <x-form-status-select id="edit_status" name="status" />

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit"
                        class="rounded-md bg-acmi-darkblue px-5 py-2 text-xs font-medium text-white transition hover:bg-blue-900">
                        Save Changes
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

        function updateArrows() {
            if (!slider || !leftArrow || !rightArrow) return;

            const scrollLeft = slider.scrollLeft;
            const maxScroll = slider.scrollWidth - slider.clientWidth;

            if (scrollLeft <= 5) {
                leftArrow.classList.add('hidden');
                leftArrow.classList.remove('flex');
            } else {
                leftArrow.classList.remove('hidden');
                leftArrow.classList.add('flex');
            }

            if (scrollLeft >= maxScroll - 5) {
                rightArrow.classList.add('hidden');
                rightArrow.classList.remove('flex');
            } else {
                rightArrow.classList.remove('hidden');
                rightArrow.classList.add('flex');
            }
        }

        function scrollCat(direction) {
            const amount = 200;

            slider.scrollBy({
                left: direction === 'left' ? -amount : amount,
                behavior: 'smooth'
            });
        }

        if (slider) {
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

        function openEditMediaModal(id, title, categoryId, imageUrl) {
            document.getElementById('editMediaForm').action = `/media/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editCategory').value = categoryId;

            const preview = document.getElementById('editImagePreview');

            if (imageUrl) {
                preview.src = imageUrl;
                preview.classList.remove('hidden');
            } else {
                preview.src = '';
                preview.classList.add('hidden');
            }

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

        function askDeleteCategory(id) {
            document.querySelector(`.normal-state-${id}`).classList.add('hidden');
            document.querySelector(`.confirm-state-${id}`).classList.remove('hidden');
            document.querySelector(`.confirm-state-${id}`).classList.add('flex');
        }

        function cancelDeleteCategory(id) {
            document.querySelector(`.confirm-state-${id}`).classList.add('hidden');
            document.querySelector(`.confirm-state-${id}`).classList.remove('flex');
            document.querySelector(`.normal-state-${id}`).classList.remove('hidden');
            document.querySelector(`.normal-state-${id}`).classList.add('flex');
        }
    </script>
@endsection