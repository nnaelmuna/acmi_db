@extends('layouts.app')

@section('title', 'Edit Product - ACMI')

@section('page_title', 'Edit Product')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <form action="{{ route('product.update', $product->id) }}" 
          method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            
            {{-- Row 1: Basic Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                   <label class="block text-sm font-bold text-gray-800 mb-2">Product Category</label>
                   @php $currentCat = old('category', $product->category); @endphp
                   <select name="category" class="w-full rounded-md border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-200' }} py-2 px-3 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] transition appearance-none bg-white bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.2rem_1.2rem] bg-no-repeat bg-[right_1rem_center] pr-10">
                        @foreach(['Energi', 'Software', 'FnB', 'Manufaktur', 'Properti', 'Fintech'] as $opt)
                            <option value="{{ $opt }}" {{ $currentCat == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Product Title</label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" class="w-full rounded-md border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                    @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $product->company_name) }}" class="w-full rounded-md border {{ $errors->has('company_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                    @error('company_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 2: Images & CEO --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8 relative">
                <input type="file" id="mainImageInput" name="product_images[]" class="hidden" accept="image/*" multiple onchange="handleImageUpload(this)">

                <div class="md:col-span-8">
                    <label class="block text-sm font-bold text-gray-800 mb-4">Update Product Image (Max 3)</label>
                    <div class="flex flex-wrap gap-4">
                        <div onclick="document.getElementById('mainImageInput').click()" 
                             class="w-32 h-40 bg-white rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-gray-50 hover:border-[#0014A8] transition group shrink-0">
                            <i class="fas fa-upload text-2xl mb-2 group-hover:text-[#0014A8]"></i>
                            <span class="text-[10px] font-bold uppercase group-hover:text-[#0014A8]">Upload Image</span>
                        </div>
                
                        {{-- Slot preview akan diisi otomatis oleh JavaScript --}}
                        @for ($i = 1; $i <= 3; $i++)
                            <div id="preview-slot-{{ $i }}" class="w-32 h-40 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-200 relative shadow-sm">
                                <span class="text-[12px] font-medium text-gray-400">Image {{ $i }}</span>
                            </div>
                        @endfor
                    </div>
                    {{-- Container rahasia buat nampung input hidden existing_images --}}
                    <div id="existing-images-container"></div>
                </div>

                <div class="md:col-span-4">
                    <label class="block text-sm font-bold text-gray-800 mb-3">CEO</label>
                    <input type="text" name="ceo_name" value="{{ old('ceo_name', $product->ceo_name) }}" class="w-full rounded-md border {{ $errors->has('ceo_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                </div>
            </div>

            {{-- Row 3: Description --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-800 mb-2">Product Description</label>
                <textarea name="description" rows="4" class="w-full rounded-sm border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} p-4 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none text-sm leading-relaxed">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Row 4: Features & Contact --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-800">Key Features</h3>
                    <div id="feature-container" class="space-y-3">
                        @if($product->features)
                            @foreach($product->features as $feature)
                                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                    <i class="fas fa-check text-[#0014A8] text-xs"></i>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                    <input type="hidden" name="features[]" value="{{ $feature }}">
                                    <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-gray-300 hover:text-red-500">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" onclick="addFeatureToList()" class="w-10 h-10 shrink-0 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 hover:text-[#0014A8] hover:border-[#0014A8] transition shadow-sm">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                        <input type="text" id="feature-input" placeholder="Add Key Features.." 
                            class="flex-1 rounded-xl border border-gray-200 py-2.5 px-4 outline-none text-sm"
                            onkeypress="if(event.key === 'Enter') { event.preventDefault(); addFeatureToList(); }">
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-800">Contact Company Details</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center"><i class="fas fa-globe text-gray-800"></i></div>
                            <input type="text" name="website" value="{{ old('website', $product->website) }}" class="w-full rounded-md border border-gray-300 py-2 px-3 outline-none">
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center"><i class="fas fa-envelope text-gray-800"></i></div>
                            <input type="email" name="email" value="{{ old('email', $product->email) }}" class="w-full rounded-md border border-gray-300 py-2 px-3 outline-none">
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center"><i class="fas fa-phone text-gray-800"></i></div>
                            <input type="text" name="phone" value="{{ old('phone', $product->phone) }}" class="w-full rounded-md border border-gray-300 py-2 px-3 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-4">
                <a href="{{ route('product.index') }}" class="px-7 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-7 py-2 rounded-lg bg-[#0014A8] text-white font-bold hover:bg-blue-900 transition shadow-lg">
                    Update Product
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // --- 1. IMAGE LOGIC ---
    let uploadedFiles = []; 
    let existingFiles = @json($product->images ?? []); 

    window.onload = function() {
        renderPreviews();
    };

    function handleImageUpload(input) {
        if (input.files && input.files.length > 0) {
            const newFiles = Array.from(input.files);
            newFiles.forEach(file => {
                if ((uploadedFiles.length + existingFiles.length) < 3) {
                    uploadedFiles.push(file);
                }
            });
            input.value = ''; 
            renderPreviews();
        }
    }

    function renderPreviews() {
        const defaultLabels = ['Image 1', 'Image 2', 'Image 3'];
        const containerHidden = document.getElementById('existing-images-container');
        containerHidden.innerHTML = ''; 

        for (let i = 1; i <= 3; i++) {
            const slot = document.getElementById(`preview-slot-${i}`);
            const isExisting = existingFiles[i - 1];
            const isNew = uploadedFiles[i - 1 - existingFiles.length];

            if (isExisting) {
                slot.innerHTML = `
                    <img src="{{ asset('storage') }}/${isExisting}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeExisting(${i-1})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg border-2 border-white hover:bg-red-600">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                `;
                containerHidden.innerHTML += `<input type="hidden" name="existing_images[]" value="${isExisting}">`;

            } else if (isNew) {
                const reader = new FileReader();
                reader.onload = e => {
                    slot.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeNew(${i - 1 - existingFiles.length})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    `;
                };
                reader.readAsDataURL(isNew);
            } else {
                slot.innerHTML = `<span class="text-[12px] font-medium text-gray-400">${defaultLabels[i-1]}</span>`;
            }
        }
    }

    function removeExisting(index) {
        existingFiles.splice(index, 1);
        renderPreviews();
    }

    function removeNew(index) {
        uploadedFiles.splice(index, 1);
        renderPreviews();
    }

    // --- 2. FEATURE LOGIC ---
    function addFeatureToList() {
        const input = document.getElementById('feature-input');
        const container = document.getElementById('feature-container');
        if (input.value.trim() !== "") {
            const val = input.value.trim();
            const div = document.createElement('div');
            div.className = "flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm";
            div.innerHTML = `
                <i class="fas fa-check text-[#0014A8] text-xs"></i>
                <span class="text-sm text-gray-700">${val}</span>
                <input type="hidden" name="features[]" value="${val}">
                <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-gray-300 hover:text-red-500">
                    <i class="fas fa-times text-xs"></i>
                </button>`;
            container.appendChild(div);
            input.value = "";
        }
    }

    // --- 3. SUBMIT LOGIC ---
    document.getElementById('productForm').onsubmit = function(e) {
        const dt = new DataTransfer();
        uploadedFiles.forEach(f => dt.items.add(f));
        document.getElementById('mainImageInput').files = dt.files;
    };
</script>
@endsection