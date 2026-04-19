@extends('layouts.app')

@section('title', 'Dashboard CMS - ACMI')
@section('page_title', 'Dashboard')

@section('content')
<div class="w-full">

    {{-- CARD SECTION --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Total Member --}}
        <div class="bg-white rounded-xl border border-[#1120B0]/20 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Member</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-black mt-2">
                    {{ number_format($totalMember ?? 0) }}
                </h2>
            </div>

            <div class="bg-[#1120B0]/10 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#1120B0]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003Z"/>
                </svg>
            </div>
        </div>

        {{-- New Member --}}
        <div class="bg-white rounded-xl border border-[#1120B0]/20 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">New Member</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-black mt-2">
                    {{ number_format($newMember ?? 0) }}
                </h2>
            </div>

            <div class="bg-green-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4.5a7.5 7.5 0 0 0-7.5 7.5h15A7.5 7.5 0 0 0 12 4.5Z"/>
                </svg>
            </div>
        </div>

        {{-- Total Views --}}
        <div class="bg-white rounded-xl border border-[#1120B0]/20 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Views</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-black mt-2">
                    {{ number_format($totalViews ?? 0) }}
                </h2>
            </div>

            <div class="bg-purple-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13.5s3.75-7.5 9-7.5 9 7.5 9 7.5-3.75 7.5-9 7.5-9-7.5-9-7.5Z"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- SECOND SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h4>

            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex justify-between items-start border-b pb-3">
                        <div>
                            <p class="text-sm text-gray-700">
                                {{ $activity->description ?? '-' }}
                            </p>
                            <span class="text-xs text-gray-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No activity yet</p>
                @endforelse
            </div>
        </div>

        {{-- New Members --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">New Members</h4>

            <div class="space-y-4">
                @forelse($latestMembers as $member)
                    <div class="flex items-center gap-4 border-b pb-3">

                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img 
                                src="{{ $member->photo ? asset('storage/'.$member->photo) : asset('images/default-user.png') }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        {{-- Info --}}
                        <div>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $member->name }}
                            </p>
                            <span class="text-xs text-gray-400">
                                {{ $member->approved_at ? $member->approved_at->diffForHumans() : $member->created_at->diffForHumans() }}
                            </span>
                        </div>

                    </div>
                @empty
                    <p class="text-sm text-gray-400">No new members</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection