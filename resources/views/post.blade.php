@extends('layouts.app')

@section('title', 'ACMI - Post Management')
@section('page_title', 'Post')

@section('header_right')
    <div class="w-full max-w-[220px] md:max-w-[260px] lg:max-w-[300px]">
        <div class="relative w-full">
            <img
                src="{{ asset('assets/icons/search.svg') }}"
                alt="Search"
                class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 object-contain opacity-60"
            >
            <input
                type="text"
                placeholder="Search posts"
                class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20"
            >
        </div>
    </div>
@endsection

@section('content')
@php
    $dummyPosts = [
        [
            'title' => 'ACMI Peduli Banjir Bandung',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
        [
            'title' => 'Grebek Kantor Temen: Budi Wahyono',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
        [
            'title' => 'Edukasi Bisnis',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
        [
            'title' => 'ACMI Talk: "Menyusun Strategi Bisni 2025"',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
        [
            'title' => 'Super Networking',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
        [
            'title' => 'ACMI Peduli Banjir Bandung',
            'status' => 'Published',
            'views' => 15,
            'date' => '2026/4/7 at 12.23 am',
        ],
    ];

    $posts = $posts ?? collect($dummyPosts);
@endphp

<div class="space-y-5">

    {{-- Top Action Section --}}
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

        {{-- Tabs --}}
        <div class="inline-flex w-fit flex-wrap items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5">
            <button
                type="button"
                onclick="switchTab(this)"
                class="tab-item inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-medium text-black shadow-sm transition"
            >
                <span>Published</span>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                    0
                </span>
            </button>

            <button
                type="button"
                onclick="switchTab(this)"
                class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-black"
            >
                <span>Draft</span>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                    0
                </span>
            </button>

            <button
                type="button"
                onclick="switchTab(this)"
                class="tab-item inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-black"
            >
                <span>Archived</span>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                    0
                </span>
            </button>
        </div>

        {{-- New Post Button --}}
        <div class="flex justify-start xl:justify-end">
            <a
                href="{{ route('post.create') }}"
                class="inline-flex items-center gap-3 rounded-2xl bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue"
            >
                <span>New Post</span>
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>

    {{-- Table Wrapper --}}
    <div class="overflow-hidden rounded-[20px] border border-acmi-blueprimer bg-white">

        {{-- Table Header --}}
        <div class="grid grid-cols-12 items-center gap-3 border-b border-acmi-blueprimer px-4 py-3 text-xs font-medium text-gray-600">
            <div class="col-span-7 flex items-center gap-3 md:col-span-8">
                <input
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer"
                >
                <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">
                    Title
                </span>
            </div>

            <div class="col-span-2 text-center">
                <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">
                    Stats
                </span>
            </div>

            <div class="col-span-3 text-left">
                <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">
                    Date
                </span>
            </div>
        </div>

        {{-- Table Body --}}
        <div class="divide-y divide-acmi-blueprimer/70">
            @forelse($posts as $post)
                <div class="grid grid-cols-12 items-center gap-3 px-4 py-4 transition hover:bg-gray-50">
                    <div class="col-span-7 flex items-start gap-3 md:col-span-8">
                        <input
                            type="checkbox"
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer"
                        >

                        <p class="text-sm font-medium leading-6 text-gray-900">
                            {{ is_array($post) ? $post['title'] : $post->title }}
                        </p>
                    </div>

                    <div class="col-span-2 flex items-center justify-center gap-1 text-sm text-gray-700">
                        <img
                            src="{{ asset('assets/icons/eye.svg') }}"
                            alt="Views"
                            class="h-4 w-4 object-contain"
                        >
                        <span>{{ is_array($post) ? $post['views'] : ($post->views ?? 0) }}</span>
                    </div>

                    <div class="col-span-3 text-xs leading-5 text-gray-600">
                        <p class="font-medium text-gray-700">
                            {{ is_array($post) ? $post['status'] : ucfirst($post->status ?? 'Published') }}
                        </p>
                        <p>
                            {{ is_array($post) ? $post['date'] : optional($post->created_at)->format('Y/m/d \a\t h.i a') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex min-h-[340px] items-center justify-center px-6 py-10">
                    <p class="text-sm italic text-gray-400">
                        No post content has been created yet. Please click “New Post”.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(element) {
        const tabs = document.querySelectorAll('.tab-item');

        tabs.forEach((tab) => {
            tab.classList.remove('bg-white', 'shadow-sm', 'text-black');
            tab.classList.add('text-gray-500');
        });

        element.classList.remove('text-gray-500');
        element.classList.add('bg-white', 'shadow-sm', 'text-black');
    }
</script>
@endpush