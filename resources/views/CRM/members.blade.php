@extends('layouts.app')

@section('title', 'Members CRM - ACMI')
@section('page_title', 'Members CRM')

{{-- PINDAHIN SEARCH KE SINI BIAR SEJAJAR ADMIN ACCOUNT --}}
@section('header_right')
    <div class="hidden md:block w-64 mr-4">
        <div class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <form action="{{ route('members.index') }}" method="GET" id="searchForm">
                {{-- Tambahin input hidden biar filternya gak ilang pas search --}}
                <input type="hidden" name="industry" value="{{ request('industry', 'Semua') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members..."
                    class="w-full rounded-full border border-gray-200 py-2 pl-10 pr-4 text-sm focus:border-blue-500 outline-none shadow-sm bg-white transition-all">
            </form>
        </div>
    </div>
@endsection

@section('content')
    <style>
        #catSlider::-webkit-scrollbar {
            display: none;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 99;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Biar dropdown gak kepotong tabel */
        .table-responsive {
            overflow: visible !important;
        }
    </style>

    <div class="max-w-7xl mx-auto pb-10">

        <div class="flex items-center gap-3 mb-7">
            <div class="relative group flex-1">
                <div id="catSlider"
                    class="flex no-scrollbar overflow-x-auto scroll-smooth items-center gap-2 rounded-2xl border border-gray-200 bg-[#F6F6F6] p-1.5 w-max max-w-full">
                    @foreach ($industries as $ind)
                        @php $isAll = ($ind == 'Semua'); @endphp
                        <a href="{{ route('members.index', ['industry' => $isAll ? 'Semua' : $ind, 'search' => request('search')]) }}"
                            class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition {{ request('industry', 'Semua') == ($isAll ? 'Semua' : $ind) ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                            <span>{{ $isAll ? 'Semua' : $ind }}</span>
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                                {{ $isAll ? $totalMembers : $counts[$ind] ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm table-responsive">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#DAE6FF] text-[10px] border-b border-gray-400 font-bold text-black">
                    <tr>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-base text-[12px]"><i class="far fa-user"></i>
                                    Profile</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-base text-[12px]"><i
                                        class="far fa-address-card"></i>
                                    Contact</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-base text-[12px]"><i class="far fa-building"></i>
                                    Company</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-base text-[12px]"><i class="fas fa-cubes"></i>
                                    Industry</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-3 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-1 font-base text-[12px]"><i class="fas fa-briefcase"></i>
                                    Position</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-base text-[12px]"><i class="fas fa-link"></i>
                                    Company
                                    URL</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2 font-base text-[12px]">
                                <i class="far fa-dot-circle"></i> Action
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($members as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                            <td class="p-4 border-r border-gray-100 font-bold text-gray-800">{{ $item->name }}</td>
                            <td class="p-4 border-r border-gray-100">
                                <div class="text-[12px] text-gray-600">
                                    <p>{{ $item->email }}</p>
                                    <p class="text-gray-400">{{ $item->phone }}</p>
                                </div>
                            </td>
                            <td class="p-4 border-r border-gray-100">{{ $item->company_name }}</td>
                            <td class="p-4 border-r border-gray-100 text-center">
                                <span
                                    class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-bold">{{ $item->industry }}</span>
                            </td>
                            <td class="p-4 border-r border-gray-100">{{ $item->position }}</td>
                            <td class="p-4 border-r border-gray-100 text-center">
                                @if ($item->company_url)
                                    <a href="{{ $item->company_url }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        <i class="fas fa-link mr-1"></i> Visit
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="relative inline-block text-left dropdown">
                                    <button
                                        class="bg-gray-100 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2">
                                        Action <i class="fas fa-chevron-down text-[9px]"></i>
                                    </button>
                                    <div class="dropdown-content w-36 bg-white border border-gray-100 rounded-xl shadow-xl">
                                        <button onclick="openViewModal({{ $item->id }})"
                                            class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2"><i
                                                class="far fa-eye text-blue-500"></i> View</button>
                                        <button onclick="openEditModal({{ $item->id }})"
                                            class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 border-y border-gray-50"><i
                                                class="far fa-edit text-green-500"></i> Edit</button>
                                        <button onclick="openDeleteModal('{{ route('members.destroy', $item->id) }}')"
                                            class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 text-red-500"><i
                                                class="far fa-trash-alt"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
