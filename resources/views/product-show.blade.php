@extends('layouts.app')

@section('title', 'Product Detail - ACMI')
@section('page_title', 'Product Detail')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">

        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/engine.jpg') }}"
            class="mb-6 h-72 w-full rounded-2xl object-cover">

        <h1 class="text-2xl font-bold text-gray-900">{{ $product->title }}</h1>
        <p class="mt-2 text-sm font-semibold text-gray-700">{{ $product->company_name }}</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
            <p><b>Category:</b> {{ $product->category }}</p>
            <p><b>CEO:</b> {{ $product->ceo_name }}</p>
            <p><b>Website:</b> {{ $product->website }}</p>
            <p><b>Email:</b> {{ $product->email }}</p>
            <p><b>Phone:</b> {{ $product->phone }}</p>
        </div>

        <div class="mt-6">
            <h3 class="font-bold text-gray-900 mb-2">Description</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $product->description }}</p>
        </div>

        @if ($product->features)
            <div class="mt-6">
                <h3 class="font-bold text-gray-900 mb-3">Key Features</h3>
                <div class="space-y-2">
                    @foreach ($product->features as $feature)
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-check text-[#0014A8] text-xs"></i>
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('product.index') }}"
                class="px-7 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                Back
            </a>

            <a href="{{ route('product.edit', $product->id) }}"
                class="px-7 py-2 rounded-lg bg-acmi-blueprimer text-white font-medium hover:bg-acmi-darkblue transition shadow-lg">
                Edit Product
            </a>
        </div>

    </div>
</div>
@endsection