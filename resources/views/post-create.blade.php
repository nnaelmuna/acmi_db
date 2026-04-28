@extends('layouts.app')

@section('title', 'ACMI - Create New Post')
@section('page_title', 'Create New Post')

@section('content')


<form action="{{ route('post.store') }}" enctype="multipart/form-data" method="POST" class="grid grid-cols-1 gap-8 pb-20 xl:grid-cols-12">
    @csrf

    {{-- Left Section --}}
    <div class="space-y-6 xl:col-span-7">

        {{-- Title --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Add Title</label>
            <input
                type="text"
                name="title"
                placeholder="Enter title here..."
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
            >
        </div>

        {{-- Description --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Add Description</label>
            <textarea
                rows="2"
                name="description"
                placeholder="Write a short summary..."
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
            ></textarea>
        </div>

        {{-- Hidden status --}}
        <input type="hidden" name="status" id="postStatus" value="published">
        
        {{-- Tombol Save to Draft
        <button
            type="button"
            onclick="saveDraft()"
            class="..."
        >
            Save to Draft
        </button> --}}

        {{-- Content Editor --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Field</label>

            <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                <textarea
                    id="acmi-editor"
                    name="content"
                    rows="15"
                    placeholder="Write your content here..."
                    class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none"
                ></textarea>
            </div>
        </div>
    </div>

    {{-- Right Section --}}
    <div class="space-y-8 xl:col-span-5">

        {{-- Category --}}
        <div class="rounded-2xl bg-gray-100/80 p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-black">Select Category</h3>

                <button
                    type="button"
                    onclick="openCategoryModal()"
                    class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue"
                >
                    Edit Category
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($categories as $category)
                    <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer"
                        >
                        <span class="transition group-hover:text-black">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Upload Image --}}
        <div>
            <h3 class="mb-3 text-sm font-bold text-black">Upload Image</h3>
        
            {{-- Drop Zone --}}
            <div
                id="dropZone"
                onclick="document.getElementById('imageInput').click()"
                class="group relative flex aspect-square cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white transition hover:bg-gray-50 overflow-hidden"
            >
                {{-- Preview Image (hidden by default) --}}
                <img
                    id="imagePreview"
                    src=""
                    alt="Preview"
                    class="hidden absolute inset-0 h-full w-full object-cover rounded-2xl"
                >
        
                {{-- Remove Button (hidden by default) --}}
                <button
                    id="removeImageBtn"
                    type="button"
                    onclick="removeImage(event)"
                    class="hidden absolute top-3 right-3 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600 transition"
                >
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
        
                {{-- Placeholder (visible by default) --}}
                <div id="uploadPlaceholder" class="flex flex-col items-center justify-center">
                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 transition group-hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v12.905a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Click to upload image</p>
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</p>
                </div>
            </div>
        
            {{-- Hidden Input --}}
            <input
                type="file"
                id="imageInput"
                name="image"
                accept="image/png, image/jpeg, image/webp"
                class="hidden"
            >
        </div>
    </div>

    {{-- Bottom Action Buttons --}}
    <div class="xl:col-span-12">
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
            <a
                href="{{ route('post') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Cancel
            </a>

            <button
                type="button"
                onclick="saveDraft()"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-medium text-black transition hover:bg-gray-50"
            >
                Save to Draft
            </button>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-acmi-blueprimer px-8 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue"
            >
                Publish Now
            </button>
        </div>
    </div>
</form>

@if($errors->any())
        <div class="rounded-xl bg-red-50 p-4 text-sm text-red-600">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
@endif

{{-- Modal pop-up Category --}}
<div id="categoryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl scale-95 transform transition-transform duration-300">
        
        {{-- Header Modal --}}
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Manage Categories</h3>
            <button type="button" onclick="closeCategoryModal()" class="text-gray-400">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Daftar Kategori yang Ada --}}
        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Existing Categories</p>
            <div id="categoryList" class="max-h-52 overflow-y-auto space-y-2 pr-1">
                @foreach($categories as $category)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-{{ $category->id }}">
                        
                        {{-- Normal State --}}
                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            <button
                                type="button"
                                onclick="askDeleteCategory({{ $category->id }})"
                                class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition"
                                title="Delete category"
                            >
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
        
                        {{-- Confirm State (hidden by default) --}}
                        <div class="hidden items-center justify-between gap-3 confirm-state-{{ $category->id }}">
                            <span class="text-sm font-medium text-red-500 whitespace-nowrap">Delete "{{ $category->name }}"?</span>
                            <div class="flex gap-2 flex-shrink-0">
                                <button
                                    type="button"
                                    onclick="cancelDeleteCategory({{ $category->id }})"
                                    class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    onclick="confirmDeleteCategory({{ $category->id }})"
                                    class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition"
                                >
                                    Yes, Delete
                                </button>
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
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Add New Category</p>
            <form action="{{ route('categories.store') }}" method="POST" id="formAddCategory">
                @csrf
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="name" 
                        id="newCategoryInput"
                        placeholder="e.g. Technology" 
                        class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20" 
                        required
                    >
                    <button 
                        type="submit" 
                        class="rounded-xl bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue whitespace-nowrap"
                    >
                        + Add
                    </button>
                </div>
            </form>
        </div>

        {{-- Tombol Close --}}
        <div class="mt-5 flex justify-end">
            <button 
                type="button" 
                onclick="closeCategoryModal()" 
                class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Done
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>

    tinymce.init({
        selector: '#acmi-editor',
        license_key: 'gpl',
        height: 500,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat | help',
        content_style: 'body { font-family: "Poppins", sans-serif; font-size:14px }',
        branding: false,
    });

    // Fungsi untuk memunculkan modal
    function openCategoryModal() {
        const modal = document.getElementById('categoryModal');
        const modalBox = modal.querySelector('.scale-95');
        
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }
    
    // Fungsi untuk menyembunyikan modal
    function closeCategoryModal() {
        const modal = document.getElementById('categoryModal');
        const modalBox = modal.querySelector('.scale-100');
        
        if(modalBox) {
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
            el.classList.add('flex', 'w-full'); // tambah w-full di sini
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
                // Hapus dari list di modal
                document.getElementById(`category-item-${id}`)?.remove();
                // Hapus dari checkbox list di form utama
                document.querySelector(`input[name="categories[]"][value="${id}"]`)?.closest('label')?.remove();
            }
        });
    }
    
    // Tutup modal kalau klik luar
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('categoryModal');
        if (e.target === modal) {
            closeCategoryModal();
        }
    });

    // Upload Image Preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const dropZone = document.getElementById('dropZone');
    
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) showPreview(file);
    });
    
    // Drag & Drop support
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
        event.stopPropagation(); // supaya tidak trigger file picker
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
        removeImageBtn.classList.add('hidden');
        removeImageBtn.classList.remove('flex');
        imageInput.value = '';
    }

    function saveDraft() {
        document.getElementById('postStatus').value = 'draft';
        document.querySelector('form').submit();
    }

    document.querySelector('form').addEventListener('submit', function() {
        tinymce.triggerSave();
    });
</script>
@endpush