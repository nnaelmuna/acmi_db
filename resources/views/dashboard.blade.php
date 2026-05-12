@extends('layouts.app')

@section('title', 'Dashboard CMS - ACMI')
@section('page_title', 'Dashboard')
@section('header_right')
    <a href="{{ route('admin.history') }}" class="flex items-center gap-2 px-3 py-2 bg-acmi-blueprimer hover:bg-[#0A1B89] text-white rounded-lg transition-all text-sm font-medium shrink-0">
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

            {{-- Bottom panels --}}
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.2fr_0.8fr]">

                {{-- Recent Activity --}}
                <div class="rounded-2xl border border-acmi-blueprimer bg-white p-5">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h4 class="text-xl font-semibold text-acmi-darkblue">Recent Activity</h4>

                        <div
                            class="inline-flex w-fit items-center gap-2 rounded-full bg-acmi-blueprimer px-3 py-1.5 text-xs font-medium text-white">
                            <span>Activity Logs</span>
                            <i class="fas fa-history text-[10px]"></i>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentActivities as $log)
                            <div
                                class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded-xl transition-all border-b border-gray-50 last:border-0">
                                {{-- Icon Dinamis --}}
                                <div
                                    class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ str_contains(strtolower($log->description), 'member') ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                    <i
                                        class="fas {{ str_contains(strtolower($log->description), 'member') ? 'fa-user-check' : 'fa-file-alt' }} text-sm"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-800 leading-tight">
                                        {{ $log->description ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-[10px] text-gray-400 flex items-center gap-1">
                                        <i class="far fa-clock"></i>
                                        {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                {{-- Indikator Status (Dot) --}}
                                <span
                                    class="mt-2 h-2 w-2 rounded-full {{ $loop->first ? 'bg-green-400 animate-pulse' : 'bg-gray-200' }}"></span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10">
                                <i class="fas fa-clipboard-list text-gray-200 text-4xl mb-2"></i>
                                <p class="text-sm text-gray-400">No activity yet</p>
                            </div>
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
