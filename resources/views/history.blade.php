@extends('layouts.app')

@section('title', 'History Recap - ACMI')
@section('page_title', 'History Statistics')

@section('content')
<div class="w-full space-y-6">
    {{-- Card Utama --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-acmi-darkblue">Monthly Growth Recap</h3>
            <span class="text-xs font-medium px-3 py-1 bg-blue-50 text-blue-600 rounded-full">Real-time Data</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                        <th class="py-4 px-4 text-sm font-semibold text-blue-600 uppercase tracking-wider">Total Members</th>
                        <th class="py-4 px-4 text-sm font-semibold text-green-600 uppercase tracking-wider">New Members</th>
                        <th class="py-4 px-4 text-sm font-semibold text-purple-600 uppercase tracking-wider">Total Views</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(array_reverse($monthlyRecap) as $data)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-4 text-sm font-bold text-gray-800">{{ $data['month'] }}</td>
                        <td class="py-4 px-4 text-sm text-gray-600 font-medium">
                            {{ number_format($data['total_member']) }}
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <span class="px-2 py-1 bg-green-50 text-green-600 rounded-md font-bold text-xs">
                                +{{ number_format($data['new_member']) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">
                            {{ number_format($data['views']) }} <span class="text-[10px] text-gray-400">Views</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-sm text-gray-400">No data records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tombol Back --}}
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium bg-acmi-blueprimer px-3 rounded-lg py-2 text-white hover:bg-[#0A1B89] transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Dashboard</span>
    </a>
</div>
@endsection