@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('title', 'Inbound CRM - ACMI')
@section('page_title', 'Inbound CRM')

@section('content')
    <div class="max-w-7xl mx-auto pb-10">

        <div class="relative pointer-events-none">
            <div class="absolute -top-16 -mt-3 right-0 flex justify-end w-full">
                <form action="{{ route('inbound.index') }}" method="GET" class="relative group mr-48 pointer-events-auto">
                    <i
                        class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm group-focus-within:text-acmi-blueprimer transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inbound"
                        class="w-full rounded-full border border-acmi-bordercolor bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
                </form>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-acmi-bordercolor/50 max-w-md mb-5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-black flex items-center gap-3 text-md">
                    <i class="far fa-dot-circle text-acmi-blueaccent"></i> Approval Status
                </h3>
                <button class="text-acmi-darkblue">
                    <i class="fas fa-ellipsis-h text-sm"></i>
                </button>
            </div>

            <div class="grid grid-cols-3 gap-0">
                <div class="pr-4">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Review</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['review'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">
                        <span class="text-acmi-blueprimer font-bold">
                            {{ ($diffs['review'] ?? 0) >= 0 ? '+ ' : '- ' }}{{ abs($diffs['review'] ?? 0) }}
                        </span> vs yesterday
                    </p>
                </div>

                <div
                    class="relative pl-6 pr-4 before:content-[''] before:absolute before:left-0 before:top-1 before:bottom-4 before:w-[1px] before:bg-acmi-bordercolor">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Approved</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['approved'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">
                        <span class="text-acmi-blueprimer font-bold">
                            {{ ($diffs['approved'] ?? 0) >= 0 ? '+ ' : '- ' }}{{ abs($diffs['approved'] ?? 0) }}
                        </span> vs yesterday
                    </p>
                </div>

                <div
                    class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-1 before:bottom-4 before:w-[1px] before:bg-acmi-bordercolor">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Rejected</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['rejected'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">
                        <span class="text-acmi-blueprimer font-bold">
                            {{ ($diffs['rejected'] ?? 0) >= 0 ? '+ ' : '- ' }}{{ abs($diffs['rejected'] ?? 0) }}
                        </span> vs yesterday
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex gap-4">
                <div class="relative">
                    <select onchange="window.location.href=this.value"
                        class="appearance-none bg-white border border-acmi-bordercolor rounded-lg pl-4 pr-10 py-2 text-sm outline-none shadow-sm cursor-pointer hover:border-acmi-blueprimer focus:border-acmi-blueprimer focus:ring-2 focus:ring-acmi-blueprimer/20 transition">
                        <option value="{{ route('inbound.index') }}">Filter Status</option>
                        <option value="{{ route('inbound.index', ['status' => 'review']) }}"
                            {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="{{ route('inbound.index', ['status' => 'approved']) }}"
                            {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="{{ route('inbound.index', ['status' => 'rejected']) }}"
                            {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <i
                        class="fas fa-chevron-down absolute right-4 top-3.5 text-[8px] text-acmi-blueprimer pointer-events-none"></i>
                </div>
            </div>

            <button onclick="approveAllSelected()"
                class="bg-acmi-blueprimer hover:bg-acmi-darkblue text-white px-3 py-2 rounded-lg text-sm font-base flex items-center gap-2 transition shadow-sm">
                <i class="fas fa-check text-[10px]"></i> Approve all
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-acmi-bordercolor bg-white shadow-sm">
            <table class="min-w-[1200px] w-full text-left border-collapse">
                <thead class="bg-acmi-softblue border-b border-acmi-bordercolor text-[10.5px] font-bold text-black">
                    <tr>
                        <th class="p-4 w-10 text-center border-r border-acmi-bordercolor">
                            <input type="checkbox" id="selectAll"
                                class="rounded border-acmi-bordercolor text-acmi-blueprimer focus:ring-acmi-blueprimer">
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
                    @forelse($inbounds as $item)
                        <tr class="border-b border-acmi-bordercolor/70 hover:bg-acmi-softblue/30 transition cursor-pointer"
                            onclick="openDetailModal({{ $item->id }})">

                            <td class="p-4 text-center border-r border-acmi-bordercolor/70"
                                onclick="event.stopPropagation()">
                                <input type="checkbox"
                                    class="inbound-checkbox rounded border-acmi-bordercolor text-acmi-blueprimer focus:ring-acmi-blueprimer"
                                    value="{{ $item->id }}">
                            </td>

                            <td class="p-4 border-r border-acmi-bordercolor/70">
                                <p class="font-bold text-gray-800">{{ $item->name }}</p>
                                <p class="text-[10px] text-gray-400">Today at {{ $item->created_at->format('H.i') }}</p>
                            </td>

                            <td class="p-4 border-r border-acmi-bordercolor/70 text-[11px]">
                                <div class="flex flex-col gap-1">
                                    <span class="flex items-center gap-2">
                                        <i class="far fa-envelope text-acmi-blueaccent"></i>
                                        {{ $item->email }}
                                    </span>

                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-phone-alt text-acmi-blueaccent"></i>
                                        {{ $item->phone }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-4 font-medium border-r border-acmi-bordercolor/70">
                                {{ $item->company_name }}
                            </td>

                            <td class="p-4 text-gray-500 border-r border-acmi-bordercolor/70">
                                {{ $item->industry }}
                            </td>

                            <td class="p-4 text-gray-500 border-r border-acmi-bordercolor/70">
                                {{ $item->position }}
                            </td>

                            <td class="p-4 border-r border-acmi-bordercolor/70">
                                @if ($item->company_url)
                                    <a href="{{ $item->company_url }}" onclick="event.stopPropagation()" target="_blank"
                                        class="text-acmi-blueprimer border border-acmi-blueaccent/30 rounded-full px-3 py-1 text-[10px] bg-acmi-softblue/40 flex items-center w-fit gap-2 hover:bg-acmi-softblue transition">
                                        <i class="fas fa-link text-[8px]"></i>
                                        {{ Str::limit($item->company_url, 20) }}
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center" onclick="event.stopPropagation()">
                                <div class="relative inline-block text-left group">
                                    <button type="button"
                                        class="inline-flex items-center justify-between w-28 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border transition-all
                                            {{ $item->status == 'approved' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                                            {{ $item->status == 'rejected' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                                            {{ $item->status == 'review' ? 'bg-acmi-softblue text-acmi-blueprimer border-acmi-blueaccent/30' : '' }}">

                                        <span>{{ $item->status == 'review' ? 'Review' : ucfirst($item->status) }}</span>
                                        <i class="fas fa-chevron-down text-[8px] ml-1"></i>
                                    </button>

                                    <div
                                        class="absolute right-0 mt-1 w-36 bg-white border border-acmi-bordercolor rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] overflow-visible">
                                        <div class="p-2 flex flex-col gap-1">
                                            <button type="button" onclick="updateStatus({{ $item->id }}, 'review')"
                                                class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-acmi-blueprimer hover:bg-acmi-softblue rounded-xl transition">
                                                <i class="fas fa-sync-alt mr-2"></i> Review
                                            </button>

                                            <button type="button"
                                                onclick="updateStatus({{ $item->id }}, 'approved')"
                                                class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-green-600 hover:bg-green-50 rounded-xl transition border-t border-acmi-bordercolor/50 pt-2">
                                                <i class="fas fa-check-circle mr-2"></i> Approved
                                            </button>

                                            <button type="button"
                                                onclick="updateStatus({{ $item->id }}, 'rejected')"
                                                class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-red-600 hover:bg-red-50 rounded-xl transition border-t border-acmi-bordercolor/50 pt-2">
                                                <i class="fas fa-times-circle mr-2"></i> Rejected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="p-10 text-center text-gray-400 italic border-b border-acmi-bordercolor">
                                No inbound data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-auto border-t border-acmi-bordercolor p-4">
                <x-pagination :paginator="$inbounds" />
            </div>
        </div>
    </div>

    <div id="detailModal" onclick="closeDetailModal()"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[999] backdrop-blur-sm">
        <div onclick="event.stopPropagation()"
            class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl m-4">
            <div class="p-6 border-b border-acmi-bordercolor flex justify-between items-center bg-acmi-softblue/20">
                <h2 class="text-lg font-bold text-gray-800">Inbound Detail Information</h2>

                <button onclick="closeDetailModal()" class="text-acmi-blueprimer hover:text-acmi-darkblue transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="mb-8">
                    <h3 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Name</p>
                            <p id="d_name" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Email</p>
                            <p id="d_email" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Phone Number</p>
                            <p id="d_phone" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">LinkedIn Profile</p>
                            <p id="d_linkedin" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-acmi-blueprimer font-bold text-xs uppercase tracking-wider mb-4">
                        Company Information
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Company Name</p>
                            <p id="d_company" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Position</p>
                            <p id="d_position" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Industry</p>
                            <p id="d_industry" class="text-sm font-semibold text-gray-800">-</p>
                        </div>

                        <div class="rounded-xl border border-acmi-bordercolor p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Company Website</p>
                            <p id="d_url" class="text-sm font-semibold text-gray-800 break-all">-</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-acmi-blueprimer font-bold uppercase tracking-wide text-xs mb-3">Motivation & Referrals</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="border border-gray-300 rounded-xl p-3 bg-white">
                            <label class="block text-[10px] uppercase font-semibold text-gray-400">Number Of Employees</label>
                            <div class="text-sm font-medium text-gray-800 mt-1">
                                {{ $inbound->employee_size ?? '-' }}
                            </div>
                        </div>

                        <div class="border border-gray-300 rounded-xl p-3 bg-white">
                            <label class="block text-[10px] uppercase font-semibold text-gray-400">Annual Revenue</label>
                            <div class="text-sm font-medium text-gray-800 mt-1">
                                {{ $inbound->annual_revenue ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-xl p-3 bg-white min-h-[100px]">
                        <label class="block text-[10px] uppercase font-semibold text-gray-400">Motivation</label>
                        <div class="text-sm font-medium text-gray-800 mt-1 whitespace-pre-line">
                            {{ $inbound->motivation_referral ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(id) {
            fetch(`/crm/inbound/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('d_name').innerText = data.name || '-';
                    document.getElementById('d_email').innerText = data.email || '-';
                    document.getElementById('d_phone').innerText = data.phone || '-';
                    document.getElementById('d_linkedin').innerText = data.linkedin_url || '-';
                    document.getElementById('d_company').innerText = data.company_name || '-';
                    document.getElementById('d_position').innerText = data.position || '-';
                    document.getElementById('d_industry').innerText = data.industry || '-';
                    document.getElementById('d_url').innerText = data.company_url || '-';

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

        function updateStatus(id, status) {
            let actionText = status === 'approved' ? 'approve' : (status === 'rejected' ? 'reject' : 'set to review');
            let btnColor = status === 'rejected' ? '#E15B5B' : '#1120B0';

            Swal.fire({
                title: `<span style="font-size: 18px; font-weight: 700;">Are you sure want to ${actionText} this item?</span>`,
                showCancelButton: true,
                confirmButtonText: status.charAt(0).toUpperCase() + status.slice(1),
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                confirmButtonColor: btnColor,
                cancelButtonColor: '#F3F4F6',
                customClass: {
                    popup: 'rounded-[32px] p-6',
                    confirmButton: 'rounded-full px-8 py-2.5 text-sm font-bold ml-2',
                    cancelButton: 'rounded-full px-8 py-2.5 text-sm font-bold text-gray-500'
                },
                buttonsStyling: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while the status is being updated.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/crm/inbound/${id}/status`, {
                        method: "PATCH",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated successfully!',
                            showConfirmButton: false,
                            timer: 900
                        }).then(() => window.location.reload());
                    });
                }
            });
        }

        function approveAllSelected() {
            let selectedIds = [];

            document.querySelectorAll('.inbound-checkbox:checked').forEach(cb => {
                selectedIds.push(cb.value);
            });

            if (selectedIds.length === 0) {
                Swal.fire('Oops!', 'Please select at least one inbound data first.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Bulk Approve',
                text: `Approve ${selectedIds.length} selected data?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1120B0',
                confirmButtonText: 'Yes, approve all!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while the selected data is being approved.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch("{{ route('inbound.bulkApprove') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: selectedIds
                        })
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved successfully!',
                            showConfirmButton: false,
                            timer: 900
                        }).then(() => window.location.reload());
                    });
                }
            });
        }
    </script>
@endsection
