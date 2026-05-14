@extends('layouts.app')

@section('title', 'Members CRM - ACMI')
@section('page_title', 'Members CRM')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('header_right')
    <div class="hidden md:block w-64 mr-4">
        <form action="{{ route('members.index') }}" method="GET" class="relative group">
            <input type="hidden" name="status" value="{{ request('status', 'published') }}">
            <input type="hidden" name="category" value="{{ request('category') }}">

            <i
                class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm group-focus-within:text-acmi-blueprimer transition"></i>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members..."
                class="w-full rounded-full border border-acmi-bordercolor bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 shadow-sm outline-none transition focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
        </form>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div id="successAlert" class="mb-4 rounded-xl bg-green-100 p-4 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-600">
            <p class="mb-2 font-semibold">Validation Error</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-7xl mx-auto pb-10">
        <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            <div class="inline-flex items-center gap-1 rounded-2xl border border-acmi-bordercolor bg-[#F6F6F6] p-1.5 w-fit">
                <a href="{{ route('members.index', ['status' => 'published'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status', 'published') === 'published' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Published</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['published'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('members.index', ['status' => 'draft'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'draft' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Draft</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['draft'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('members.index', ['status' => 'archived'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'archived' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Archived</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-black text-[10px] font-medium text-white">
                        {{ $statusCounts['archived'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('members.index', ['status' => 'trash'] + request()->except('status')) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
                    {{ request('status') === 'trash' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-black' }}">
                    <span>Trash</span>
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-medium text-white">
                        {{ $statusCounts['trash'] ?? 0 }}
                    </span>
                </a>
            </div>

            <div class="flex items-center justify-end">
                <x-filters-dropdown-category :categories="$categories" routeName="members.index" />
            </div>
        </div>

        <div class="flex min-h-[calc(100vh-230px)] flex-col">
            <div class="bg-white rounded-xl border border-acmi-bordercolor shadow-sm overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-acmi-softblue text-[10px] border-b border-acmi-bordercolor font-bold text-black">
                        <tr>
                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="far fa-user text-acmi-blueprimer"></i> Profile
                                </span>
                            </th>

                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="far fa-address-card text-acmi-blueprimer"></i> Contact
                                </span>
                            </th>

                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="far fa-building text-acmi-blueprimer"></i> Company
                                </span>
                            </th>

                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="fas fa-cubes text-acmi-blueprimer"></i> Industry
                                </span>
                            </th>

                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="fas fa-briefcase text-acmi-blueprimer"></i> Position
                                </span>
                            </th>

                            <th class="p-4 border-r border-acmi-bordercolor">
                                <span class="flex items-center gap-2 font-base text-[12px]">
                                    <i class="fas fa-link text-acmi-blueprimer"></i> Company URL
                                </span>
                            </th>

                            <th class="p-4 text-center">
                                <span class="flex items-center justify-center gap-2 font-base text-[12px]">
                                    <i class="far fa-dot-circle text-acmi-blueprimer"></i> Action
                                </span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="text-sm text-gray-700">
                        @forelse($members as $item)
                            <tr class="border-b border-acmi-bordercolor/70 hover:bg-acmi-softblue/30 transition">
                                <td class="p-4 border-r border-acmi-bordercolor/70">
                                    <p class="font-bold text-gray-800">{{ $item->name }}</p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ optional($item->created_at)->format('d M Y, H.i') }}
                                    </p>
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70 text-[11px]">
                                    <div class="flex flex-col gap-1">
                                        <span class="flex items-center gap-2">
                                            <i class="far fa-envelope text-acmi-blueaccent"></i>
                                            {{ $item->email }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-phone-alt text-acmi-blueaccent"></i>
                                            {{ $item->phone ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70">
                                    {{ $item->company_name ?? '-' }}
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70 text-center">
                                    <span
                                        class="rounded-full bg-acmi-softblue px-3 py-1 text-[10px] font-bold text-acmi-blueprimer">
                                        {{ $item->industry ?? '-' }}
                                    </span>
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70">
                                    {{ $item->position ?? '-' }}
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70">
                                    @if ($item->company_url)
                                        <a href="{{ $item->company_url }}" target="_blank"
                                            class="text-acmi-blueprimer border border-acmi-blueaccent/30 rounded-full px-3 py-1 text-[10px] bg-acmi-softblue/40 flex items-center w-fit gap-2 hover:bg-acmi-softblue transition">
                                            <i class="fas fa-link text-[8px]"></i>
                                            {{ Str::limit($item->company_url, 24) }}
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    <div class="relative inline-block text-left group">
                                        @if (request('status') === 'trash')
                                            <button type="button"
                                                class="inline-flex items-center justify-between w-32 px-3 py-1.5 rounded-full text-[10px] font-bold border bg-gray-100 text-gray-700 border-gray-200 transition">
                                                Trash Action
                                                <i class="fas fa-chevron-down text-[8px] ml-1"></i>
                                            </button>

                                            <div
                                                class="absolute right-0 mt-1 w-44 bg-white border border-acmi-bordercolor rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] overflow-hidden">
                                                <form action="{{ route('members.restore', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-green-600 hover:bg-green-50 transition">
                                                        <i class="fas fa-undo mr-2"></i> Restore
                                                    </button>
                                                </form>

                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('members.forceDelete', $item->id) }}', 'Permanently delete this member? This action cannot be undone.')"
                                                    class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-red-600 hover:bg-red-50 transition border-t border-acmi-bordercolor/50">
                                                    <i class="far fa-trash-alt mr-2"></i> Permanently Delete
                                                </button>
                                            </div>
                                        @else
                                            <button type="button"
                                                class="inline-flex items-center justify-between w-28 px-3 py-1.5 rounded-full text-[10px] font-bold border bg-acmi-softblue text-acmi-blueprimer border-acmi-blueaccent/30 transition">
                                                Action
                                                <i class="fas fa-chevron-down text-[8px] ml-1"></i>
                                            </button>

                                            <div
                                                class="absolute right-0 mt-1 w-40 bg-white border border-acmi-bordercolor rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] overflow-hidden">
                                                <button type="button" onclick="openViewModal({{ $item->id }})"
                                                    class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-acmi-blueprimer hover:bg-acmi-softblue transition">
                                                    <i class="far fa-eye mr-2"></i> View Detail
                                                </button>

                                                <button type="button" onclick="openEditModal({{ $item->id }})"
                                                    class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-green-600 hover:bg-green-50 transition border-t border-acmi-bordercolor/50">
                                                    <i class="far fa-edit mr-2"></i> Edit
                                                </button>

                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('members.destroy', $item->id) }}', 'Move this member to trash?')"
                                                    class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-red-600 hover:bg-red-50 transition border-t border-acmi-bordercolor/50">
                                                    <i class="far fa-trash-alt mr-2"></i> Delete
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400 italic">
                                    No members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-auto">
                <x-pagination :paginator="$members" />
            </div>
        </div>
    </div>

    <div id="viewModal" onclick="closeModal('viewModal')"
        class="hidden fixed inset-0 z-[100] bg-black/50 items-center justify-center p-4 backdrop-blur-sm">
        <div onclick="event.stopPropagation()" class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-acmi-bordercolor bg-acmi-softblue/20">
                <h3 class="text-lg font-bold text-gray-800">Member Detail Information</h3>
                <button onclick="closeModal('viewModal')"
                    class="text-acmi-blueprimer hover:text-acmi-darkblue transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
                <div>
                    <h4 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                        Personal Information
                    </h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Name</p>
                            <p id="view_name" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Email</p>
                            <p id="view_email" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Phone</p>
                            <p id="view_phone" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">LinkedIn</p>
                            <p id="view_linkedin" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                        Company Information
                    </h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Company Name</p>
                            <p id="view_company_name" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Industry</p>
                            <p id="view_industry" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Position</p>
                            <p id="view_position" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Company URL</p>
                            <p id="view_company_url" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" onclick="closeModal('editModal')"
        class="hidden fixed inset-0 z-[100] bg-black/50 items-center justify-center p-4 backdrop-blur-sm">
        <div onclick="event.stopPropagation()" class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-acmi-bordercolor bg-acmi-softblue/20">
                <h3 class="text-lg font-bold text-gray-800">Edit Member</h3>
                <button onclick="closeModal('editModal')"
                    class="text-acmi-blueprimer hover:text-acmi-darkblue transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" id="edit_status" value="published">

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
                    <div>
                        <h4 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                            Personal Information
                        </h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Name</label>
                                <input type="text" name="name" id="edit_name"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Email</label>
                                <input type="email" name="email" id="edit_email"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Phone</label>
                                <input type="text" name="phone" id="edit_phone"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">LinkedIn</label>
                                <input type="text" name="linkedin" id="edit_linkedin"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                            Company Information
                        </h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Company Name</label>
                                <input type="text" name="company_name" id="edit_company_name"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Industry</label>
                                <input type="text" name="industry" id="edit_industry"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Position</label>
                                <input type="text" name="position" id="edit_position"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-800 block mb-1">Company URL</label>
                                <input type="text" name="company_url" id="edit_company_url"
                                    class="w-full rounded-xl border border-acmi-bordercolor px-4 py-2.5 text-sm outline-none focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-acmi-softblue/20 flex justify-end gap-3 border-t border-acmi-bordercolor">
                    <button type="submit" onclick="setEditStatus('draft')"
                        class="px-6 py-2.5 rounded-xl border border-acmi-bordercolor bg-white text-sm font-bold text-gray-600 hover:bg-acmi-softblue/40 transition">
                        Save to Draft
                    </button>

                    <button type="submit" onclick="setEditStatus('archived')"
                        class="px-6 py-2.5 rounded-xl border border-acmi-yellowaccent bg-white text-sm font-bold text-acmi-yellowaccent hover:bg-acmi-yellowaccent hover:text-white transition">
                        Archive
                    </button>

                    <button type="submit" onclick="setEditStatus('published')"
                        class="px-6 py-2.5 rounded-xl bg-acmi-blueprimer text-white text-sm font-bold hover:bg-acmi-darkblue transition">
                        Publish Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <form id="delete-item-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        const successAlert = document.getElementById('successAlert');

        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
            }, 2500);

            setTimeout(() => {
                successAlert.remove();
            }, 3000);
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openViewModal(id) {
            fetch(`/crm/members/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_name').innerText = data.name || '-';
                    document.getElementById('view_email').innerText = data.email || '-';
                    document.getElementById('view_phone').innerText = data.phone || '-';
                    document.getElementById('view_linkedin').innerText = data.linkedin || '-';
                    document.getElementById('view_company_name').innerText = data.company_name || '-';
                    document.getElementById('view_industry').innerText = data.industry || '-';
                    document.getElementById('view_position').innerText = data.position || '-';
                    document.getElementById('view_company_url').innerText = data.company_url || '-';

                    openModal('viewModal');
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to load member detail.', 'error');
                });
        }

        function openEditModal(id) {
            fetch(`/crm/members/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_phone').value = data.phone || '';
                    document.getElementById('edit_linkedin').value = data.linkedin || '';
                    document.getElementById('edit_company_name').value = data.company_name || '';
                    document.getElementById('edit_industry').value = data.industry || '';
                    document.getElementById('edit_position').value = data.position || '';
                    document.getElementById('edit_company_url').value = data.company_url || '';
                    document.getElementById('edit_status').value = data.status || 'published';

                    document.getElementById('editForm').action = `/crm/members/${id}`;

                    openModal('editModal');
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to load member data.', 'error');
                });
        }

        function setEditStatus(status) {
            document.getElementById('edit_status').value = status;
        }

        function openDeleteModal(actionUrl, message = 'Are you sure want to delete this member?') {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1120B0',
                cancelButtonColor: '#F3F4F6',
                confirmButtonText: 'Yes, continue',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[32px] p-6',
                    confirmButton: 'rounded-full px-8 py-2.5 text-sm font-bold ml-2',
                    cancelButton: 'rounded-full px-8 py-2.5 text-sm font-bold text-gray-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-item-form');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        }
    </script>
@endpush
