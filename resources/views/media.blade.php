@extends('layouts.app')

@section('title', 'Media - ACMI')
@section('page_title', 'Media')

@section('content')

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-600">
            <p class="mb-2 font-semibold">Validation Error</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div id="successAlert" class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    <style>
        #catSlider::-webkit-scrollbar {
            display: none;
        }

        #catSlider {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

        {{-- Status Tabs --}}
        <div class="flex flex-wrap items-center gap-2">
            <x-filters-tab :tabs="$tabs" />
        </div>

        {{-- Filter, Add Category, Add Media --}}
        <div class="flex flex-wrap items-center gap-3 xl:justify-end">

            {{-- Filter Category Dropdown --}}
            <x-filters-dropdown-category :categories="$categories" routeName="media" valueField="id" />

            {{-- Add Category --}}
            <button type="button" onclick="openCategoryModal()"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-400 shadow-sm transition hover:text-acmi-blueprimer">
                <i class="fa-solid fa-plus text-sm"></i>
            </button>

            {{-- Add Media --}}
            <button type="button" onclick="openMediaModal()"
                class="inline-flex h-11 items-center gap-3 rounded-xl bg-acmi-blueprimer px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue">
                <span>Add Media</span>
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="flex min-h-[calc(100vh-230px)] flex-col">
        <div class="mt-7 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            @forelse($media as $item)
                <div
                    class="group relative rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">

                    {{-- Edit & Delete  --}}
                    @if (request('status') !== 'trash')
                        <div
                            class="absolute right-6 top-6 z-30 flex gap-2 opacity-0 transition-all duration-300 group-hover:opacity-100">

                            <button type="button"
                                onclick="openEditMediaModal({
                                    id: '{{ $item->id }}',
                                    title: @js($item->title),
                                    categoryId: '{{ $item->media_category_id }}',
                                    status: '{{ $item->status ?? 'published' }}',
                                    imageUrl: '{{ $item->image ? asset('storage/' . $item->image) : '' }}',
                                    updateUrl: '{{ route('media.update', $item->id) }}'
                                })"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-blue-600 shadow-md transition hover:bg-blue-600 hover:text-white">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <button type="button"
                                onclick="openDeleteModal('{{ route('media.destroy', $item->id) }}', 'Are you sure want to delete this media?')"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-red-500 shadow-md transition hover:bg-red-500 hover:text-white">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Image --}}
                    <div class="relative overflow-hidden rounded-lg">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-48 w-full items-center justify-center bg-gray-100 text-sm text-gray-400">
                                No Image
                            </div>
                        @endif

                        <span
                            class="absolute left-2 top-2 rounded-md bg-white/90 px-2 py-1 text-[10px] font-bold uppercase text-[#4155C6] backdrop-blur-sm">
                            {{ $item->category->name ?? '-' }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="mt-4">
                        <h3 class="text-lg font-bold leading-tight text-gray-900">
                            {{ $item->title }}
                        </h3>

                        <div
                            class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3 text-xs italic text-gray-800">
                            Category: {{ $item->category->name ?? '-' }}
                        </div>
                    </div>

                    {{-- Bottom Button --}}
                    @if (request('status') === 'trash')
                        <form action="{{ route('media.restore', $item->id) }}" method="POST" class="mt-4 w-full">
                            @csrf

                            <button type="submit"
                                class="block w-full rounded-lg border border-blue-900 py-2.5 text-center font-medium text-acmi-blueprimer transition hover:bg-acmi-blueprimer hover:text-white">
                                Restore
                            </button>
                        </form>

                        <button type="button"
                            onclick="openDeleteModal('{{ route('media.forceDelete', $item->id) }}', 'Permanently Delete? This data cannot be recovered!')"
                            class="mt-2 block w-full rounded-lg border border-red-600 py-2.5 text-center font-medium text-red-600 transition hover:bg-red-600 hover:text-white">
                            Permanently Delete
                        </button>
                    @else
                        @if ($item->image)
                            <button type="button"
                                onclick="openPreviewModal('{{ asset('storage/' . $item->image) }}', @js($item->title))"
                                class="mt-4 block w-full rounded-lg bg-acmi-blueprimer py-2.5 text-center font-medium text-white transition hover:bg-acmi-darkblue">
                                Preview
                            </button>
                        @else
                            <button type="button" disabled
                                class="mt-4 block w-full cursor-not-allowed rounded-lg bg-gray-300 py-2.5 text-center font-medium text-white">
                                No Preview
                            </button>
                        @endif
                    @endif
                </div>
            @empty
                <div
                    class="col-span-full flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white italic text-gray-400">
                    No media available yet.
                </div>
            @endforelse
        </div>

        <div class="mt-auto">
            <x-pagination :paginator="$media" />
        </div>
    </div>

    {{-- Category Modal --}}
    <x-modal-popup-category id="categoryModal" title="Manage Categories" closeAction="closeCategoryModal()">
        <div id="categoryModalAlert"
            class="mb-4 hidden rounded-xl bg-green-100 px-4 py-3 text-sm font-medium text-green-700">
        </div>

        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Existing Categories</p>

            <div id="categoryList" class="max-h-52 space-y-2 overflow-y-auto pr-1">
                @foreach ($categories as $category)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5"
                        id="category-item-{{ $category->id }}">

                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700 font-medium" id="category-name-label-{{ $category->id }}">
                                {{ $category->name }}
                            </span>

                            <div class="flex items-center gap-2">
                                <button type="button" onclick="startEditCategory({{ $category->id }}, '{{ $category->name }}')"
                                    class="text-gray-400 transition hover:text-acmi-blueprimer">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" onclick="askDeleteCategory({{ $category->id }})"
                                    class="text-gray-400 transition hover:text-red-500">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Edit State --}}
                        <div class="edit-state-{{ $category->id }} hidden flex-col gap-2">
                            <input type="text" id="edit-input-{{ $category->id }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                            
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="cancelEditCategory({{ $category->id }})"
                                    class="text-xs font-medium text-gray-500 hover:text-gray-700">
                                    Cancel
                                </button>
                                <button type="button" onclick="saveEditCategory({{ $category->id }})"
                                    class="text-xs font-bold text-acmi-blueprimer hover:text-acmi-darkblue">
                                    Save
                                </button>
                            </div>
                        </div>

                        <div class="hidden items-center justify-between gap-3 confirm-state-{{ $category->id }}">
                            <span class="whitespace-nowrap text-sm font-medium text-red-500">
                                Delete "{{ $category->name }}"?
                            </span>

                            <div class="flex flex-shrink-0 gap-2">
                                <button type="button" onclick="cancelDeleteCategory({{ $category->id }})"
                                    class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                                    Cancel
                                </button>

                                <button type="button"
                                    onclick="confirmDeleteCategory('{{ route('media.categories.destroy', $category->id) }}', {{ $category->id }})"
                                    class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white transition hover:bg-red-600">
                                    Yes, Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-5 border-t border-gray-200"></div>

        <div>
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Add New Category</p>

            <form action="{{ route('media.categories.store') }}" method="POST" id="formAddCategory">
                @csrf

                <div class="flex gap-2">
                    <input type="text" name="name" id="newCategoryInput" placeholder="e.g. Campaign"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
                        required>

                    <button type="submit"
                        class="whitespace-nowrap rounded-lg bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue">
                        + Add
                    </button>
                </div>
            </form>
        </div>
    </x-modal-popup-category>

    {{-- Add Media Modal --}}
    <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
        <div id="mediaModalBox"
            class="w-full max-w-lg scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-300">

            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Add Media</h2>

                <button type="button" onclick="closeMediaModal()" class="text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <select name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                        <option value="">Select Category</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('media_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
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
        <div id="editMediaModalBox"
            class="w-full max-w-lg scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-300">

            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Edit Media</h2>

                <button type="button" onclick="closeEditMediaModal()"
                    class="text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editMediaForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="editTitle" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <select id="editCategory" name="media_category_id" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Current Image</label>
                    <img id="editImagePreview" src=""
                        class="hidden h-40 w-full rounded-xl border border-gray-200 object-cover">
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Change Image</label>
                    <input type="file" name="image"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
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



