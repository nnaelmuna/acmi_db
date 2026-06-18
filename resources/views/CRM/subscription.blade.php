@extends('layouts.app')

@section('title', 'Subscription Screen - ACMI')
@section('page_title', 'Subscription')

@section('header_right')
    <div class="hidden md:block w-64 mr-4">
        <form action="{{ route('subscription.index') }}" method="GET" class="relative group">
            <input type="hidden" name="status" value="{{ request('status', 'published') }}">

            <i
                class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm group-focus-within:text-acmi-blueprimer transition"></i>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Subscription"
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

    <div class="max-w-7xl mx-auto pb-10">
        {{-- Row Atas: Filter Tabs & Action Button --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                {{-- Memanggil komponen tab template bawaan project kamu --}}
                <x-filters-tab :tabs="$tabs" />
            </div>
            <div>
                <button onclick="approveAllSelected()"
                    class="bg-acmi-blueprimer hover:bg-acmi-darkblue text-white px-3 py-2 rounded-lg text-sm font-base flex items-center gap-2 transition shadow-sm">
                    <i class="fas fa-check text-[10px]"></i> Approve all
                </button>
            </div>
        </div>

        {{-- Main Table Area --}}
        <div class="flex flex-col">
            <div class="overflow-x-auto rounded-xl border border-acmi-bordercolor bg-white shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-acmi-softblue text-[11px] border-b border-acmi-bordercolor font-bold text-gray-700">
                        <tr>
                            <th class="p-3 w-10 text-center"><input type="checkbox" id="select-all"
                                    class="rounded border-gray-300"></th>
                            <th class="p-3"><i class="far fa-user mr-1 text-acmi-blueprimer"></i> Profile</th>
                            <th class="p-3"><i class="far fa-address-card mr-1 text-acmi-blueprimer"></i> Contact</th>
                            <th class="p-3"><i class="far fa-building mr-1 text-acmi-blueprimer"></i> Company</th>
                            <th class="p-3"><i class="fas fa-cubes mr-1 text-acmi-blueprimer"></i> Industry</th>
                            <th class="p-3"><i class="fas fa-briefcase mr-1 text-acmi-blueprimer"></i> Position</th>
                            <th class="p-3"><i class="fas fa-link mr-1 text-acmi-blueprimer"></i> Transaction</th>
                            <th class="p-3 text-center"><i class="far fa-dot-circle mr-1 text-acmi-blueprimer"></i> Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="text-xs text-gray-700 divide-y divide-gray-100">
                        @forelse($subscriptions as $item)
                            <tr class="hover:bg-slate-50/80 transition cursor-pointer"
                                onclick="openDetailModal('{{ $item->id }}', '{{ $item->name }}', '{{ $item->transaction_url }}', '{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}')">
                                <td class="p-3 text-center">
                                    <input type="checkbox"
                                        class="subscription-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        value="{{ $item->id }}">
                                </td> {{-- 🛠️ FIX: Kemarin lu lupa nutup tag </td> di sini, jadinya kolom geser --}}

                                {{-- Profile --}}
                                <td class="p-3">
                                    <p class="font-bold text-gray-900">{{ $item->name }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                    </p>
                                </td>

                                {{-- Contact --}}
                                <td class="p-3">
                                    <div class="space-y-0.5">
                                        <p class="flex items-center gap-1.5 text-gray-600">
                                            <i class="far fa-envelope text-[10px] text-gray-400"></i> {{ $item->email }}
                                        </p>
                                        <p class="flex items-center gap-1.5 text-gray-600">
                                            <i class="fas fa-phone-alt text-[10px] text-gray-400"></i> {{ $item->phone }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Company & Info --}}
                                <td class="p-3 font-medium text-gray-800">{{ $item->company_name }}</td>
                                <td class="p-3 text-gray-500">{{ $item->industry }}</td>
                                <td class="p-3 text-gray-500">{{ $item->position }}</td>

                                {{-- Transaction Link --}}
                                <td class="p-3">
                                    <a href="{{ $item->transaction_url }}" target="_blank"
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded border border-gray-200 bg-gray-50 text-acmi-blueprimer hover:bg-gray-100 transition truncate max-w-[150px]">
                                        <i class="fas fa-link text-[9px] text-gray-400"></i>
                                        {{ $item->transaction_url }}
                                    </a>
                                </td>

                                {{-- Interactive Status & Trash Dropdown Dropdown --}}
                                <td class="p-3 text-center" onclick="event.stopPropagation()">
                                    <div class="relative inline-block text-left group">

                                        @if (request('status') === 'trash')
                                            {{-- TOMBOL UTAMA SAAT DI TAB TRASH --}}
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-32 px-4 py-2 rounded-full text-[11px] font-bold border bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200 transition duration-150 cursor-pointer shadow-sm">
                                                <span>Trash Action</span>
                                                <i class="fas fa-chevron-down text-[9px] ml-2 text-gray-500"></i>
                                            </button>

                                            {{-- FLOATING BOX PUTIH (MODEL ACTION TRASH) --}}
                                            <div
                                                class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] py-1.5 p-1">
                                                {{-- Opsi Restore --}}
                                                <a href="{{ route('subscription.restore', $item->id) }}"
                                                    class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-green-600 hover:bg-green-50 rounded-lg transition text-left cursor-pointer">
                                                    <i class="fas fa-undo mr-2.5 text-[12px]"></i> Restore
                                                </a>

                                                {{-- Opsi Delete Permanently --}}
                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('subscription.forceDelete', $item->id) }}', 'Permanently Delete? This data cannot be recovered!')"
                                                    class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-red-600 hover:bg-red-50 rounded-lg transition text-left cursor-pointer border-t border-gray-100/70 mt-1">
                                                    <i class="far fa-trash-alt mr-2.5 text-[12px]"></i> Delete Permanently
                                                </button>
                                            </div>
                                        @else
                                            {{-- TOMBOL UTAMA STATUS (ACTIVE / DEACTIVE) --}}
                                            @if($item->sub_status === 'active')
                                                <button type="button"
                                                    class="inline-flex items-center justify-center w-28 px-4 py-2 rounded-full text-[12px] font-semibold border bg-[#E6FEDA] text-black border-[#5389A8]/30 hover:bg-[#D2FEBC] transition duration-150 cursor-pointer shadow-sm">
                                                    <span>Active</span>
                                                    <i class="fas fa-chevron-down text-[9px] ml-2 text-black"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="inline-flex items-center justify-center w-28 px-4 py-2 rounded-full text-[12px] font-semibold border bg-[#FFDADA] text-black border-[#A85353]/30 hover:bg-[#FFC9C9] transition duration-150 cursor-pointer shadow-sm">
                                                    <span>Deactive</span>
                                                    <i class="fas fa-chevron-down text-[9px] ml-2 text-black"></i>
                                                </button>
                                            @endif

                                            {{-- FLOATING BOX PUTIH (MODEL ACTION STATUS) --}}
                                            <div
                                                class="absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100] py-1.5 p-1">
                                                <form action="{{ route('subscription.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    @if($item->sub_status === 'active')
                                                        <input type="hidden" name="sub_status" value="deactive">
                                                        <button type="submit"
                                                            class="flex items-center w-full px-3 py-2 text-[12px] font-semibold text-red-500 hover:bg-red-50 rounded-lg transition text-left">
                                                            <i class="far fa-times-circle mr-2.5 text-[12px]"></i> Set Deactive
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="sub_status" value="active">
                                                        <button type="submit"
                                                            class="flex items-center w-full px-3 py-2 text-[12px] font-semibold text-green-600 hover:bg-green-50 rounded-lg transition text-left">
                                                            <i class="far fa-check-circle mr-2.5 text-[12px]"></i> Set Active
                                                        </button>
                                                    @endif
                                                </form>
                                                <button type="button"
                                                    onclick="openDeleteModal('{{ route('subscription.destroy', $item->id) }}', 'Are you sure want to move this item to trash?')"
                                                    class="flex items-center w-full px-3 py-2 text-[11px] font-semibold text-red-850 hover:bg-red-50 rounded-lg transition text-left cursor-pointer border-t border-gray-100/70 mt-1">
                                                    <i class="far fa-trash-alt mr-2.5 text-[12px]"></i> Move To Trash
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-gray-400 italic bg-gray-50/50">
                                    No subscription records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Area --}}
            <div class="mt-4">
                <x-pagination :paginator="$subscriptions" />
            </div>
        </div>
    </div>
