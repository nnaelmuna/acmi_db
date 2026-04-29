@extends('layouts.app')

@section('title', 'ACMI - Edit Post')
@section('page_title', 'Edit Post')

@section('content')

<form action="{{ route('post.update', $post) }}" enctype="multipart/form-data" method="POST" class="grid grid-cols-1 gap-8 pb-20 xl:grid-cols-12">
    @csrf
    @method('PUT')

    {{-- Left Section --}}
    <div class="space-y-6 xl:col-span-7">

        {{-- Title --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Add Title</label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $post->title) }}" {{-- ✅ Diisi data lama --}}
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
            >{{ old('description', $post->description) }}</textarea> {{-- ✅ Diisi data lama --}}
        </div>

        {{-- Hidden status --}}
        <input type="hidden" name="status" id="postStatus" value="{{ $post->status }}">

        {{-- Content Editor --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Field</label>
            <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                <textarea
                    id="acmi-editor"
                    name="content"
                    rows="15"
                    class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none"
                >{{ old('content', $post->content) }}</textarea> {{-- ✅ Diisi data lama --}}
            </div>
        </div>
    </div>

    {{-- Right Section --}}
    <div class="space-y-8 xl:col-span-5">

        {{-- Category --}}
        <div class="rounded-2xl bg-gray-100/80 p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-black">Select Category</h3>
                <button type="button" onclick="openCategoryModal()" class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue">
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
                            {{-- ✅ Centang kategori yang sudah dipilih --}}
                            {{ $post->categories->contains($category->id) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 accent-acmi-blueprimer"
                        >
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Upload Image --}}
        <div>
            <h3 class="mb-3 text-sm font-bold text-black">Upload Image</h3>
            <div
                id="dropZone"
                onclick="document.getElementById('imageInput').click()"
                class="group relative flex aspect-square cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white transition hover:bg-gray-50 overflow-hidden"
            >
                {{-- ✅ Tampilkan gambar lama jika ada --}}
                <img
                    id="imagePreview"
                    src="{{ $post->image ? asset('storage/' . $post->image) : '' }}"
                    alt="Preview"
                    class="{{ $post->image ? '' : 'display:none' }} absolute inset-0 h-full w-full object-cover rounded-2xl"
                >

                <button
                    id="removeImageBtn"
                    type="button"
                    onclick="removeImage(event)"
                    class="{{ $post->image ? 'flex' : 'display:none' }} absolute top-3 right-3 z-10 h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600 transition"
                >
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>

                <div id="uploadPlaceholder" class="{{ $post->image ? 'display:none' : 'flex' }} flex-col items-center justify-center">
                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v12.905a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Click to upload image</p>
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</p>
                </div>
            </div>
            <input type="file" id="imageInput" name="image" accept="image/png, image/jpeg, image/webp" class="hidden">
        </div>
    </div>

    {{-- Bottom Action Buttons --}}
    <div class="xl:col-span-12">
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
            <a href="{{ route('post') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Cancel
            </a>
            <button type="button" onclick="saveDraft()" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-medium text-black transition hover:bg-gray-50">
                Save to Draft
            </button>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-acmi-blueprimer px-8 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue">
                Update Post
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    const postContent = @json(old('content', $post->content));

    tinymce.init({
        selector: '#acmi-editor',
        license_key: 'gpl',
        height: 500,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat | help',
        content_style: 'body { font-family: "Poppins", sans-serif; font-size:14px }',
        branding: false,
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(postContent);
            });
        }
    });

    // Sync TinyMCE sebelum submit
    document.querySelector('form').addEventListener('submit', function() {
        tinymce.triggerSave();
    });

    function saveDraft() {
        document.getElementById('postStatus').value = 'draft';
        tinymce.triggerSave();
        document.querySelector('form').submit();
    }

    // Image preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const dropZone = document.getElementById('dropZone');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) showPreview(file);
    });

    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
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
</script>
@endpush