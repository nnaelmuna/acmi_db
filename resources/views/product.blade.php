@extends('layouts.app')

@section('title', 'Product - ACMI')
@section('page_title', 'Product')

<style>
    /* Menyembunyikan scrollbar untuk Chrome, Safari, dan Opera */
    #catSlider::-webkit-scrollbar {
        display: none;
    }

    /* Menyembunyikan scrollbar untuk Firefox */
    #catSlider {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;    /* Firefox */
    }
</style>

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="flex items-center gap-3 w-full xl:w-auto">
            
            <!-- Wrapper Utama -->
            <div class="relative group flex-1 xl:flex-none">
                
                <!-- Panah Kiri (Id: leftArrow) -->
                <button id="leftArrow" onclick="scrollCat('left')" 
                    class="hidden absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 bg-white shadow-md rounded-full justify-center text-gray-600 border border-gray-100 transition hover:scale-110">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>
    
                <!-- Container Kategori (Border ditipiskan pakai border-gray-100) -->
                <div id="catSlider" class="flex no-scrollbar overflow-x-auto scroll-smooth items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5 max-w-[300px] md:max-w-[500px] lg:max-w-[700px]">
                    <a href="{{ route('product.index') }}"
                        class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('category') ? 'bg-white text-black shadow-sm' : 'text-gray-00 hover:text-black' }}">
                        <span>Semua</span>
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                            {{ $products->count() }}
                        </span>
                    </a>
    
                    @php $categories = ['Software', 'Energi', 'FnB', 'Manufaktur', 'Properti', 'Fintech', 'Logistik', 'Health']; @endphp
                    @foreach ($categories as $cat)
                        <a href="{{ route('product.index', ['category' => $cat]) }}"
                            class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ request('category') == $cat ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                            <span>{{ $cat }}</span>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                                {{ ${'count' . $cat} ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>
    
                <!-- Panah Kanan (Id: rightArrow) -->
                <button id="rightArrow" onclick="scrollCat('right')" 
                    class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 bg-white shadow-md rounded-full flex items-center justify-center text-gray-600 border border-gray-100 transition hover:scale-110">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
    
            <!-- Tombol Tambah Kategori -->
            <button type="button" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white border border-gray-100 text-gray-400 shadow-sm transition hover:text-[#0014A8]">
                <i class="fa-solid fa-plus text-sm"></i>
            </button>
        </div>
    
        <!-- Tombol New Product -->
        <div class="flex items-center">
            <a href="{{ route('product.create') }}" class="inline-flex items-center gap-3 rounded-2xl bg-[#0014A8] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-blue-900">
                <span>New Product</span>
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-7">
        @forelse ($products as $item)
            <div class="group relative bg-white rounded-xl shadow-sm p-4 border border-gray-200 transition hover:shadow-md">
                <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                    <a href="{{ route('product.edit', $item->id) }}" class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    {{-- Langsung manggil fungsi yang ada di app.blade.php --}}
                    <button type="button" onclick="openDeleteModal('{{ route('product.destroy', $item->id) }}', 'Are you sure want to delete this item?')" 
                        class="w-8 h-8 bg-white shadow-md rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>

                <div class="relative overflow-hidden rounded-lg">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/engine.jpg') }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                    <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-[10px] font-bold uppercase text-[#4155C6]">
                        {{ $item->category }}
                    </span>
                </div>

                <div class="mt-4">
                    <h3 class="font-bold text-lg text-gray-900 leading-tight">{{ $item->title }}</h3>
                    <p class="text-gray-900 font-semibold text-sm mt-1">{{ $item->company_name }}</p>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-gray-800 text-xs italic">
                        CEO: {{ $item->ceo_name }}
                    </div>
                </div>
                <a href="#" class="block w-full text-center mt-4 bg-[#4155C6] text-white py-2.5 rounded-lg font-medium transition hover:bg-[#3444a1]">Detail</a>
            </div>
        @empty
            <div class="col-span-full flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white italic text-gray-400">No Product available yet.</div>
        @endforelse
    </div>

    <script>
        const slider = document.getElementById('catSlider');
        const leftArrow = document.getElementById('leftArrow');
        const rightArrow = document.getElementById('rightArrow');
    
        function updateArrows() {
            const scrollLeft = slider.scrollLeft;
            const maxScroll = slider.scrollWidth - slider.clientWidth;
    
            // Munculkan/Sembunyikan Panah Kiri
            if (scrollLeft <= 5) {
                leftArrow.classList.add('hidden');
            } else {
                leftArrow.classList.remove('hidden');
            }
    
            // Munculkan/Sembunyikan Panah Kanan
            if (scrollLeft >= maxScroll - 5) {
                rightArrow.classList.add('hidden');
            } else {
                rightArrow.classList.remove('hidden');
            }
        }
    
        function scrollCat(direction) {
            const amount = 200;
            slider.scrollBy({
                left: direction === 'left' ? -amount : amount,
                behavior: 'smooth'
            });
        }
    
        // Jalankan saat scroll dan saat halaman pertama kali dibuka
        slider.addEventListener('scroll', updateArrows);
        window.addEventListener('load', updateArrows);
        window.addEventListener('resize', updateArrows);
    </script>
@endsection