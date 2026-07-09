@extends('layouts.app')

@section('title', 'Header Settings - ACMI')

@section('page_title', 'Header Settings')

@section('content')

    <div class="max-w-6xl mx-auto pb-10">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('header.update') }}" method="POST" enctype="multipart/form-data" id="headerForm">
            @csrf

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Title 1 (e.g. Komunitas Eksklusif)</label>
                        <input type="text" name="title_1" value="{{ old('title_1', $header->title_1) }}"
                            placeholder="Title 1"
                            class="w-full rounded-md border {{ $errors->has('title_1') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none">
                        @error('title_1')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Title 2 (e.g. CEO Indonesia)</label>
                        <input type="text" name="title_2" value="{{ old('title_2', $header->title_2) }}"
                            placeholder="Title 2"
                            class="w-full rounded-md border {{ $errors->has('title_2') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none">
                        @error('title_2')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Header Description</label>
                    <textarea name="description" rows="4" placeholder="Header Description..."
                        class="w-full rounded-sm border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} p-4 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none shadow-sm text-sm leading-relaxed">{{ old('description', $header->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-4">Header Images (Carousel, Max 5)</label>
                    <input type="file" id="mainImageInput" name="header_images[]" class="hidden" accept="image/*"
                        multiple onchange="handleImageUpload(this)">

                    <div id="existing-images-container" class="hidden">
                        @if($header->images)
                            @foreach($header->images as $img)
                                <input type="hidden" name="existing_images[]" value="{{ $img }}">
                            @endforeach
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <div onclick="document.getElementById('mainImageInput').click()"
                            class="w-32 h-40 bg-white rounded-lg border-2 border-dashed {{ $errors->has('header_images') ? 'border-red-400' : 'border-gray-200' }} flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:border-acmi-blueprimer transition group shrink-0 shadow-sm">
                            <i class="fas fa-upload text-xl mb-2 group-hover:text-acmi-blueprimer"></i>
                            <span class="text-[9px] font-bold uppercase group-hover:text-acmi-blueprimer">Upload Image</span>
                        </div>

                        @for($i = 1; $i <= 5; $i++)
                            <div id="preview-slot-{{$i}}"
                                class="w-32 h-40 bg-white rounded-lg flex items-center justify-center overflow-hidden border border-gray-100 relative shadow-sm">
                                <span class="text-[12px] font-medium text-gray-400">Image {{$i}}</span>
                            </div>
                        @endfor
                    </div>

                    @error('header_images')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="mt-8 flex items-center justify-end gap-2">
                    <button type="submit" class="px-6 py-2 bg-acmi-blueprimer hover:bg-acmi-darkblue text-white rounded-md text-sm font-medium transition">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let existingImages = @json($header->images ?? []);
        let uploadedFiles = [];

        document.addEventListener('DOMContentLoaded', function() {
            renderPreviews();
        });

        function handleImageUpload(input) {
            if (!input.files || input.files.length === 0) return;

            const newFiles = Array.from(input.files);

            newFiles.forEach(file => {
                if (existingImages.length + uploadedFiles.length < 5) {
                    uploadedFiles.push(file);
                }
            });

            syncFileInput();
            renderPreviews();

            input.value = '';
        }

        function syncFileInput() {
            const dt = new DataTransfer();

            uploadedFiles.forEach(file => {
                dt.items.add(file);
            });

            document.getElementById('mainImageInput').files = dt.files;
        }

        function renderPreviews() {
            const maxImages = 5;
            
            // Sync existing images hidden inputs
            const container = document.getElementById('existing-images-container');
            container.innerHTML = '';
            existingImages.forEach(img => {
                container.innerHTML += `<input type="hidden" name="existing_images[]" value="${img}">`;
            });

            let currentSlot = 1;

            // Render existing images
            existingImages.forEach((img, idx) => {
                const slot = document.getElementById(`preview-slot-${currentSlot}`);
                slot.innerHTML = `
                    <img src="/storage/${img}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeExistingImage(${idx})" class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full text-[10px] flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                currentSlot++;
            });

            // Render newly uploaded files
            uploadedFiles.forEach((file, idx) => {
                const slot = document.getElementById(`preview-slot-${currentSlot}`);
                const reader = new FileReader();
                reader.onload = e => {
                    slot.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeUploadedImage(${idx})" class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full text-[10px] flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                };
                reader.readAsDataURL(file);
                currentSlot++;
            });

            // Empty remaining slots
            while (currentSlot <= maxImages) {
                const slot = document.getElementById(`preview-slot-${currentSlot}`);
                slot.innerHTML = `<span class="text-[12px] font-medium text-gray-400">Image ${currentSlot}</span>`;
                currentSlot++;
            }
        }

        function removeExistingImage(index) {
            existingImages.splice(index, 1);
            renderPreviews();
        }

        function removeUploadedImage(index) {
            uploadedFiles.splice(index, 1);
            syncFileInput();
            renderPreviews();
        }

        document.getElementById('headerForm').addEventListener('submit', function() {
            syncFileInput();
        });
    </script>
@endsection