@endsection

@push('scripts')
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

        function animateModalOpen(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const box = document.getElementById(boxId);

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            });
        }

        function animateModalClose(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const box = document.getElementById(boxId);

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openMediaModal() {
            animateModalOpen('mediaModal', 'mediaModalBox');
        }

        function closeMediaModal() {
            animateModalClose('mediaModal', 'mediaModalBox');
        }

        function openEditMediaModal(data) {
            const form = document.getElementById('editMediaForm');
            const title = document.getElementById('editTitle');
            const category = document.getElementById('editCategory');
            const status = document.getElementById('edit_status');
            const preview = document.getElementById('editImagePreview');

            form.action = data.updateUrl;
            title.value = data.title;
            category.value = data.categoryId;
            status.value = data.status || 'published';

            if (data.imageUrl) {
                preview.src = data.imageUrl;
                preview.classList.remove('hidden');
            } else {
                preview.src = '';
                preview.classList.add('hidden');
            }

            animateModalOpen('editMediaModal', 'editMediaModalBox');
        }

        function closeEditMediaModal() {
            animateModalClose('editMediaModal', 'editMediaModalBox');
        }

        function openPreviewModal(imageUrl, title) {
            document.getElementById('previewImage').src = imageUrl;
            document.getElementById('previewTitle').innerText = title;

            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.getElementById('previewImage').src = '';
        }

        function openDeleteModal(url, message) {
            const form = document.getElementById('deleteForm');
            const text = document.getElementById('deleteModalMessage');

            form.action = url;
            text.innerText = message;

            animateModalOpen('deleteModal', 'deleteModalBox');
        }

        function closeDeleteModal() {
            animateModalClose('deleteModal', 'deleteModalBox');
        }

        function askDeleteCategory(id) {
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.edit-state-${id}`).forEach(el => el.classList.add('hidden'));

            const confirmState = document.querySelector(`.confirm-state-${id}`);
            confirmState.classList.remove('hidden');
            confirmState.classList.add('flex');
        }

        function cancelDeleteCategory(id) {
            const confirmState = document.querySelector(`.confirm-state-${id}`);
            confirmState.classList.add('hidden');
            confirmState.classList.remove('flex');

            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function startEditCategory(id, currentName) {
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.confirm-state-${id}`).forEach(el => el.classList.add('hidden'));

            const editState = document.querySelector(`.edit-state-${id}`);
            const input = document.getElementById(`edit-input-${id}`);

            editState.classList.remove('hidden');
            editState.classList.add('flex');
            input.value = currentName;
            input.focus();
        }

        function cancelEditCategory(id) {
            const editState = document.querySelector(`.edit-state-${id}`);
            editState.classList.add('hidden');
            editState.classList.remove('flex');
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        async function saveEditCategory(id) {
            const input = document.getElementById(`edit-input-${id}`);
            const newName = input.value.trim();

            if (!newName) return;

            try {
                // Gunakan URL absolut untuk menghindari tabrakan rute
                const response = await fetch(`${window.location.origin}/categories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: newName
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update label di modal
                    const label = document.getElementById(`category-name-label-${id}`);
                    if (label) label.innerText = data.category.name;
                    
                    // Update onclick param di tombol edit agar data barunya tersimpan
                    const editBtn = document.querySelector(`.normal-state-${id} button[onclick^="startEditCategory"]`);
                    if (editBtn) {
                        const safeName = data.category.name.replace(/'/g, "\\'");
                        editBtn.setAttribute('onclick', `startEditCategory(${id}, '${safeName}')`);
                    }

                    cancelEditCategory(id);
                    showCategoryModalNotif('Category updated successfully');
                } else {
                    alert(data.message || 'Failed to update category');
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                alert('Update failed. Please check your connection or route.');
            }
        }

        function confirmDeleteCategory(url, id) {
            fetch(url, {
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
                        document.getElementById(`category-item-${id}`)?.remove();
                        showCategoryModalNotif('Category deleted successfully');
                    }
                });
        }

        function showCategoryModalNotif(message) {
            const alert = document.getElementById('categoryModalAlert');

            if (!alert) return;

            alert.innerText = message;
            alert.classList.remove('hidden');

            setTimeout(() => {
                alert.classList.add('hidden');
                alert.innerText = '';
            }, 2500);
        }

        function toggleFilterDropdown() {
            const dropdown = document.getElementById('filterDropdown');
            const chevron = document.getElementById('filterChevron');

            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('filterDropdownWrapper');

            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('filterDropdown')?.classList.add('hidden');
                document.getElementById('filterChevron')?.classList.remove('rotate-180');
            }
        });

        window.addEventListener('click', function(e) {
            const mediaModal = document.getElementById('mediaModal');
            const editMediaModal = document.getElementById('editMediaModal');
            const previewModal = document.getElementById('previewModal');
            const deleteModal = document.getElementById('deleteModal');

            if (e.target === mediaModal) closeMediaModal();
            if (e.target === editMediaModal) closeEditMediaModal();
            if (e.target === previewModal) closePreviewModal();
            if (e.target === deleteModal) closeDeleteModal();
        });
    </script>
@endpush