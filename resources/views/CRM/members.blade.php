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

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members"
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

            <x-filters-tab-crm :tabs="[
                [
                    'label' => 'All Member',
                    'value' => 'all',
                    'count' =>
                        ($statusCounts['published'] ?? 0) +
                        ($statusCounts['draft'] ?? 0) +
                        ($statusCounts['archived'] ?? 0),
                ],
                [
                    'label' => 'Trash',
                    'value' => 'trash',
                    'count' => $statusCounts['trash'] ?? 0,
                ],
            ]" />

            <div class="flex items-center justify-end">
                <x-filters-dropdown-category :categories="$categories" routeName="members.index" />
            </div>
        </div>

        <div class="flex min-h-[calc(100vh-230px)] flex-col">
            <div class="overflow-x-auto rounded-xl border border-acmi-bordercolor bg-white shadow-sm">
                <table class="min-w-[1200px] w-full text-left border-collapse">
                    <thead class="bg-acmi-softblue text-[10px] border-b border-acmi-bordercolor font-bold text-black">
                        <tr>
                            {{-- Checkbox Select All --}}
                            <th class="p-4 w-10 text-center border-r border-acmi-bordercolor">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-acmi-bordercolor text-acmi-blueprimer focus:ring-acmi-blueprimer cursor-pointer">
                            </th>

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
                            <tr class="border-b border-acmi-bordercolor/70 hover:bg-acmi-softblue/30 transition cursor-pointer"
                                onclick="openDetailModal({{ $item->id }})">
                                
                                {{-- Individual Checkbox dengan Stop Propagation --}}
                                <td class="p-4 text-center border-r border-acmi-bordercolor/70"
                                    onclick="event.stopPropagation()">
                                    <input type="checkbox"
                                        class="member-checkbox rounded border-acmi-bordercolor text-acmi-blueprimer focus:ring-acmi-blueprimer cursor-pointer"
                                        value="{{ $item->id }}">
                                </td>
                                
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

                                <td class="p-4 border-r border-acmi-bordercolor/70 font-medium">
                                    {{ $item->company_name ?? '-' }}
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70 text-center">
                                    <span class="rounded-full bg-acmi-softblue px-3 py-1 text-[10px] font-bold text-acmi-blueprimer">
                                        {{ $item->industry ?? '-' }}
                                    </span>
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70 text-gray-500">
                                    {{ $item->position ?? '-' }}
                                </td>

                                <td class="p-4 border-r border-acmi-bordercolor/70">
                                    @if ($item->company_url)
                                        <a href="{{ $item->company_url }}" onclick="event.stopPropagation()" target="_blank"
                                            class="text-acmi-blueprimer border border-acmi-blueaccent/30 rounded-full px-3 py-1 text-[10px] bg-acmi-softblue/40 flex items-center w-fit gap-2 hover:bg-acmi-softblue transition">
                                            <i class="fas fa-link text-[8px]"></i>
                                            {{ Str::limit($item->company_url, 24) }}
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>

                                <td class="p-4 text-center" onclick="event.stopPropagation()">
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
                                                    onclick="openDeleteModal('{{ route('members.forceDelete', $item->id) }}', 'Permanently delete this member? This action cannot be undone.', 'Yes, delete permanently')"
                                                    class="flex items-center w-full px-4 py-2.5 text-[11px] font-semibold text-red-600 hover:bg-red-50 transition border-t border-acmi-bordercolor/50">
                                                    <i class="far fa-trash-alt mr-2"></i> Permanently Delete
                                                </button>
                                            </div>
                                        @else
                                            @if($item->sub_status === 'active')
                                                <button type="button"
                                                    class="inline-flex items-center justify-center w-28 px-4 py-2 rounded-full text-[11px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100 transition duration-150 cursor-pointer shadow-sm">
                                                    Active
                                                    <i class="fas fa-chevron-down text-[9px] ml-2 text-emerald-700"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="inline-flex items-center justify-center w-28 px-4 py-2 rounded-full text-[11px] font-bold border bg-red-50 text-red-700 border-red-200 hover:bg-red-100 transition duration-150 cursor-pointer shadow-sm">
                                                    Unactive
                                                    <i class="fas fa-chevron-down text-[9px] ml-2 text-red-700"></i>
                                                </button>
                                            @endif

                                            <div
                                                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] py-1.5 p-1">
                                                <form action="{{ route('members.updateSubStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    @if($item->sub_status === 'active')
                                                        <input type="hidden" name="sub_status" value="unactive">
                                                        <button type="submit"
                                                            class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-red-500 hover:bg-red-50 rounded-lg transition text-left">
                                                            <i class="far fa-times-circle mr-2.5 text-[12px]"></i> Set Unactive
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="sub_status" value="active">
                                                        <button type="submit"
                                                            class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-green-600 hover:bg-green-50 rounded-lg transition text-left">
                                                            <i class="far fa-check-circle mr-2.5 text-[12px]"></i> Set Active
                                                        </button>
                                                    @endif
                                                </form>
                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('members.destroy', $item->id) }}', 'Are you sure want to move this member to trash?')"
                                                    class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-gray-600 hover:bg-red-50 rounded-lg transition text-left cursor-pointer border-t border-gray-100/70 mt-1">
                                                    <i class="far fa-trash-alt mr-2.5 text-[12px]"></i> Move To Trash
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-10 text-center text-gray-400 italic">
                                    No members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-auto pt-4">
                <x-pagination :paginator="$members" />
            </div>
        </div>
    </div>

    {{-- Detail Modal Pop-up --}}
    <div id="detailModal" onclick="closeDetailModal()"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[999] backdrop-blur-sm">
        <div onclick="event.stopPropagation()"
            class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl m-4">
            <div class="p-6 border-b border-acmi-bordercolor flex justify-between items-center bg-acmi-softblue/20">
                <h2 class="text-lg font-bold text-gray-800">Member Detail Information</h2>

                <button onclick="closeDetailModal()" class="text-acmi-blueprimer hover:text-acmi-darkblue transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto bg-gray-50/50">
                <div class="mb-6">
                    <h3 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-3">
                        Informasi Pribadi
                    </h3>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_name">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 break-all min-h-[38px] flex items-center">
                                <span id="d_email">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_phone">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Linkedin Profile</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 break-all min-h-[38px] flex items-center">
                                <span id="d_linkedin">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-3">
                        Informasi Perusahaan
                    </h3>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Perusahaan</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_company">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jabatan</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_position">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Industri</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_industry">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Website Perusahaan</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 break-all min-h-[38px] flex items-center">
                                <span id="d_url">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jumlah Karyawan</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_employee_size">-</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Annual Revenue</label>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[38px] flex items-center">
                                <span id="d_annual_revenue">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-3">
                        Pesan / Catatan Tambahan
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <div class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-sm font-medium text-gray-800 min-h-[100px] whitespace-pre-line items-start">
                                <span id="d_message">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-item-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        // Logika Sinkronisasi Checkbox (Select All)
        document.getElementById('selectAll').onclick = function() {
            let checkboxes = document.querySelectorAll('.member-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        }

        function openDetailModal(id) {
            fetch(`/crm/members/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('d_name').innerText = data.name || '-';
                    document.getElementById('d_email').innerText = data.email || '-';
                    document.getElementById('d_phone').innerText = data.phone || '-';
                    document.getElementById('d_linkedin').innerText = data.linkedin_url || data.linkedin || '-';

                    document.getElementById('d_company').innerText = data.company_name || '-';
                    document.getElementById('d_position').innerText = data.position || '-';
                    document.getElementById('d_industry').innerText = data.industry || '-';
                    document.getElementById('d_url').innerText = data.company_url || '-';
                    document.getElementById('d_employee_size').innerText = data.employee_size || '-';
                    document.getElementById('d_annual_revenue').innerText = data.annual_revenue || '-';

                    document.getElementById('d_message').innerText = data.message || data.motivation_referral || '-';

                    document.getElementById('detailModal').classList.remove('hidden');
                    document.getElementById('detailModal').classList.add('flex');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    console.error("Gagal mengambil data detail:", error);
                    Swal.fire('Oops!', 'Gagal memuat detail data.', 'error');
                });
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

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

        function openDeleteModal(url, text, confirmText = 'Yes, confirm') {
            Swal.fire({
                title: `<span style="font-size: 18px; font-weight: 700;">Are you sure?</span>`,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E15B5B',
                cancelButtonColor: '#F3F4F6',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[23px] p-6',
                    confirmButton: 'rounded-xl px-6 py-2.5 text-sm font-bold ml-2',
                    cancelButton: 'rounded-lg px-6 py-2.5 text-sm font-bold text-gray-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-item-form');
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>
@endpush