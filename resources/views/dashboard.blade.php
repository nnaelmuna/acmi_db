@extends('layouts.app')

@section('title', 'Dashboard CMS - ACMI')
@section('page_title', 'Dashboard')

@section('content')
<div class="w-full">
    <div class="flex flex-col gap-6">

        {{-- Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

            {{-- Total Member --}}
            <div class="rounded-2xl border border-acmi-blueprimer bg-white overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3">
                    <p class="text-sm font-medium text-gray-700">Total Member</p>
                    <img src="{{ asset('assets/icons/people_icon.svg') }}"
                         alt="Total Member"
                         class="h-5 w-5 object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                </div>
                <div class="h-px bg-acmi-blueprimer"></div>
                <div class="px-5 py-4">
                    <h2 class="text-4xl font-bold text-black">
                        {{ number_format($totalMember ?? 0) }}
                    </h2>
                </div>
            </div>

            {{-- New Member --}}
            <div class="rounded-2xl border border-acmi-blueprimer bg-white overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3">
                    <p class="text-sm font-medium text-gray-700">New Member</p>
                    <img src="{{ asset('assets/icons/people_icon.svg') }}"
                         alt="New Member"
                         class="h-5 w-5 object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                </div>
                <div class="h-px bg-acmi-blueprimer"></div>
                <div class="px-5 py-4">
                    <h2 class="text-4xl font-bold text-black">
                        {{ number_format($newMember ?? 0) }}
                    </h2>
                </div>
            </div>

            {{-- Total Views --}}
            <div class="rounded-2xl border border-acmi-blueprimer bg-white overflow-hidden md:col-span-2 xl:col-span-1">
                <div class="flex items-center justify-between px-5 py-3">
                    <p class="text-sm font-medium text-gray-700">Total Views</p>
                    <img src="{{ asset('assets/icons/bar-chart-icon.svg') }}"
                         alt="Total Views"
                         class="h-5 w-5 object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                </div>
                <div class="h-px bg-acmi-blueprimer"></div>
                <div class="px-5 py-4">
                    <h2 class="text-4xl font-bold text-black">
                        {{ number_format($totalViews ?? 0) }}
                    </h2>
                </div>
            </div>

        </div>

        {{-- Bottom panels --}}
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.2fr_0.8fr]">

            {{-- Recent Activity --}}
            <div class="rounded-2xl border border-acmi-blueprimer bg-white p-5">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h4 class="text-xl font-semibold text-acmi-darkblue">Recent Activity</h4>

                    <div class="inline-flex w-fit items-center gap-2 rounded-full bg-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-white">
                        <span>Post</span>
                        <img src="{{ asset('assets/icons/down.svg') }}"
                                     alt="Activity"
                                     class="h-4 w-4 object-contain"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                <img src="{{ asset('assets/icons/down.svg') }}"
                                     alt="Activity"
                                     class="h-4 w-4 object-contain"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $activity->description ?? '-' }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <span class="mt-2 h-2.5 w-2.5 rounded-full bg-lime-400"></span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No activity yet</p>
                    @endforelse
                </div>
            </div>

            {{-- New Members --}}
            <div class="rounded-2xl border border-acmi-blueprimer bg-white p-5">
                <h4 class="mb-4 text-xl font-semibold text-acmi-darkblue">New Member</h4>

                <div class="space-y-4">
                    @forelse($latestMembers as $member)
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 overflow-hidden rounded-full bg-gray-200">
                                <img
                                    src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/images/default-user.png') }}"
                                    alt="{{ $member->name }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900">
                                    {{ $member->name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $member->approved_at ? $member->approved_at->diffForHumans() : $member->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No new members</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection