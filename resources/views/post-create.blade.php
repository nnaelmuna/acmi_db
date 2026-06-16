@extends('layouts.app')

@section('title', 'ACMI - Create New Post')
@section('page_title', 'Create New Post')

@section('content')

    @if ($errors->any())
        <div id="errorAlert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-600 shadow-sm">
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
                    alert.style.transition = '0.7s'; alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 4000);
        </script>
    @endif

    <form id="postForm" action="{{ route('post.store') }}" enctype="multipart/form-data" method="POST" class="flex flex-col gap-8 pb-20">
        @csrf

        {{-- Section 1: Category (Full Width) --}}
        <div class="rounded-2xl bg-gray-100/80 p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-black">Select Category</h3>
                <button type="button" onclick="openCategoryModal()" class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue">
                    Edit Category
                </button>
            </div>

            <div id="categoryCheckboxList" class="flex flex-wrap gap-x-10 gap-y-4">
                @foreach ($categories as $category)
                    <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer">
                        <span class="transition group-hover:text-black">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Section 2: Upload Image (Below Category) --}}
        <div class="w-full lg:w-[450px]">
            <h3 class="mb-3 text-sm font-bold text-black">Upload Image</h3>
            <div id="dropZone" onclick="document.getElementById('imageInput').click()" class="group relative flex h-[280px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-white transition hover:bg-gray-50">
                <img id="imagePreview" src="" alt="Preview" class="absolute inset-0 hidden h-full w-full rounded-2xl object-cover">
                <button id="removeImageBtn" type="button" onclick="removeImage(event)" class="absolute right-3 top-3 z-10 hidden h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
                <div id="uploadPlaceholder" class="flex flex-col items-center justify-center">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-xl bg-gray-100 group-hover:bg-gray-200">
                        <i class="fa-regular fa-image text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Click to upload image</p>
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</p>
                </div>
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
        </div>

        {{-- Section 3: Content --}}
        <div class="space-y-6">
            <p class="pl-3 text-sm text-gray-600">Fill in either English or Indonesian content</p>
            
            {{-- English --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">English Content</h3>
                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Title</label>
                    <input type="text" name="title_en" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-acmi-blueprimer focus:ring-0">
                </div>
                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Description</label>
                    <textarea name="description_en" rows="2" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-acmi-blueprimer focus:ring-0"></textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Content</label>
                    <textarea id="acmi-editor-en" name="content_en"></textarea>
                </div>
            </div>

            {{-- Indonesia --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">Konten Bahasa Indonesia</h3>
                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Judul</label>
                    <input type="text" name="title_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-acmi-blueprimer focus:ring-0">
                </div>
                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Deskripsi</label>
                    <textarea name="description_id" rows="2" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-acmi-blueprimer focus:ring-0"></textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Isi Konten</label>
                    <textarea id="acmi-editor-id" name="content_id"></textarea>
                </div>
            </div>

            <input type="hidden" name="status" id="postStatus" value="published">
        </div>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="window.location='{{ route('post') }}'" class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600">Cancel</button>
            <x-form-status-buttons />
        </div>
    </form>

    {{-- Category Modal --}}
    <x-modal-popup-category id="categoryModal" title="Manage Categories" closeAction="closeCategoryModal()">
        <div id="categoryModalAlert" class="mb-4 hidden rounded-xl bg-green-100 p-3 text-sm text-green-700"></div>
        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold text-blue-700">EXISTING CATEGORIES</p>
            <div id="categoryList" class="max-h-52 space-y-2 overflow-y-auto pr-1">
                @foreach ($categories as $category)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-{{ $category->id }}">
                        
                        {{-- Normal Mode --}}
                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm font-medium text-gray-700" id="category-name-label-{{ $category->id }}">{{ $category->name }}</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="startEditCategory({{ $category->id }}, '{{ $category->name }}')" class="text-gray-400 hover:text-acmi-blueprimer transition">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" onclick="askDeleteCategory({{ $category->id }})" class="text-gray-400 hover:text-red-500 transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Edit Mode --}}
                        <div class="edit-state-{{ $category->id }} hidden flex-col gap-2">
                            <input type="text" id="edit-input-{{ $category->id }}" class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="cancelEditCategory({{ $category->id }})" class="text-xs font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                                <button type="button" onclick="saveEditCategory({{ $category->id }})" class="text-xs font-bold text-acmi-blueprimer hover:text-acmi-darkblue">Save</button>
                            </div>
                        </div>

                        {{-- Delete Confirmation Mode --}}
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
        <div class="border-t border-gray-200 pt-5">
            <p class="mb-3 text-xs font-semibold text-blue-700 uppercase">Add New Category</p>
            <form id="formAddCategory" class="flex gap-2">
                @csrf
                <input type="text" id="newCategoryInput" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20" placeholder="e.g. Technology" required>
                <button type="submit" id="btnAddCategory" class="rounded-lg bg-acmi-blueprimer px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-acmi-darkblue transition">+ Add</button>
            </form>
        </div>
    </x-modal-popup-category>

@endsection

@push('scripts')
    <script>
        // Init TinyMCE
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

        // Modal Controls
        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }
        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
        }

        // Category Inline Editing
        function startEditCategory(id, currentName) {
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.confirm-state-${id}`).forEach(el => el.classList.add('hidden'));
            const editState = document.querySelector(`.edit-state-${id}`);
            const input = document.getElementById(`edit-input-${id}`);
            if(editState && input) {
                editState.classList.remove('hidden');
                editState.classList.add('flex');
                input.value = currentName;
                input.focus();
            }
        }

        function cancelEditCategory(id) {
            document.querySelectorAll(`.edit-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        async function saveEditCategory(id) {
            const input = document.getElementById(`edit-input-${id}`);
            const newName = input?.value.trim();
            if(!newName) return;

            try {
                const res = await fetch(`${window.location.origin}/post-categories/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json' 
                    },
                    body: JSON.stringify({ name: newName })
                });
                const data = await res.json();
                if(data.success) {
                    // Update list di halaman & modal tanpa reload
                    const label = document.getElementById(`category-name-label-${id}`);
                    if(label) label.innerText = data.category.name;
                    
                    const mainCheckLabel = document.querySelector(`input[name="categories[]"][value="${id}"]`)?.closest('label')?.querySelector('span');
                    if(mainCheckLabel) mainCheckLabel.innerText = data.category.name;
                    
                    cancelEditCategory(id);
                }
            } catch(e) { alert('Failed to update category'); }
        }

        // Category Deleting
        function askDeleteCategory(id) {
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.edit-state-${id}`).forEach(el => el.classList.add('hidden'));
            const confirmState = document.querySelector(`.confirm-state-${id}`);
            if(confirmState) { confirmState.classList.remove('hidden'); confirmState.classList.add('flex', 'w-full', 'justify-between', 'items-center'); }
        }

        function cancelDeleteCategory(id) {
            document.querySelectorAll(`.confirm-state-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.normal-state-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function confirmDeleteCategory(id) {
            fetch(`${window.location.origin}/post-categories/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    document.getElementById(`category-item-${id}`)?.remove();
                    document.querySelector(`input[name="categories[]"][value="${id}"]`)?.closest('label')?.remove();
                }
            });
        }

        // Image Preview Logic
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const dropZone = document.getElementById('dropZone');

        if (imageInput) {
            imageInput.onchange = function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                        removeImageBtn.classList.remove('hidden');
                        removeImageBtn.classList.add('flex');
                    };
                    reader.readAsDataURL(file);
                }
            };
        }

        if (dropZone) {
            dropZone.ondragover = e => { e.preventDefault(); dropZone.classList.add('bg-gray-50'); };
            dropZone.ondragleave = () => dropZone.classList.remove('bg-gray-50');
            dropZone.ondrop = e => {
                e.preventDefault();
                dropZone.classList.remove('bg-gray-50');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    imageInput.files = e.dataTransfer.files;
                    imageInput.onchange();
                }
            };
        }

        function removeImage(e) {
            if (e) e.stopPropagation();
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
            removeImageBtn.classList.add('hidden');
            removeImageBtn.classList.remove('flex');
            imageInput.value = '';
        }

        // Add Category Logic
        const formAdd = document.getElementById('formAddCategory');
        if (formAdd) {
            formAdd.onsubmit = async function(e) {
                e.preventDefault();
                const input = document.getElementById('newCategoryInput');
                const name = input?.value.trim();
                if (!name) return;
                
                try {
                    const response = await fetch("{{ route('categories.store') }}", {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                            'Accept': 'application/json', 
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ name })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // 1. Tambahkan ke Checkbox List Utama
                        const mainList = document.getElementById('categoryCheckboxList');
                        if (mainList) {
                            mainList.insertAdjacentHTML('beforeend', `
                                <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                                    <input type="checkbox" name="categories[]" value="${data.category.id}" checked class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer">
                                    <span class="transition group-hover:text-black">${data.category.name}</span>
                                </label>
                            `);
                        }

                        // 2. Tambahkan ke List di Modal
                        const modalList = document.getElementById('categoryList');
                        if (modalList) {
                            modalList.insertAdjacentHTML('beforeend', `
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-${data.category.id}">
                                    <div class="flex items-center justify-between normal-state-${data.category.id}">
                                        <span class="text-sm font-medium text-gray-700" id="category-name-label-${data.category.id}">${data.category.name}</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" onclick="startEditCategory(${data.category.id}, '${data.category.name}')" class="text-gray-400 hover:text-acmi-blueprimer transition">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                            <button type="button" onclick="askDeleteCategory(${data.category.id})" class="text-gray-400 hover:text-red-500 transition">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="edit-state-${data.category.id} hidden flex-col gap-2">
                                        <input type="text" id="edit-input-${data.category.id}" class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="cancelEditCategory(${data.category.id})" class="text-xs font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                                            <button type="button" onclick="saveEditCategory(${data.category.id})" class="text-xs font-bold text-acmi-blueprimer hover:text-acmi-darkblue">Save</button>
                                        </div>
                                    </div>
                                    <div class="hidden items-center justify-between gap-3 confirm-state-${data.category.id}">
                                        <span class="text-sm font-medium text-red-500">Delete "${data.category.name}"?</span>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="cancelDeleteCategory(${data.category.id})" class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">Cancel</button>
                                            <button type="button" onclick="confirmDeleteCategory(${data.category.id})" class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition">Yes, Delete</button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }

                        // Reset input & Notifikasi
                        input.value = '';
                        const alertBox = document.getElementById('categoryModalAlert');
                        if (alertBox) {
                            alertBox.innerText = 'Category added successfully!';
                            alertBox.classList.remove('hidden');
                            setTimeout(() => alertBox.classList.add('hidden'), 3000);
                        }
                    } else {
                        alert(data.message || 'Failed to add category');
                    }
                } catch (e) { 
                    console.error(e);
                    alert('An error occurred');
                }
            };
        }

        // Click outside modal
        window.onclick = function(e) {
            const modal = document.getElementById('categoryModal');
            if (e.target == modal) closeCategoryModal();
        };

        document.getElementById('postForm').onsubmit = () => tinymce.triggerSave();
    </script>
@endpush