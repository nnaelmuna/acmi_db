@extends('layouts.app')

@section('title', 'ACMI - Create New Post')
@section('page_title', 'Create New Post')

@section('content')

    @if ($errors->any())
        <div id="errorAlert"
            class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-600 shadow-sm">

            <div class="mb-2 flex items-center gap-2 font-semibold">
                <i class="fa-solid fa-circle-exclamation"></i>
                Validation Error
            </div>

            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('errorAlert');

                if (alert) {
                    alert.style.transition = '0.7s';
                    alert.style.opacity = '0';

                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }
            }, 4000);
        </script>
    @endif

    @if (session('success'))
        <div id="successAlert" class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-sm font-semibold text-green-700 shadow-sm">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');

                if (alert) {
                    alert.remove();
                }
            }, 3000);
        </script>
    @endif

    <form id="postForm" action="{{ route('post.store') }}" enctype="multipart/form-data" method="POST"
        class="flex flex-col gap-8 pb-20">
        @csrf

        {{-- Category (Full Width) --}}
        <div class="rounded-2xl bg-gray-100/80 p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-black">Select Category</h3>

                <button type="button" onclick="openCategoryModal()"
                    class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue">
                    Edit Category
                </button>
            </div>

            <div id="categoryCheckboxList" class="flex flex-wrap gap-x-10 gap-y-4">
                @foreach ($categories as $category)
                    <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer">
                        <span class="transition group-hover:text-black">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Upload Image (Stack Below Category) --}}
        <div class="w-full lg:w-[450px]">
            <h3 class="mb-3 text-sm font-bold text-black">Upload Image</h3>

            <div id="dropZone" onclick="document.getElementById('imageInput').click()"
                class="group relative flex h-[280px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-white transition hover:bg-gray-50">

                <img id="imagePreview" src="" alt="Preview"
                    class="absolute inset-0 hidden h-full w-full rounded-2xl object-cover">

                <button id="removeImageBtn" type="button" onclick="removeImage(event)"
                    class="absolute right-3 top-3 z-10 hidden h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-md transition hover:bg-red-600">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>

                <div id="uploadPlaceholder" class="flex flex-col items-center justify-center">
                    <div
                        class="mb-3 flex h-14 w-14 items-center justify-center rounded-xl bg-gray-100 transition group-hover:bg-gray-200">
                        <i class="fa-regular fa-image text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Click to upload image</p>
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</p>
                </div>
            </div>

            <input type="file" id="imageInput" name="image" accept="image/png, image/jpeg, image/webp"
                class="hidden">
        </div>

        {{-- Content Section (Full Width) --}}
        <div class="space-y-6">
            <p class="text-small pl-3 text-gray-800">
                Fill in either English or Indonesian content / Isi salah satu konten: Bahasa Inggris atau Bahasa Indonesia
            </p>

            {{-- English Content --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">English Content</h3>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Title</label>
                    <input type="text" name="title_en" placeholder="Enter English title..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Description</label>
                    <textarea rows="2" name="description_en" placeholder="Write English short summary..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Content</label>
                    <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                        <textarea id="acmi-editor-en" name="content_en" rows="12" placeholder="Write English content here..."
                            class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Konten Bahasa Indonesia --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">Konten Bahasa Indonesia</h3>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Judul</label>
                    <input type="text" name="title_id" placeholder="Masukkan judul bahasa Indonesia..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Deskripsi</label>
                    <textarea rows="2" name="description_id" placeholder="Tulis deskripsi singkat dalam bahasa Indonesia..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Isi Konten</label>
                    <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                        <textarea id="acmi-editor-id" name="content_id" rows="12"
                            placeholder="Tulis isi konten bahasa Indonesia di sini..."
                            class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Hidden status --}}
            <input type="hidden" name="status" id="postStatus" value="published">
        </div>

        {{-- Bottom Action Buttons --}}
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
            <button type="button" onclick="window.location='{{ route('post') }}'"
                class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                Cancel
            </button>

            <x-form-status-buttons />
        </div>
    </form>

    {{-- Category Modal --}}
    <x-modal-popup-category id="categoryModal" title="Manage Categories" closeAction="closeCategoryModal()">

        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-700">Existing Categories</p>

            <div id="categoryList" class="max-h-52 space-y-2 overflow-y-auto pr-1">
                @foreach ($categories as $category)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5"
                        id="category-item-{{ $category->id }}">

                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>

                            <button type="button" onclick="askDeleteCategory({{ $category->id }})"
                                class="ml-3 flex-shrink-0 text-gray-400 transition hover:text-red-500">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
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

                                <button type="button" onclick="confirmDeleteCategory({{ $category->id }})"
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

            <form action="{{ route('categories.store') }}" method="POST" id="formAddCategory">
                @csrf

                <div class="flex gap-2">
                    <input type="text" name="name" id="newCategoryInput" placeholder="e.g. Technology"
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

