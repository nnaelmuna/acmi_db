@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product - ACMI' : 'New Product - ACMI')

@section('page_title', isset($product) ? 'Edit Product' : 'New Product')

@section('content')
    <div class="max-w-6xl mx-auto pb-10">
        {{-- Form dinamis: ke Update atau ke Store --}}
        <form action="{{ isset($product) ? route('product.update', $product->id) : route('product.store') }}" method="POST"
            enctype="multipart/form-data" id="productForm">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div>

                {{-- Top Row: Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Kategori Produk</label>
                        @php $currentCat = old('category', $product->category ?? ''); @endphp
                        <select name="category"
                            class="w-full rounded-md border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-200' }} py-2 px-3 focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] transition appearance-none bg-white bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.2rem_1.2rem] bg-no-repeat bg-[right_1rem_center] pr-10">
                            @foreach (['Energi', 'Software', 'FnB', 'Manufaktur', 'Properti', 'Fintech'] as $opt)
                                <option value="{{ $opt }}" {{ $currentCat == $opt ? 'selected' : '' }}>
                                    {{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Judul Produk</label>
                        <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}"
                            placeholder="Green Energy Solutions"
                            class="w-full rounded-md border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                        @error('title')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Nama Perusahaan</label>
                        <input type="text" name="company_name"
                            value="{{ old('company_name', $product->company_name ?? '') }}"
                            placeholder="PT Energi Hijau Indonesia"
                            class="w-full rounded-md border {{ $errors->has('company_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                        @error('company_name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Second Row: Images & CEO --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8 relative">
                    <!-- Letakkan ini di atas bagian row gambar -->
                    <input type="file" id="mainImageInput" name="product_images[]" class="hidden" accept="image/*"
                        multiple onchange="handleImageUpload(this)">

                    <div class="md:col-span-8">
                        <label class="block text-sm font-bold text-gray-800 mb-4">Gambar Produk (Max 3)</label>
                        <div class="flex flex-wrap gap-4">
                            <!-- Tombol Upload -->
                            <div onclick="document.getElementById('mainImageInput').click()"
                                class="w-32 h-40 bg-white rounded-2xl border-2 border-dashed {{ $errors->has('product_images') ? 'border-red-400' : 'border-gray-200' }} flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:border-[#0014A8] transition group shrink-0 shadow-sm">
                                <i class="fas fa-upload text-xl mb-2 group-hover:text-[#0014A8]"></i>
                                <span class="text-[9px] font-bold uppercase group-hover:text-[#0014A8]">Upload Image</span>
                            </div>

                            <!-- Slot Preview 1 (Primary) -->
                            <div id="preview-slot-1"
                                class="w-32 h-40 bg-white rounded-2xl flex items-center justify-center overflow-hidden border border-gray-100 relative shadow-sm">
                                @if (isset($product) && $product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-[12px] font-medium text-gray-400">Image 1</span>
                                @endif
                            </div>

                            <!-- Slot Preview 2 -->
                            <div id="preview-slot-2"
                                class="w-32 h-40 bg-white rounded-2xl flex items-center justify-center overflow-hidden border border-gray-100 relative shadow-sm">
                                <span class="text-[12px] font-medium text-gray-400">Image 2</span>
                            </div>

                            <!-- Slot Preview 3 -->
                            <div id="preview-slot-3"
                                class="w-32 h-40 bg-white rounded-2xl flex items-center justify-center overflow-hidden border border-gray-100 relative shadow-sm">
                                <span class="text-[12px] font-medium text-gray-400">Image 3</span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-800 mb-3">CEO</label>
                        <input type="text" name="ceo_name" value="{{ old('ceo_name', $product->ceo_name ?? '') }}"
                            placeholder="Dewi Kusuma"
                            class="w-full rounded-md border {{ $errors->has('ceo_name') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                    </div>
                </div>

                {{-- Description Section --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Deskripsi Produk</label>
                    <textarea name="description" rows="4" placeholder="Green Energy Solutions menyediakan..."
                        class="w-full rounded-sm border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} p-4 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none shadow-sm text-sm leading-relaxed">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                {{-- Bottom Section: Features & Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                    {{-- Key Features --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800">Fitur Utama</h3>
                        <div id="feature-container" class="space-y-3">
                            {{-- Kalau ada data lama (pas edit), tampilin di sini --}}
                            @if (isset($product) && $product->features)
                                @foreach ($product->features as $feature)
                                    <div
                                        class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                        <div
                                            class="w-5 h-5 rounded border border-gray-300 flex items-center justify-center text-[#0014A8]">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                                        <input type="hidden" name="features[]" value="{{ $feature }}">
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="ml-auto text-gray-300 hover:text-red-500">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mt-4">
                            <button type="button" onclick="addFeatureToList()"
                                class="w-10 h-10 shrink-0 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 hover:text-[#0014A8] hover:border-[#0014A8] transition shadow-sm">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                            <input type="text" id="feature-input" placeholder="Tambahckan Fitur Utama.."
                                class="flex-1 rounded-xl border border-gray-200 py-2.5 px-4 outline-none text-sm"
                                onkeypress="if(event.key === 'Enter') { event.preventDefault(); addFeatureToList(); }">
                        </div>
                    </div>

                    {{-- Company Contact Details --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800">Rincian Kontak Perusahaan</h3>
                        <div class="space-y-3">
                            {{-- Website --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center"><i
                                            class="fas fa-globe text-gray-800"></i></div>
                                    <input type="text" name="website"
                                        value="{{ old('website', $product->website ?? '') }}"
                                        placeholder="https://energihijau.co.id"
                                        class="w-full rounded-md border {{ $errors->has('website') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                                </div>
                                @error('website')
                                    <p class="text-red-500 text-[10px] mt-1 ml-13" style="margin-left: 3.25rem;">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center"><i
                                            class="fas fa-envelope text-gray-800"></i></div>
                                    <input type="email" name="email"
                                        value="{{ old('email', $product->email ?? '') }}"
                                        placeholder="contact@energihijau.co.id"
                                        class="w-full rounded-md border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-[10px] mt-1 ml-13" style="margin-left: 3.25rem;">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex items-center justify-center"><i
                                            class="fas fa-phone text-gray-800"></i></div>
                                    <input type="text" name="phone"
                                        value="{{ old('phone', $product->phone ?? '') }}" placeholder="+62 21 5555 0202"
                                        class="w-full rounded-md border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 caret-[#0014A8] focus:ring-2 focus:ring-[#0014A8]/20 focus:border-[#0014A8] outline-none">
                                </div>
                                @error('phone')
                                    <p class="text-red-500 text-[10px] mt-1 ml-13" style="margin-left: 3.25rem;">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-12 flex justify-end gap-4">
                    <a href="{{ route('product.index') }}"
                        class="px-7 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                        class="px-7 py-2 rounded-lg bg-[#0014A8] text-white font-bold hover:bg-blue-900 transition shadow-lg">
                        {{ isset($product) ? 'Update Product' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // --- 1. FITUR LOGIC (Key Features) ---
        function addFeatureToList() {
            const input = document.getElementById('feature-input');
            const container = document.getElementById('feature-container');
            if (input.value.trim() !== "") {
                const val = input.value.trim();
                const div = document.createElement('div');
                div.className = "flex items-center gap-3 bg-white p-3.5 rounded-2xl border border-gray-100 shadow-sm";
                div.innerHTML = `
                    <i class="fas fa-check text-[#0014A8] text-xs"></i>
                    <span class="text-sm text-gray-700">${val}</span>
                    <input type="hidden" name="features[]" value="${val}">
                    <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-gray-300 hover:text-red-500 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>`;
                container.appendChild(div);
                input.value = "";
            }
        }
    
       // --- IMAGE LOGIC (Versi Nabung Gambar) ---
    let uploadedFiles = []; // Ini tabungan gambar kita

function handleImageUpload(input) {
    if (input.files && input.files.length > 0) {
        // Ambil file yang baru dipilih
        const newFiles = Array.from(input.files);
        
        // Masukin ke tabungan, tapi maksimal tetep cuma 3
        newFiles.forEach(file => {
            if (uploadedFiles.length < 3) {
                uploadedFiles.push(file);
            }
        });

        // Penting: Reset input biar kalau pilih file yang sama bisa kepicu lagi onchange-nya
        input.value = ''; 
        
        renderPreviews();
    }
}

function renderPreviews() {
    const defaultLabels = ['Image 1', 'Image 2', 'Image 3'];
    
    for (let i = 1; i <= 3; i++) {
        const slot = document.getElementById(`preview-slot-${i}`);
        const file = uploadedFiles[i - 1]; 
        
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                // Kasih tombol hapus kecil biar kalau salah upload bisa dibuang
                slot.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeImage(${i-1})" class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full text-[10px] flex items-center justify-center shadow-lg">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            slot.innerHTML = `<span class="text-[12px] font-medium text-gray-400">${defaultLabels[i-1]}</span>`;
        }
    }
}

// Fungsi tambahan buat hapus kalau salah pilih
function removeImage(index) {
    uploadedFiles.splice(index, 1);
    renderPreviews();
}
    
        // --- 3. SUBMIT LOGIC (Paksa file masuk ke Form) ---
        document.getElementById('productForm').onsubmit = function(e) {
            // Ini bagian krusial biar 3 gambarnya beneran kekirim ke Laravel
            if (uploadedFiles.length > 0) {
                const dt = new DataTransfer();
                uploadedFiles.forEach(f => dt.items.add(f));
                document.getElementById('mainImageInput').files = dt.files;
            }
            // Lu bisa tambah validasi lain di sini kalau perlu
        };
    
        // --- 4. REAL-TIME ERROR REMOVAL ---
        document.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', function() {
                const p = this.closest('.relative') || this.parentElement;
                const err = p.querySelector('.text-red-500');
                if (err) err.remove();
                // Kembalikan border ke warna normal
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-200');
            });
        });
    </script>
@endsection
