@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-acmi-darkblue">Media</h1>
            <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openCategoryModal()"
                class="rounded-lg bg-orange-500 px-5 py-2 text-sm font-medium text-white shadow-md hover:bg-orange-600 transition">
                Add Category
            </button>

            <button onclick="openMediaModal()"
                class="rounded-lg bg-acmi-darkblue px-5 py-2 text-sm font-medium text-white shadow-md hover:bg-[#0B1357] transition">
                Add Media
            </button>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Category Pills --}}
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach($categories as $category)
            <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600">
                {{ $category->name }}
            </span>
        @endforeach
    </div>

    {{-- Media Grid --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($media as $item)
            <div class="overflow-hidden rounded-2xl bg-white shadow-md">
                <div class="h-56 w-full bg-gray-100">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                             class="h-full w-full object-cover"
                             alt="{{ $item->title }}">
                    @endif
                </div>

                <div class="p-4">
                    <p class="mb-1 text-xs font-semibold uppercase text-orange-500">
                        {{ $item->category->name ?? '-' }}
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $item->title }}
                    </h3>

                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            onclick="openEditMediaModal(
                                '{{ $item->id }}',
                                '{{ $item->title }}',
                                '{{ $item->media_category_id }}'
                            )"
                            class="rounded-md bg-blue-600 px-3 py-2 text-xs text-white hover:bg-blue-700">
                            Edit
                        </button>

                        <form action="{{ url('/media/' . $item->id) }}" method="POST"
                              onsubmit="return confirm('Delete this media?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-md bg-orange-500 px-3 py-2 text-xs text-white hover:bg-orange-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No media found.</p>
        @endforelse
    </div>
</div>


{{-- Add Category Modal --}}
<div id="categoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-acmi-darkblue p-6 shadow-2xl">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Add Category</h2>
            <button onclick="closeCategoryModal()" class="text-white">✕</button>
        </div>

        <form action="{{ url('/categories') }}" method="POST">
            @csrf

            <label class="mb-2 block text-sm text-white">Category Name</label>
            <input type="text" name="name" required
                class="mb-5 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeCategoryModal()"
                    class="rounded-md border border-white/50 px-4 py-2 text-sm text-white">
                    Cancel
                </button>

                <button type="submit"
                    class="rounded-md bg-white px-4 py-2 text-sm font-medium text-acmi-darkblue">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>


{{-- Add Media Modal --}}
<div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-lg rounded-2xl bg-acmi-darkblue p-6 shadow-2xl">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Add Media</h2>
            <button onclick="closeMediaModal()" class="text-white">✕</button>
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
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="mb-2 block text-sm text-white">Image</label>
                <input type="file" name="image" required
                    class="w-full rounded-md bg-white px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeMediaModal()"
                    class="rounded-md border border-white/50 px-4 py-2 text-sm text-white">
                    Cancel
                </button>

                <button type="submit"
                    class="rounded-md bg-white px-4 py-2 text-sm font-medium text-acmi-darkblue">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>


{{-- Edit Media Modal --}}
<div id="editMediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-lg rounded-2xl bg-acmi-darkblue p-6 shadow-2xl">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Edit Media</h2>
            <button onclick="closeEditMediaModal()" class="text-white">✕</button>
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
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="mb-2 block text-sm text-white">Change Image</label>
                <input type="file" name="image"
                    class="w-full rounded-md bg-white px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditMediaModal()"
                    class="rounded-md border border-white/50 px-4 py-2 text-sm text-white">
                    Cancel
                </button>

                <button type="submit"
                    class="rounded-md bg-white px-4 py-2 text-sm font-medium text-acmi-darkblue">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
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
</script>
@endsection