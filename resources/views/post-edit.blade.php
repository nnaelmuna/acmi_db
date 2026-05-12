@extends('layouts.app')

@section('title', 'ACMI - Edit Post')
@section('page_title', 'Edit Post')

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
                    alert.style.transition = '0.3s';
                    alert.style.opacity = '0';

                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }
            }, 4000);
        </script>
    @endif

    <form action="{{ route('post.update', $post) }}" enctype="multipart/form-data" method="POST"
        class="grid grid-cols-1 gap-8 pb-20 xl:grid-cols-12">
        @csrf
        @method('PUT')

        {{-- Left Section --}}
        <div class="space-y-6 xl:col-span-7">

            <p class="text-xs text-gray-500">
                Fill in either English or Indonesian content.
            </p>

            {{-- English Content --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">English Content</h3>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Title</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $post->title_en) }}"
                        placeholder="Enter English title..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Description</label>
                    <textarea rows="2" name="description_en" placeholder="Write English short summary..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">{{ old('description_en', $post->description_en) }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Content</label>
                    <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                        <textarea id="acmi-editor-en" name="content_en" rows="12" placeholder="Write English content here..."
                            class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none">{{ old('content_en', $post->content_en) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Konten Bahasa Indonesia --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="mb-5 text-sm font-bold text-acmi-blueprimer">Konten Bahasa Indonesia</h3>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Judul</label>
                    <input type="text" name="title_id" value="{{ old('title_id', $post->title_id) }}"
                        placeholder="Masukkan judul bahasa Indonesia..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </div>

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Deskripsi</label>
                    <textarea rows="2" name="description_id" placeholder="Tulis deskripsi singkat dalam bahasa Indonesia..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">{{ old('description_id', $post->description_id) }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-800">Isi Konten</label>
                    <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                        <textarea id="acmi-editor-id" name="content_id" rows="12"
                            placeholder="Tulis isi konten bahasa Indonesia di sini..."
                            class="w-full resize-none p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none">{{ old('content_id', $post->content_id) }}</textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="status" id="postStatus" value="{{ $post->status }}">
        </div>

        {{-- Right Section --}}
        <div class="space-y-8 xl:col-span-5">

            {{-- Category --}}
            <div class="rounded-2xl bg-gray-100/80 p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-black">Select Category</h3>
                    <button type="button" onclick="openCategoryModal()"
                        class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue">
                        Edit Category
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($categories as $category)
                        <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{-- ✅ Centang kategori yang sudah dipilih --}}
                                {{ $post->categories->contains($category->id) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 accent-acmi-blueprimer">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Upload Image --}}
            <div>
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
        </div>

        {{-- Bottom Action Buttons --}}
        <div class="xl:col-span-12">
            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
                <button type="button"
                    onclick="openDeleteModal('{{ route('post.destroy', $post->id) }}', 'Are you sure want to delete this post?')"
                    class="rounded-md border border-red-300 px-4 py-2 text-xs font-medium text-red-500 transition hover:bg-red-50">
                    Delete
                </button>
                <button type="button" onclick="window.location='{{ route('post') }}'"
                    class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                    Cancel
                </button>
                <x-form-status-buttons />
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        const postContent = @json(old('content', $post->content));

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

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) showPreview(file);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
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
