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

    {{-- Category Tabs + Button --}}
    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

        {{-- Category Tabs --}}
        <div class="flex-1 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
            <div class="flex flex-wrap items-center gap-2">

                {{-- Semua --}}
                <a href="{{ url('/media') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                {{ !request('category') ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Semua</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $allMedia->count() }}
                    </span>
                </a>

                @foreach ($categories as $cat)
                    <div class="group relative inline-flex items-center">

                        <a href="{{ url('/media?category=' . $cat->slug) }}"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                        {{ request('category') == $cat->slug ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                            <span>{{ $cat->name }}</span>
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                                {{ $allMedia->where('media_category_id', $cat->id)->count() }}
                            </span>
                        </a>

                    {{-- Delete category --}}
                    <form action="{{ route('media.categories.delete', $cat->id) }}" method="POST"
    onsubmit="return confirm('Delete this category?')"
    class="absolute -right-1 -top-1 hidden group-hover:block">
    @csrf

                            <button type="submit"
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white shadow hover:bg-red-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
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
    <div id="categoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div class="rounded-2xl bg-acmi-darkblue px-5 py-3 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Add Category</h2>
                <button type="button" onclick="closeCategoryModal()" class="px-5 py-3 text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('media.categories.store') }}" method="POST">
                @csrf

                <label class="mb-2 block text-sm text-white">Category Name</label>
                <input type="text" name="name" required
                    class="mb-5 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCategoryModal()"
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
    </script>
@endsection
