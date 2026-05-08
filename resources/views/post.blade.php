@extends('layouts.app')

@section('title', 'ACMI - Post Management')
@section('page_title', 'Post')

@section('header_right')
    <div class="w-full max-w-[220px] md:max-w-[260px] lg:max-w-[300px]">
        <div class="relative w-full">
            <img src="{{ asset('assets/icons/search.svg') }}" alt="Search"
                class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 object-contain opacity-60">
            <input type="text" placeholder="Search posts"
                class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
        </div>
    </div>
@endsection

@section('content')

    <div class="space-y-5">

        {{-- Top Action Section --}}
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            {{-- Tabs --}}
            <x-filters-tab :tabs="$tabs" />

            {{-- Kanan: Filter + New Post --}}
            <div class="flex items-center gap-2 justify-start xl:justify-end">

                {{-- Filter Dropdown --}}
                <x-filters-dropdown-category :categories="$categories" routeName="post" />

                {{-- New Post Button --}}
                <a href="{{ route('post.create') }}"
                    class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                    <span>New Post</span>
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
        </div>

        {{-- Table Wrapper --}}
        <div class="overflow-hidden rounded-[20px] border border-acmi-blueprimer bg-white">

            {{-- Table Header --}}
            <div
                class="grid grid-cols-12 items-center gap-3 border-b border-acmi-blueprimer px-4 py-3 text-xs font-medium text-gray-600">
                <div class="col-span-8 flex items-center gap-3">
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Title</span>
                </div>

                <div class="col-span-2 text-center">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Stats</span>
                </div>

                <div class="col-span-2 text-left">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Date</span>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-acmi-blueprimer/70">
                @forelse($posts as $post)
                    <div class="grid grid-cols-12 items-center gap-3 px-4 py-4 transition hover:bg-gray-50">

                        {{-- Title --}}
                        <div class="col-span-8 flex items-center gap-3">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300">
                            <p class="text-sm font-medium text-gray-900">
                                <a href="{{ route('post.edit', $post) }}" class="hover:text-acmi-blueprimer transition">
                                    {{ $post->title }}
                                </a>
                            </p>
                        </div>

                        {{-- Stats --}}
                        <div class="col-span-2 flex items-center justify-center gap-1 text-sm text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                            <span>{{ $post->views ?? 0 }}</span>
                        </div>

                        {{-- Date --}}
                        <div class="col-span-2 text-xs text-gray-600">
                            {{ optional($post->created_at)->format('Y/m/d \a\t h:i a') }}
                        </div>

                    </div>
                @empty
                    <div class="flex min-h-[340px] items-center justify-center px-6 py-10">
                        <p class="text-sm italic text-gray-400">
                            No post content has been created yet. Please click "New Post".
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
