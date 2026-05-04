@extends('layouts.app')

@section('title', 'Inbound CRM - ACMI')
@section('page_title', 'Inbound CRM')

@section('content')
    <div class="max-w-7xl mx-auto pb-10">

        <!-- top bar -->
        <div class="relative pointer-events-none">
            <div class="absolute -top-16 -mt-3 right-0 flex justify-end w-full">
                <form action="{{ route('inbound.index') }}" method="GET" class="relative group mr-48 pointer-events-auto">
                    <!-- pointer-events-auto biar search tetep bisa diketik -->
                    <i
                        class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm group-focus-within:text-[#0014A8] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts"
                        class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </form>
            </div>
        </div>
        <!-- 1. Statistics Cards -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 max-w-md mb-5">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-black flex items-center gap-3 text-md">
                    <i class="far fa-dot-circle text-gray-400"></i> Approval Status
                </h3>
                <button class="text-black"><i class="fas fa-ellipsis-h text-sm"></i></button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-0">
                <!-- Requested -->
                <div class="pr-4">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Requested</p>
                    <!-- Ini otomatis nambah kalo data di database lu nambah -->
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['requested'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400"><span class="text-blue-600 font-bold">+ 0</span> vs yesterday</p>
                </div>

                <!-- Approved (Ada garis kiri) -->
                <div
                    class="relative pl-6 pr-4 before:content-[''] before:absolute before:left-0 before:top-1 before:bottom-4 before:w-[1px] before:bg-gray-200">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Approved</p>
                    <!-- Ini otomatis nambah kalo status data jadi approved -->
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['approved'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400"><span class="text-gray-400 font-bold">0</span> vs yesterday</p>
                </div>

                <!-- Rejected -->
                <div
                    class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-1 before:bottom-4 before:w-[1px] before:bg-gray-200">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Rejected</p>
                    <!-- Ini otomatis nambah kalo status data jadi rejected -->
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['rejected'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400"><span class="text-gray-400 font-bold">0</span> vs yesterday</p>
                </div>
            </div>
        </div>

        <!-- 2. Filter Bar & Bulk Action -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex gap-4">
                <div class="relative">
                    <select onchange="window.location.href=this.value"
                        class="appearance-none bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2 text-sm outline-none shadow-sm cursor-pointer hover:border-gray-300 transition">
                        <option value="{{ route('inbound.index') }}">Filter Status</option>
                        <option value="{{ route('inbound.index', ['status' => 'requested']) }}"
                            {{ request('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="{{ route('inbound.index', ['status' => 'approved']) }}"
                            {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="{{ route('inbound.index', ['status' => 'rejected']) }}"
                            {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-3.5 text-[8px] text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <button onclick="approveAllSelected()"
                class="bg-black text-white px-3 py-2 rounded-lg text-sm font-base flex items-center gap-2 hover:bg-gray-800 transition shadow-sm">
                <i class="fas fa-check text-[10px]"></i> Approve all
            </button>
        </div>

        <!-- 3. Data Table -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#DBDBDB] border-b border-gray-400 text-[10.5px] uppercase font-bold text-black">
                    <tr>
                        <th class="p-4 w-10 text-center border-r border-gray-400">
                            <input type="checkbox" id="selectAll"
                                class="rounded border-gray-400 text-[#0014A8] focus:ring-[#0014A8]">
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="far fa-user"></i> Profile</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="far fa-address-card"></i>
                                    Contact</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="far fa-building"></i>
                                    Company</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="fas fa-cubes"></i>
                                    Industry</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="fas fa-briefcase"></i>
                                    Position</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 border-r border-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 font-medium"><i class="fas fa-link"></i> Company
                                    URL</span>
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                        <th class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2 font-medium">
                                <i class="far fa-dot-circle"></i> Action
                                <i class="fas fa-sort text-gray-400 text-[10px]"></i>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($inbounds as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50/50 transition cursor-pointer"
                            onclick="openDetailModal({{ $item->id }})">
                            <td class="p-4 text-center border-r border-gray-200" onclick="event.stopPropagation()">
                                <input type="checkbox" class="inbound-checkbox rounded border-gray-300 text-[#0014A8]"
                                    value="{{ $item->id }}">
                            </td>
                            <td class="p-4 border-r border-gray-200">
                                <p class="font-bold text-gray-800">{{ $item->name }}</p>
                                <p class="text-[10px] text-gray-400">Today at {{ $item->created_at->format('H.i') }}</p>
                            </td>
                            <td class="p-4 border-r border-gray-200 text-[11px]">
                                <div class="flex flex-col gap-1">
                                    <span class="flex items-center gap-2"><i class="far fa-envelope text-gray-400"></i>
                                        {{ $item->email }}</span>
                                    <span class="flex items-center gap-2"><i class="fas fa-phone-alt text-gray-400"></i>
                                        {{ $item->phone }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-medium border-r border-gray-200">{{ $item->company_name }}</td>
                            <td class="p-4 text-gray-500 border-r border-gray-200">{{ $item->industry }}</td>
                            <td class="p-4 text-gray-500 border-r border-gray-200">{{ $item->position }}</td>
                            <td class="p-4 border-r border-gray-200">
                                @if ($item->company_url)
                                    <a href="{{ $item->company_url }}" onclick="event.stopPropagation()" target="_blank"
                                        class="text-blue-600 border border-red-200 rounded-full px-3 py-1 text-[10px] bg-red-50/30 flex items-center w-fit gap-2 hover:bg-red-50 transition">
                                        <i class="fas fa-link text-[8px]"></i> {{ Str::limit($item->company_url, 20) }}
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center" onclick="event.stopPropagation()">
                                @if ($item->status == 'requested')
                                    <div class="flex justify-center gap-2">
                                        <button onclick="updateStatus({{ $item->id }}, 'rejected')"
                                            class="w-8 h-8 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 flex items-center justify-center transition"><i
                                                class="fas fa-times text-[10px]"></i></button>
                                        <button onclick="updateStatus({{ $item->id }}, 'approved')"
                                            class="w-8 h-8 border border-green-500 text-green-500 rounded-lg hover:bg-green-50 flex items-center justify-center transition"><i
                                                class="fas fa-check text-[10px]"></i></button>
                                    </div>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm inline-flex items-center gap-1 border
                                {{ $item->status == 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                                        {{ $item->status }} <i class="fas fa-chevron-down text-[8px]"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-gray-400 italic border-b border-gray-200">No
                                inbound data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-200">
                {{ $inbounds->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- 4. DETAIL MODAL -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[999] backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl m-4">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h2 class="text-lg font-bold text-gray-800">Member Detail Screen</h2>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition"><i
                        class="fas fa-times"></i></button>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <form id="modalForm">
                    <div class="mb-8">
                        <h3 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-4">Informasi Pribadi</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Nama</label>
                                <input type="text" id="m_name"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Email</label>
                                <input type="email" id="m_email"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Nomor Telepon</label>
                                <input type="text" id="m_phone"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Linkedin Profile</label>
                                <input type="text" id="m_linkedin"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-4">Informasi Perusahaan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Nama Perusahaan</label>
                                <input type="text" id="m_company"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Jabatan</label>
                                <input type="text" id="m_position"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Website Perusahaan</label>
                                <input type="text" id="m_url"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none bg-gray-50/30">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                <button onclick="closeDetailModal()"
                    class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-medium bg-white hover:bg-gray-50 transition">Close</button>
                <button
                    class="px-6 py-2.5 rounded-xl bg-[#0014A8] text-white text-sm font-bold shadow-lg hover:bg-blue-900 transition">Save
                    Changes</button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(id) {
            fetch(`/crm/inbound/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('m_name').value = data.name || '';
                    document.getElementById('m_email').value = data.email || '';
                    document.getElementById('m_phone').value = data.phone || '';
                    document.getElementById('m_linkedin').value = data.linkedin_url || '';
                    document.getElementById('m_company').value = data.company_name || '';
                    document.getElementById('m_position').value = data.position || '';
                    document.getElementById('m_url').value = data.company_url || '';

                    document.getElementById('detailModal').classList.remove('hidden');
                    document.getElementById('detailModal').classList.add('flex');
                });
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        document.getElementById('selectAll').onclick = function() {
            let checkboxes = document.querySelectorAll('.inbound-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        }

        function approveAllSelected() {
            let selectedIds = [];
            document.querySelectorAll('.inbound-checkbox:checked').forEach(cb => {
                selectedIds.push(cb.value);
            });

            if (selectedIds.length === 0) {
                alert('Pilih data dulu ya, Nis!');
                return;
            }

            if (confirm('Approve semua data yang dipilih?')) {
                fetch("{{ route('inbound.bulkApprove') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                }).then(() => window.location.reload());
            }
        }

        function updateStatus(id, status) {
            if (confirm(`Yakin mau set status ke ${status}?`)) {
                fetch(`/crm/inbound/${id}/status`, {
                    method: "PATCH",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status
                    })
                }).then(() => window.location.reload());
            }
        }
    </script>
@endsection
