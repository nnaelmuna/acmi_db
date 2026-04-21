@extends('layouts.app')

@section('title', 'ACMI - Create New Post')
@section('page_title', 'Create New Post')

@section('content')
@php
    $dummyCategories = [
        'Edukasi Bisnis',
        'Artikel',
        'Promo',
        'Networking',
        'Event',
        'Pengumuman',
        'Press Release',
    ];
@endphp

<form action="#" method="POST" class="grid grid-cols-1 gap-8 pb-20 xl:grid-cols-12">
    @csrf

    {{-- Left Section --}}
    <div class="space-y-6 xl:col-span-7">

        {{-- Title --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Add Title</label>
            <input
                type="text"
                placeholder="Enter title here..."
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
            >
        </div>

        {{-- Description --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Add Description</label>
            <textarea
                rows="4"
                placeholder="Write a short summary..."
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20 resize-none"
            ></textarea>
        </div>

        {{-- Content Editor --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-800">Field</label>

            <div class="overflow-hidden rounded-2xl border border-gray-300 bg-white">
                {{-- Toolbar --}}
                <div class="flex flex-wrap items-center gap-4 border-b border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                    <div class="flex items-center gap-3 border-r border-gray-300 pr-4">
                        <button type="button" class="transition hover:text-black">↩</button>
                        <button type="button" class="transition hover:text-black">↪</button>
                    </div>

                    <select class="bg-transparent font-medium text-gray-700 focus:outline-none">
                        <option>Paragraph</option>
                    </select>

                    <div class="flex items-center gap-4 font-bold text-gray-700">
                        <button type="button" class="transition hover:text-black">B</button>
                        <button type="button" class="italic transition hover:text-black">I</button>
                        <button type="button" class="underline transition hover:text-black">U</button>
                    </div>

                    <button type="button" class="font-bold transition hover:text-black">•••</button>
                </div>

                {{-- Content --}}
                <textarea
                    rows="15"
                    placeholder="Write your content here..."
                    class="w-full resize-none px-5 py-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none"
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
                    class="rounded-lg border border-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-acmi-blueprimer transition hover:bg-acmi-softblue"
                >
                    + Add Category
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($dummyCategories as $category)
                    <label class="group flex cursor-pointer items-center gap-3 text-xs text-gray-700">
                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer accent-acmi-blueprimer"
                        >
                        <span class="transition group-hover:text-black">{{ $category }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Upload Image --}}
        <div>
            <h3 class="mb-3 text-sm font-bold text-black">Upload Image</h3>

            <div class="group flex aspect-square cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white transition hover:bg-gray-50">
                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 transition group-hover:bg-gray-200">
                    <img
                        src="{{ asset('assets/icons/image-upload.svg') }}"
                        alt="Upload"
                        class="h-8 w-8 object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v12.905a1.5 1.5 0 0 0 1.5 1.5Z" />
                    </svg>
                </div>

                <p class="text-sm font-medium text-gray-500">Click to upload image</p>
            </div>
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
@endsection