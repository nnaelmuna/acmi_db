@extends('layouts.app')

@section('title', 'Members CRM - ACMI')
@section('page_title', 'Members CRM')

@section('header_right')
    <div class="hidden md:block w-64 mr-4">
        <div class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <form action="{{ route('members.index') }}" method="GET" id="searchForm">
                {{-- Input hidden supaya pas search, filter status & industry nggak hilang --}}
                <input type="hidden" name="status" value="{{ request('status', 'published') }}">
                <input type="hidden" name="industry" value="{{ request('industry') }}">
                
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

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
            <x-filters-tab :tabs="$tabs" />

            <x-filters-dropdown-category :categories="$categories" routeName="members.index" />
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
        <button class="bg-gray-100 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2">
            Action <i class="fas fa-chevron-down text-[9px]"></i>
        </button>
        <div class="dropdown-content w-36 bg-white border border-gray-100 rounded-xl shadow-xl">
            
            {{-- CEK APAKAH LAGI DI TAB TRASH --}}
            @if(request('status') == 'trash')
                {{-- TOMBOL RESTORE --}}
                <form action="{{ route('members.restore', $item->id) }}" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 text-green-600">
                        <i class="fas fa-undo"></i> Restore
                    </button>
                </form>

                {{-- TOMBOL DELETE PERMANENT (Opsional) --}}
                <button onclick="openDeleteModal('{{ route('members.destroy', $item->id) }}')"
                    class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 text-red-500 border-t border-gray-50">
                    <i class="far fa-trash-alt"></i> Force Delete
                </button>
            @else
                {{-- TAMPILAN NORMAL (View, Edit, Delete) --}}
                <button onclick="openViewModal({{ $item->id }})"
                    class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2">
                    <i class="far fa-eye text-blue-500"></i> View
                </button>
                <button onclick="openEditModal({{ $item->id }})"
                    class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 border-y border-gray-50">
                    <i class="far fa-edit text-green-500"></i> Edit
                </button>
                <button onclick="openDeleteModal('{{ route('members.destroy', $item->id) }}')"
                    class="w-full text-left px-4 py-2 text-[11px] hover:bg-gray-50 flex items-center gap-2 text-red-500">
                    <i class="far fa-trash-alt"></i> Delete
                </button>
            @endif

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
   <div id="editModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">Member Data Update</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div>
                        <h4 class="text-blue-700 font-bold text-sm mb-4">Informasi Pribadi</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Nama</label>
                                <input type="text" name="name" id="edit_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all" placeholder="Nama Lengkap">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Email</label>
                                <input type="email" name="email" id="edit_email" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all" placeholder="email@gmail.com">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Nomor Telepon</label>
                                <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all" placeholder="08xxxx">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Linkedin Profile</label>
                                <input type="text" name="linkedin" id="edit_linkedin" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all" placeholder="linkedin.com/in/...">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-blue-700 font-bold text-sm mb-4">Informasi Perusahaan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Nama Perusahaan</label>
                                <input type="text" name="company_name" id="edit_company_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Jabatan</label>
                                <input type="text" name="position" id="edit_position" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Industri</label>
                                <input type="text" name="industry" id="edit_industry" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Website Perusahaan</label>
                                <input type="text" name="company_url" id="edit_company_url" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm transition-all">
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-white transition-all">Save to draft</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-800 text-white text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-200 transition-all">Publish Now</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">Member Detail Screen</h3>
                <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">
                <div>
                    <h4 class="text-blue-700 font-bold text-sm mb-4">Informasi Pribadi</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Nama Lengkap</label>
                            <p id="view_name" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Email Address</label>
                            <p id="view_email" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Nomor Telepon</label>
                            <p id="view_phone" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Linkedin Profile</label>
                            <p id="view_linkedin" class="text-sm text-blue-600 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 truncate">-</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-blue-700 font-bold text-sm mb-4">Informasi Perusahaan</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Perusahaan</label>
                            <p id="view_company_name" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jabatan</label>
                            <p id="view_position" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Industri</label>
                            <p id="view_industry" class="text-sm text-gray-800 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Website</label>
                            <p id="view_company_url" class="text-sm text-blue-600 font-semibold bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end border-t border-gray-100">
                <button onclick="closeModal('viewModal')" class="px-8 py-2.5 rounded-xl bg-acmi-blueprimer text-white text-sm font-bold hover:bg-[#0A1B89] transition-all">Close Detail</button>
            </div>
        </div>
    </div>
@endsection

<script>
    function openEditModal(id) {
    // 1. Ambil data member via AJAX (sesuai function show di controller kamu)
    fetch(`/crm/members/${id}`)
        .then(response => response.json())
        .then(data => {
            // 2. Isi semua field input di modal edit
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_phone').value = data.phone || '';
            document.getElementById('edit_company_name').value = data.company_name;
            document.getElementById('edit_industry').value = data.industry;
            document.getElementById('edit_position').value = data.position;
            document.getElementById('edit_company_url').value = data.company_url || '';

            // 3. Update Action Form agar mengarah ke ID yang benar
            document.getElementById('editForm').action = `/crm/members/${id}`;

            // 4. Munculkan Modal
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Biar gak bisa scroll background
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function openViewModal(id) {
    // Ambil data via AJAX
    fetch(`/crm/members/${id}`)
        .then(response => response.json())
        .then(data => {
            // Pasang data ke element (pakai innerText karena ini label/p, bukan input)
            document.getElementById('view_name').innerText = data.name;
            document.getElementById('view_email').innerText = data.email;
            document.getElementById('view_phone').innerText = data.phone || '-';
            document.getElementById('view_linkedin').innerText = data.linkedin || '-';
            document.getElementById('view_company_name').innerText = data.company_name;
            document.getElementById('view_industry').innerText = data.industry;
            document.getElementById('view_position').innerText = data.position;
            document.getElementById('view_company_url').innerText = data.company_url || '-';

            // Tampilkan modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => alert('Gagal mengambil data member!'));
}
</script>
