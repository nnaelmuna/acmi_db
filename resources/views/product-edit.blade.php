@extends('layouts.app')

@section('title', 'Edit Product - ACMI')
@section('page_title', 'Edit Product')

@section('content')


    <div class="max-w-6xl mx-auto pb-10">
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="productForm">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">

                {{-- (Category, Title, Company) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Product Category</label>

                        <div
                            class="relative w-full border border-gray-200 rounded-md bg-white focus-within:ring-2 focus-within:ring-acmi-blueprimer/20 focus-within:border-acmi-blueprimer transition p-1.5 flex flex-wrap gap-2 items-center min-h-[45px]">

                            <div id="category-tags" class="flex flex-wrap gap-2 relative z-10"></div>

                            <div class="flex-1 min-w-[120px] relative">
                                <select id="category-select"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <div
                                    class="flex items-center justify-between pl-0 pr-2 py-1 text-sm text-gray-500 pointer-events-none relative z-0">
                                    <span id="placeholder-text" class="text-sm">Select Category</span>
                                    <i class="fas fa-plus text-xs text-gray-500"></i>
                                </div>
                            </div>

                            <div id="category-hidden-inputs"></div>
                        </div>

                        @error('category')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Product Title</label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}"
                            class="w-full rounded-md border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none">
                        @error('title')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $product->company_name) }}"
                            class="w-full rounded-md border {{ $errors->has('company_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none">
                        @error('company_name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Images & CEO --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8 relative">
                    <input type="file" id="mainImageInput" name="product_images[]" class="hidden" accept="image/*"
                        multiple onchange="handleImageUpload(this)">

                    <div class="md:col-span-8">
                        <label class="block text-sm font-bold text-gray-800 mb-4">Update Product Image (Max 3)</label>

                        <div class="flex flex-wrap gap-4">
                            <div onclick="document.getElementById('mainImageInput').click()"
                                class="w-32 h-40 bg-white rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-gray-50 hover:border-acmi-blueprimer transition group shrink-0">
                                <i class="fas fa-upload text-2xl mb-2 group-hover:text-acmi-blueprimer"></i>
                                <span class="text-[10px] font-bold uppercase group-hover:text-acmi-blueprimer">
                                    Upload Image
                                </span>
                            </div>

                            @for ($i = 1; $i <= 3; $i++)
                                <div id="preview-slot-{{ $i }}"
                                    class="w-32 h-40 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-200 relative shadow-sm">
                                    <span class="text-[12px] font-medium text-gray-400">Image {{ $i }}</span>
                                </div>
                            @endfor
                        </div>

                        <div id="existing-images-container"></div>

                        @error('product_images')
                            <p class="text-red-500 text-[10px] mt-2">{{ $message }}</p>
                        @enderror

                        @error('product_images.*')
                            <p class="text-red-500 text-[10px] mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-800 mb-3">CEO</label>
                        <input type="text" name="ceo_name" value="{{ old('ceo_name', $product->ceo_name) }}"
                            class="w-full rounded-md border {{ $errors->has('ceo_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none">
                        @error('ceo_name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Product Description</label>
                    <textarea name="description" rows="4"
                        class="w-full rounded-sm border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} p-4 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none text-sm leading-relaxed shadow-sm">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Features & Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Key Features --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800">Key Features</h3>

                        <div id="feature-container" class="space-y-3">
                            @foreach (old('features', $product->features ?? []) as $feature)
                                <div
                                    class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                    <i class="fas fa-check text-acmi-blueprimer text-xs"></i>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                    <input type="hidden" name="features[]" value="{{ $feature }}">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="ml-auto text-gray-300 hover:text-red-500 transition">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3 mt-4">
                            <button type="button" onclick="addFeatureToList()"
                                class="w-10 h-10 shrink-0 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 hover:text-acmi-blueprimer hover:border-acmi-blueprimer transition shadow-sm">
                                <i class="fas fa-plus text-xs"></i>
                            </button>

                            <input type="text" id="feature-input" placeholder="Add Key Features.."
                                class="flex-1 rounded-xl border border-gray-200 py-2.5 px-4 outline-none text-sm"
                                onkeypress="if(event.key === 'Enter') { event.preventDefault(); addFeatureToList(); }">
                        </div>

                        @error('features')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Company Details --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800">Contact Company Details</h3>

                        <div class="space-y-3">
                            {{-- Website --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center">
                                        <i class="fas fa-globe text-gray-800"></i>
                                    </div>

                                    <input type="text" name="website" value="{{ old('website', $product->website) }}"
                                        placeholder="Website URL"
                                        class="w-full rounded-md border {{ $errors->has('website') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 outline-none caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer">
                                </div>

                                @error('website')
                                    <p class="text-red-500 text-[10px] mt-1" style="margin-left: 3.25rem;">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center">
                                        <i class="fas fa-envelope text-gray-800"></i>
                                    </div>

                                    <input type="email" name="email" value="{{ old('email', $product->email) }}"
                                        placeholder="Company Email"
                                        class="w-full rounded-md border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 outline-none caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer">
                                </div>

                                @error('email')
                                    <p class="text-red-500 text-[10px] mt-1" style="margin-left: 3.25rem;">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center">
                                        <i class="fas fa-phone text-gray-800"></i>
                                    </div>

                                    <input type="text" name="phone" value="{{ old('phone', $product->phone) }}"
                                        placeholder="Company Phone" inputmode="tel"
                                        oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                                        class="w-full rounded-md border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 outline-none caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer">
                                </div>

                                @error('phone')
                                    <p class="text-red-500 text-[10px] mt-1" style="margin-left: 3.25rem;">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            {{-- address untuk edit --}}
                            <div class="relative mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center">
                                        <i class="fa-solid fa-location-dot text-gray-800"></i>
                                    </div>
                                    <input type="text" name="address"
                                        value="{{ old('address', $product->address ?? '') }}"
                                        placeholder="Sekretariat ACMI Jakarta, Indonesia"
                                        class="w-full rounded-md border {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 focus:border-acmi-blueprimer outline-none text-sm">
                                </div>
                                @error('address')
                                    <p class="text-red-500 text-[10px] mt-1" style="margin-left: 3.25rem;">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-12 flex items-center justify-end gap-2">
                    <a href="{{ route('product.index') }}"
                        class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                        Cancel
                    </a>

                    <x-form-status-buttons />
                </div>
            </div>
        </form>
    </div>

    <script>
        // data inisialisasi
        let uploadedFiles = [];
        let existingFiles = {!! json_encode($product->images ?? []) !!};
        let selectedCategories = @json(old('category', $product->category ?? []));

        document.addEventListener('DOMContentLoaded', function() {
            renderPreviews();
            renderCategoryTags();
        });

        // Image  Logic
        function handleImageUpload(input) {
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    if ((uploadedFiles.length + existingFiles.length) < 3) uploadedFiles.push(file);
                });

                input.value = '';
                renderPreviews();
            }
        }

        function renderPreviews() {
            const previewLabels = ['Image 1', 'Image 2', 'Image 3'];
            const container = document.getElementById('existing-images-container');

            if (!container) return;

            container.innerHTML = '';

            for (let i = 1; i <= 3; i++) {
                const slot = document.getElementById(`preview-slot-${i}`);

                if (!slot) continue;

                const ex = existingFiles[i - 1];
                const newF = uploadedFiles[i - 1 - existingFiles.length];

                if (ex) {
                    slot.innerHTML = `
                        <img src="/storage/${ex}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeEx(${i - 1})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center border-2 border-white transition shadow-sm hover:bg-red-600 z-30">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>`;

                    container.innerHTML += `<input type="hidden" name="existing_images[]" value="${ex}">`;
                } else if (newF) {
                    const reader = new FileReader();

                    reader.onload = e => {
                        slot.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <button type="button" onclick="removeNew(${i - 1 - existingFiles.length})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center border-2 border-white transition shadow-sm hover:bg-red-600 z-30">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>`;
                    };

                    reader.readAsDataURL(newF);
                } else {
                    slot.innerHTML = `<span class="text-[12px] font-medium text-gray-400">${previewLabels[i - 1]}</span>`;
                }
            }
        }

        function removeEx(idx) {
            existingFiles.splice(idx, 1);
            renderPreviews();
        }

        function removeNew(idx) {
            uploadedFiles.splice(idx, 1);
            renderPreviews();
        }

        // Category Logic
        const categorySelect = document.getElementById('category-select');

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const val = this.value;

                if (selectedCategories.length >= 3) {

                    const existingError = document.getElementById('category-error');

                    if (!existingError) {
                        const error = document.createElement('p');

                        error.id = 'category-error';
                        error.className = 'text-red-500 text-[10px] mt-1';
                        error.innerText = 'Maximum 3 categories allowed.';

                        document.getElementById('category-tags')
                            .closest('div')
                            .appendChild(error);
                    }

                    this.value = "";
                    return;
                }

                if (val && !selectedCategories.includes(val)) {
                    selectedCategories.push(val);
                    renderCategoryTags();
                }

                this.value = "";
            });
        }

        function renderCategoryTags() {
            const tagContainer = document.getElementById('category-tags');
            const inputContainer = document.getElementById('category-hidden-inputs');
            const placeholder = document.getElementById('placeholder-text');

            if (!tagContainer || !inputContainer) return;

            tagContainer.innerHTML = '';
            inputContainer.innerHTML = '';

            if (selectedCategories.length > 0) {
                if (placeholder) placeholder.classList.add('hidden');

                selectedCategories.forEach((cat, index) => {
                    tagContainer.innerHTML += `
                    <div class="flex items-center gap-1 bg-acmi-softblue text-acmi-blueprimer px-2 py-1 rounded text-xs relative z-30">
                        <span>${cat}</span>
                        <button type="button" onclick="removeCategory(${index})" class="hover:text-red-500 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;

                    inputContainer.innerHTML += `<input type="hidden" name="category[]" value="${cat}">`;
                });
            } else {
                if (placeholder) placeholder.classList.remove('hidden');
            }
        }

        function removeCategory(index) {
            selectedCategories.splice(index, 1);
            renderCategoryTags();
        }

        // Feature Logic
        function addFeatureToList() {
            const input = document.getElementById('feature-input');

            if (input.value.trim() !== "") {
                const div = document.createElement('div');

                div.className = "flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm";
                div.innerHTML = `
                    <i class="fas fa-check text-acmi-blueprimer text-xs"></i>
                    <span class="text-sm text-gray-700">${input.value}</span>
                    <input type="hidden" name="features[]" value="${input.value}">
                    <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-gray-300 hover:text-red-500 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>`;

                document.getElementById('feature-container').appendChild(div);
                input.value = "";
            }
        }

        // Submit Logic
        document.getElementById('productForm').onsubmit = function() {
            const dt = new DataTransfer();

            uploadedFiles.forEach(f => dt.items.add(f));

            document.getElementById('mainImageInput').files = dt.files;
        };

        // Real Time Error
        document.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', function() {
                const p = this.closest('.relative') || this.parentElement;
                const err = p.querySelector('.text-red-500');

                if (err) err.remove();

                this.classList.remove('border-red-500');
                this.classList.add('border-gray-200');
            });
        });
    </script>
@endsection