@endsection

@push('scripts')
    <script>
        tinymce.init({
            selector: '#acmi-editor-en, #acmi-editor-id',
            license_key: 'gpl',
            height: 400,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat | help',
            content_style: 'body { font-family: "Poppins", sans-serif; font-size:14px }',
            branding: false,
        });

        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            const modalBox = modal.querySelector('.scale-95') || modal.children[0];

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            const modalBox = modal.querySelector('.scale-100') || modal.children[0];

            if (modalBox) {
                modalBox.classList.remove('scale-100');
                modalBox.classList.add('scale-95');
            }

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function showToast(message) {
            const toast = document.createElement('div');

            toast.className =
                'fixed top-6 right-6 z-[9999] rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-lg';

            toast.innerText = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

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
            fetch(`/post-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`category-item-${id}`)?.remove();
                        document.querySelector(`input[name="categories[]"][value="${id}"]`)?.closest('label')
                            ?.remove();

                        showToast('Category deleted successfully.');
                    } else {
                        alert('Failed to delete category.');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert('Failed to delete category.');
                });
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('categoryModal');

            if (e.target === modal) {
                closeCategoryModal();
            }
        });

        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const dropZone = document.getElementById('dropZone');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) showPreview(file);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-acmi-blueprimer', 'bg-acmi-softblue');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-acmi-blueprimer', 'bg-acmi-softblue');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();

            dropZone.classList.remove('border-acmi-blueprimer', 'bg-acmi-softblue');

            const file = e.dataTransfer.files[0];

            if (file && file.type.startsWith('image/')) {
                imageInput.files = e.dataTransfer.files;
                showPreview(file);
            }
        });

        function showPreview(file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden');
                removeImageBtn.classList.remove('hidden');
                removeImageBtn.classList.add('flex');
            };

            reader.readAsDataURL(file);
        }

        function removeImage(event) {
            event.stopPropagation();

            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
            removeImageBtn.classList.add('hidden');
            removeImageBtn.classList.remove('flex');
            imageInput.value = '';
        }

        document.getElementById('postForm').addEventListener('submit', function() {
            tinymce.triggerSave();
        });

        document.getElementById('formAddCategory').addEventListener('submit', async function(e) {
            e.preventDefault();

            const input = document.getElementById('newCategoryInput');
            const name = input.value.trim();

            if (!name) return;

            try {
                const response = await fetch("{{ route('categories.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        name: name
                    })
                });

                const data = await response.json();

                if (data.success) {
                    const categoryWrapper = document.getElementById('categoryCheckboxList');

                    categoryWrapper.insertAdjacentHTML('beforeend', `
                        <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                            <input
                                type="checkbox"
                                name="categories[]"
                                value="${data.category.id}"
                                checked
                                class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer"
                            >
                            <span class="transition group-hover:text-black">${data.category.name}</span>
                        </label>
                    `);

                    document.getElementById('categoryList').insertAdjacentHTML('beforeend', `
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-${data.category.id}">
                            <div class="flex items-center justify-between normal-state-${data.category.id}">
                                <span class="text-sm text-gray-700">${data.category.name}</span>

                                <button type="button"
                                    onclick="askDeleteCategory(${data.category.id})"
                                    class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>

                            <div class="hidden items-center justify-between gap-3 confirm-state-${data.category.id}">
                                <span class="text-sm font-medium text-red-500 whitespace-nowrap">
                                    Delete "${data.category.name}"?
                                </span>

                                <div class="flex gap-2 flex-shrink-0">
                                    <button type="button"
                                        onclick="cancelDeleteCategory(${data.category.id})"
                                        class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                                        Cancel
                                    </button>

                                    <button type="button"
                                        onclick="confirmDeleteCategory(${data.category.id})"
                                        class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition">
                                        Yes, Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);

                    input.value = '';

                    closeCategoryModal();
                    showToast('Category added successfully.');
                } else {
                    const msg = data.message || 'Failed to add category.';
                    alert(msg);
                }

            } catch (error) {
                console.error(error);
                alert('Failed to add category.');
            }
        });
    </script>
@endpush