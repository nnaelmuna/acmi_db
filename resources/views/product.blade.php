@extends('layouts.app')

@section('title', 'Product - ACMI')
@section('page_title', 'Product')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="inline-flex w-fit flex-wrap items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
            <a href="{{ route('product.index') }}"
                class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('category') ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                <span>Semua</span>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                    {{ $products->count() }}
                </span>
            </a>

            @php $categories = ['Software', 'Energi', 'FnB', 'Manufaktur', 'Properti', 'Fintech']; @endphp
            @foreach ($categories as $cat)
                <a href="{{ route('product.index', ['category' => $cat]) }}"
                    class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ request('category') == $cat ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>{{ $cat }}</span>
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ ${'count' . $cat} ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>

        <div class="flex items-center mb-2">
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
@endsection