@endsection

{{-- MODAL FORM EDIT SUBSCRIPTION (BISA UPDATE DATA + UPLOAD) --}}
<div id="subscriptionDetailModal"
    class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
    <div
        class="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-7 shadow-2xl scale-95 transform transition-transform duration-300">

        {{-- Header Modal --}}
        {{-- Header Modal --}}
        <div class="mb-5 flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900">Subscription Detail Screen</h3>

            {{-- FIX: Tambahin onclick="event.stopPropagation(); closeDetailModal()" --}}
            <button type="button" onclick="event.stopPropagation(); closeDetailModal()"
                class="text-gray-400 hover:text-gray-600 transition p-1 z-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Form Edit Mengarah ke Route Update --}}
        <form id="edit-subscription-form" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            {{-- Partner Name --}}
            <div>
                <label class="block font-semibold text-gray-500 mb-1.5">Partner Name</label>
                <input type="text" name="name" id="modal-partner-name" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-800 font-medium outline-none focus:border-acmi-blueprimer focus:ring-1 focus:ring-acmi-blueprimer">
            </div>

            {{-- Partner Image (Bisa Diklik Pilih File Beneran) --}}
            <div>
                <label class="block font-semibold text-gray-500 mb-1.5">Partner Image</label>
                <input type="file" name="partner_image" id="modal-partner-image"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none file:mr-4 file:py-1 file:px-2.5 file:rounded file:border file:border-gray-300 file:text-[11px] file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>

            {{-- Partner Link --}}
            <div>
                <label class="block font-semibold text-gray-500 mb-1.5">Partner Link</label>
                <input type="url" name="partner_link" id="modal-partner-link"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-800 outline-none focus:border-acmi-blueprimer focus:ring-1 focus:ring-acmi-blueprimer">
            </div>

            {{-- Row Start & End Date (Tipe 'date' Biar Keluar Kalender Otomatis) --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-500 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" id="modal-start-date"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-800 outline-none focus:border-acmi-blueprimer focus:ring-1 focus:ring-acmi-blueprimer">
                </div>
                <div>
                    <label class="block font-semibold text-gray-500 mb-1.5">End Date</label>
                    <input type="date" name="end_date" id="modal-end-date"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-800 outline-none focus:border-acmi-blueprimer focus:ring-1 focus:ring-acmi-blueprimer">
                </div>
            </div>

            {{-- Tombol Submit Save Changes --}}
            <div class="mt-6 flex justify-end gap-2 border-t border-gray-100 pt-4">
                <button type="button" onclick="closeDetailModal()"
                    class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-semibold text-gray-600 transition">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-acmi-blueprimer hover:bg-acmi-darkblue font-semibold text-white transition shadow-sm">Save
                    Changes</button>
            </div>
        </form>

    </div>
</div>
@push('scripts')
    <script>
        function openDetailModal(id, name, link, rawDate) {
            // 1. Set Action Form URL secara dinamis sesuai ID data yang diklik
            let updateUrl = "{{ url('/subscription') }}/" + id + "/update-detail";
            document.getElementById('edit-subscription-form').setAttribute('action', updateUrl);

            // 2. Masukkan data awal ke input form modal
            document.getElementById('modal-partner-name').value = name;
            document.getElementById('modal-partner-link').value = link;
            document.getElementById('modal-start-date').value = rawDate;

            // Reset input file biar kosong siap upload file baru
            document.getElementById('modal-partner-image').value = "";

            // Hitung estimasi end date otomatis (+1 tahun) sebagai default value kalender kanan
            if (rawDate) {
                let endDate = new Date(rawDate);
                endDate.setFullYear(endDate.getFullYear() + 1);
                document.getElementById('modal-end-date').value = endDate.toISOString().split('T')[0];
            }

            // 3. Tampilkan Modal
            document.getElementById('subscriptionDetailModal').classList.remove('hidden');
        }

        // 🛠️ FIX: Tambahin fungsi penutup ini yang kemarin ketinggalan, Nis!
        function closeDetailModal() {
            const modal = document.getElementById('subscriptionDetailModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Opsional: Tutup modal kalau user klik area luar hitam transparan
        window.onclick = function (event) {
            const modal = document.getElementById('subscriptionDetailModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }

        // ✨ BIANG KELURUSAN: Script Bulk Select Checkbox Semua
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-close alert success jika ada
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => { successAlert.style.opacity = '0'; }, 2000);
                setTimeout(() => { successAlert.remove(); }, 2500);
            }

            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.subscription-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    });
                });
            }
        });
    </script>
@endpush