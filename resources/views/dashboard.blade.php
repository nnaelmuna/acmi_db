@extends('layouts.app')

@section('title', 'Dashboard CMS - ACMI')
@section('page_title', 'Dashboard')
@section('header_right')
    <a href="{{ route('admin.history') }}"
        class="flex items-center gap-2 px-3 py-2 bg-acmi-blueprimer hover:bg-[#0A1B89] text-white rounded-lg transition-all text-sm font-medium shrink-0">
        <i class="fas fa-history"></i>
        <span class="hidden md:inline">History</span>
    </a>
@endsection

@section('content')
    <div class="w-full">
        <div class="flex flex-col gap-6">

            {{-- Cards --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

                {{-- Total Member --}}
                <div class="rounded-2xl border border-acmi-blueprimer bg-white overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3">
                        <p class="text-sm font-medium text-gray-700">Total Member</p>
                        <i class="fas fa-users h-5 w-5"></i>
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
                        <i class="fas fa-user-plus h-5 w-5"></i>
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
                        <i class="fas fa-bar-chart"></i>
                    </div>
                    <div class="h-px bg-acmi-blueprimer"></div>
                    <div class="px-5 py-4">
                        <h2 class="text-4xl font-bold text-black">
                            {{ number_format($totalViews ?? 0) }}
                        </h2>
                    </div>
                </div>

            </div>

            {{-- Bottom Panels --}}
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.2fr_0.8fr] pb-10">

                {{-- Recent Activity --}}
                <div x-data="{ expanded: false }"
                    class="rounded-2xl border border-acmi-blueprimer bg-white p-5 shadow-sm relative">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold text-acmi-darkblue">
                                Recent Activity
                            </h4>
                            <p class="mt-1 text-xs text-gray-400">
                                Latest actions from your account
                            </p>
                        </div>

                        <div
                            class="inline-flex w-fit items-center gap-2 rounded-full bg-acmi-softblue px-3 py-1.5 text-xs font-semibold text-acmi-blueprimer">
                            <span>Recent Activity</span>
                            <i class="fas fa-history text-[10px]"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @forelse($recentActivities as $index => $log)
                            <div x-show="expanded || {{ $index }} < 5" x-transition.opacity
                                style="{{ $index >= 5 ? 'display: none;' : '' }}"
                                class="flex items-start gap-3 rounded-xl px-3 py-3 transition hover:bg-acmi-softblue/40">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-acmi-softblue text-acmi-blueprimer">
                                    <i class="fas fa-clock-rotate-left text-sm"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium leading-snug text-gray-800">
                                        {{ $log->description ?? '-' }}
                                    </p>

                                    <div class="mt-1 flex items-center gap-2 text-[11px] text-gray-400">
                                        <span>{{ $log->user->name ?? 'Super Admin' }}</span>
                                        <span>•</span>
                                        <span>{{ optional($log->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-200 py-10">
                                <i class="fas fa-clipboard-list mb-2 text-3xl text-gray-200"></i>
                                <p class="text-sm text-gray-400">No activity yet</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($recentActivities->count() > 5)
                        <button x-show="!expanded" @click="expanded = true"
                            class="w-full mt-4 py-2 text-sm font-semibold text-acmi-blueprimer bg-acmi-softblue hover:bg-[#E0E7FF] rounded-xl transition-colors">
                            View 5 More
                        </button>
                        <button x-show="expanded" @click="expanded = false"
                            class="w-full mt-4 py-2 text-sm font-semibold text-acmi-blueprimer bg-acmi-softblue hover:bg-[#E0E7FF] rounded-xl transition-colors"
                            style="display: none;">
                            View Less
                        </button>
                    @endif
                </div>
                {{-- New Members --}}
                <div class="rounded-2xl border border-acmi-blueprimer bg-white p-5 self-start">
                    <h4 class="mb-4 text-xl font-semibold text-acmi-darkblue">New Member</h4>

                    <div class="space-y-4">
                        @forelse($latestMembers as $member)
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 overflow-hidden rounded-full bg-gray-200">
                                    <img src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/images/default-user.png') }}"
                                        alt="{{ $member->name }}" class="h-full w-full object-cover">
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
