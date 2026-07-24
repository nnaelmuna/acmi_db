@extends('layouts.app')

@section('title', 'Banner Sponsored - ACMI')
@section('page_title', 'Banner Sponsored')

@section('content')

    {{-- Success --}}
    @if (session('success'))
        <div id="successAlert"
            class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div id="errorAlert" class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700 transition-all duration-500">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <x-filters-tab :tabs="$tabs" />

        <button type="button" onclick="openAddModal()"
            class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue">
            <span>Add Banner Sponsored</span>
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    {{-- Card Grid --}}
    <div class="mt-6 flex min-h-[calc(100vh-230px)] flex-col">
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($banners as $item)
                <div class="group rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md">

                    <div class="relative overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center h-40">
                        @if ($item->image)
                            @if (pathinfo($item->image, PATHINFO_EXTENSION) === 'pdf')
                                <div class="flex flex-col items-center justify-center text-rose-500">
                                    <i class="fa-solid fa-file-pdf text-5xl"></i>
                                    <span class="mt-2 text-xs font-semibold text-gray-500">PDF Document</span>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @endif
                        @else
                            <div class="text-sm text-gray-400">
                                No Image
                            </div>
                        @endif

                        {{-- Expired Badge --}}
                        @if (!$item->is_forever && $item->end_date && \Carbon\Carbon::parse($item->end_date)->endOfDay()->isPast())
                            <div class="absolute left-3 top-3 z-10 flex items-center gap-1.5 rounded-md bg-rose-500/90 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm backdrop-blur-md">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                Expired
                            </div>
                        @endif

                        {{-- Edit & Delete --}}
                        @if (request('status') !== 'trash')
                            <div class="absolute right-3 top-3 flex gap-2 opacity-0 transition group-hover:opacity-100">

                                <button type="button"
                                    onclick='openEditModal({
                                    ...@json($item),
                                    image_url: "{{ $item->image ? asset('storage/' . $item->image) : '' }}"
                                    })'
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-blue-600 shadow hover:bg-blue-600 hover:text-white">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <button type="button"
                                    onclick="openDeleteModal('{{ route('sponsored-banner.destroy', $item->id) }}', 'Are you sure want to delete this sponsored banner?')"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-red-500 shadow hover:bg-red-500 hover:text-white">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>

                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-900 leading-tight">{{ $item->title }}</h3>

                        @if ($item->link_sponsored)
                            <a href="{{ $item->link_sponsored }}" target="_blank"
                                class="mt-1 inline-block text-xs text-blue-600 hover:text-blue-800 transition truncate max-w-full">
                                <i class="fa-solid fa-link mr-1"></i>Visit Link
                            </a>
                        @endif

                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }} - 
                            @if($item->is_forever)
                                <span class="font-semibold text-green-600">Selamanya</span>
                            @else
                                {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M Y') : '-' }}
                            @endif
                        </p>

                        {{-- Impressions Badge --}}
                        <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3">
                            <span class="text-xs font-semibold text-gray-500">Total Views / Impressions</span>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-acmi-blueprimer">
                                {{ number_format($item->impressions) }}
                            </span>
                        </div>

                        {{-- Restore & Force Delete --}}
                        @if (request('status') === 'trash')
                            <form action="{{ route('sponsored-banner.restore', $item->id) }}" method="POST"
                                class="mt-4 w-full">
                                @csrf

                                <button type="submit"
                                    class="block w-full rounded-lg border border-blue-900 py-2 text-center text-sm font-medium text-acmi-blueprimer transition hover:bg-acmi-blueprimer hover:text-white">
                                    Restore
                                </button>
                            </form>

                            <button type="button"
                                onclick="openDeleteModal('{{ route('sponsored-banner.forceDelete', $item->id) }}', 'Permanently Delete? This data cannot be recovered!')"
                                class="mt-2 block w-full rounded-lg border border-red-600 py-2 text-center text-sm font-medium text-red-600 transition hover:bg-red-600 hover:text-white">
                                Permanently Delete
                            </button>
                        @else
                            @if ($item->image)
                                @if (pathinfo($item->image, PATHINFO_EXTENSION) === 'pdf')
                                    <a href="{{ asset('storage/' . $item->image) }}" target="_blank"
                                        class="mt-4 block w-full rounded-lg bg-acmi-blueprimer py-2.5 text-center text-sm font-medium text-white transition hover:bg-acmi-darkblue">
                                        Open PDF
                                    </a>
                                @else
                                    <button type="button"
                                        onclick="openPreviewModal('{{ asset('storage/' . $item->image) }}', @js($item->title))"
                                        class="mt-4 block w-full rounded-lg bg-acmi-blueprimer py-2.5 text-center text-sm font-medium text-white transition hover:bg-acmi-darkblue">
                                        Preview Image
                                    </button>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full flex min-h-[520px] items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white">
                    <p class="text-sm italic text-gray-400">No sponsored banners yet.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-auto">
            <x-pagination :paginator="$banners" />
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="addBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100 bg-white rounded-t-2xl flex-shrink-0">
                <h2 class="text-lg font-semibold text-gray-800">Add Banner Sponsored</h2>

                <button type="button" onclick="closeAddModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('sponsored-banner.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 px-6 pb-6 pt-5 overflow-y-auto flex-grow">
                @csrf

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Title</label>
                    <input type="text" name="title" required placeholder="Input banner title"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Link Sponsored</label>
                    <input type="url" name="link_sponsored" required placeholder="https://example.com"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Upload Image / PDF</label>
                    <input type="file" name="image" required accept=".jpg,.jpeg,.png,.webp,.pdf"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-acmi-blueprimer hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-md p-1 bg-white focus:outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Size</label>
                    <select name="size" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                        <option value="728x90">728x90</option>
                        <option value="970x250">970x250</option>
                        <option value="336x280">336x280</option>
                        <option value="300x250">300x250</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Posisi Banner</label>
                    <select name="position" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                        <option value="">-- Tanpa Posisi (Gunakan Fallback) --</option>
                        <option value="1">1. Di bawah Solution Section (728x90)</option>
                        <option value="2">2. Di bawah Exclusive Membership (970x250)</option>
                        <option value="3">3. Di bawah Produk Anggota (336x280)</option>
                        <option value="4">4. Di bawah FAQ (728x90)</option>
                        <option value="5">5. Di bawah Gallery (970x250)</option>
                        <option value="6">6. Di bawah Instagram Feed (300x250)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">Start Date</label>
                        <input type="date" name="start_date" id="add_start_date" required
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">End Date</label>
                        <input type="date" name="end_date" id="add_end_date" required
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_forever" id="add_is_forever" onchange="toggleAddEndDate()"
                        class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer focus:ring-acmi-blueprimer/20">
                    <label for="add_is_forever" class="ml-2 text-sm text-gray-600 font-medium cursor-pointer">
                        Tampilkan Selamanya
                    </label>
                </div>

                <x-form-status-buttons />
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="editBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100 bg-white rounded-t-2xl flex-shrink-0">
                <h2 class="text-lg font-semibold text-gray-800">Edit Banner Sponsored</h2>

                <button type="button" onclick="closeEditModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-5 px-6 pb-6 pt-5 overflow-y-auto flex-grow">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Title</label>
                    <input id="edit_title" type="text" name="title" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Link Sponsored</label>
                    <input id="edit_link_sponsored" type="url" name="link_sponsored" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Current Image / PDF</label>
                    <img id="edit_preview_image" src=""
                        class="hidden h-40 w-full rounded-xl border border-gray-200 object-cover">
                    <div id="edit_pdf_preview" class="hidden h-40 w-full rounded-xl border border-gray-200 bg-gray-50 flex flex-col items-center justify-center text-rose-500">
                        <i class="fa-solid fa-file-pdf text-5xl"></i>
                        <span class="mt-2 text-xs font-semibold text-gray-500">PDF Document</span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Change Image / PDF</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.pdf"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-acmi-blueprimer hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-md p-1 bg-white focus:outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Size</label>
                    <select id="edit_size" name="size" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                        <option value="728x90">728x90</option>
                        <option value="970x250">970x250</option>
                        <option value="336x280">336x280</option>
                        <option value="300x250">300x250</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Posisi Banner</label>
                    <select id="edit_position" name="position" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                        <option value="">-- Tanpa Posisi (Gunakan Fallback) --</option>
                        <option value="1">1. Di bawah Solution Section (728x90)</option>
                        <option value="2">2. Di bawah Exclusive Membership (970x250)</option>
                        <option value="3">3. Di bawah Produk Anggota (336x280)</option>
                        <option value="4">4. Di bawah FAQ (728x90)</option>
                        <option value="5">5. Di bawah Gallery (970x250)</option>
                        <option value="6">6. Di bawah Instagram Feed (300x250)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">Start Date</label>
                        <input id="edit_start_date" type="date" disabled
                            class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">End Date</label>
                        <input id="edit_end_date" type="date" disabled
                            class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <div class="flex items-center opacity-70">
                    <input type="checkbox" id="edit_is_forever" disabled
                        class="h-4 w-4 rounded border-gray-300 text-acmi-blueprimer cursor-not-allowed">
                    <label class="ml-2 text-sm text-gray-500 font-medium cursor-not-allowed">
                        Tampilkan Selamanya
                    </label>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Total Impressions / Views</label>
                    <input id="edit_impressions" type="text" disabled
                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                </div>

                <x-form-status-select id="edit_status" name="status" />

                <div class="flex justify-end gap-2 pt-1">
                    <button type="submit"
                        class="rounded-md bg-acmi-darkblue px-5 py-2.5 text-xs font-medium text-white transition hover:bg-blue-900 shadow">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div id="previewModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 backdrop-blur-sm">
        <div class="relative w-full max-w-3xl rounded-2xl bg-white p-4 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 id="previewTitle" class="text-lg font-semibold text-gray-900">Preview Image</h2>

                <button type="button" onclick="closePreviewModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="overflow-hidden rounded-xl bg-gray-100">
                <img id="previewImage" src="" alt="Preview Image" class="max-h-[70vh] w-full object-contain mx-auto">
            </div>
        </div>
    </div>

    {{-- Form Delete Modal --}}
    <form id="delete-item-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script>
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
            }, 2500);

            setTimeout(() => {
                successAlert.remove();
            }, 3000);
        }

        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.opacity = '0';
                errorAlert.style.transform = 'translateY(-10px)';
            }, 5000);

            setTimeout(() => {
                errorAlert.remove();
            }, 5500);
        }

        function animateModalOpen(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const box = document.getElementById(boxId);

            if (!modal || !box) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            });
        }

        function animateModalClose(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const box = document.getElementById(boxId);

            if (!modal || !box) return;

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openAddModal() {
            // reset form on open
            const form = document.querySelector('#addModal form');
            if (form) form.reset();
            toggleAddEndDate();
            animateModalOpen('addModal', 'addBox');
        }

        function closeAddModal() {
            animateModalClose('addModal', 'addBox');
        }

        function toggleAddEndDate() {
            const isForever = document.getElementById('add_is_forever').checked;
            const endDateInput = document.getElementById('add_end_date');

            if (isForever) {
                endDateInput.value = '';
                endDateInput.disabled = true;
                endDateInput.required = false;
                endDateInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
            } else {
                endDateInput.disabled = false;
                endDateInput.required = true;
                endDateInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
            }
        }

        function openEditModal(data) {
            document.getElementById('editForm').action = '/sponsored-banner/' + data.id;
            document.getElementById('edit_title').value = data.title ?? '';
            document.getElementById('edit_link_sponsored').value = data.link_sponsored ?? '';
            document.getElementById('edit_start_date').value = data.start_date ?? '';
            document.getElementById('edit_end_date').value = data.end_date ?? '';
            document.getElementById('edit_is_forever').checked = !!data.is_forever;
            document.getElementById('edit_impressions').value = data.impressions ?? 0;
            document.getElementById('edit_status').value = data.status ?? 'published';
            document.getElementById('edit_size').value = data.size ?? '728x90';
            document.getElementById('edit_position').value = data.position ?? '';

            const preview = document.getElementById('edit_preview_image');
            const pdfPreview = document.getElementById('edit_pdf_preview');

            if (data.image_url) {
                if (data.image_url.toLowerCase().endsWith('.pdf')) {
                    preview.src = '';
                    preview.classList.add('hidden');
                    pdfPreview.classList.remove('hidden');
                } else {
                    preview.src = data.image_url;
                    preview.classList.remove('hidden');
                    pdfPreview.classList.add('hidden');
                }
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                pdfPreview.classList.add('hidden');
            }
            animateModalOpen('editModal', 'editBox');
        }

        function closeEditModal() {
            animateModalClose('editModal', 'editBox');
        }

        function openPreviewModal(imageUrl, title) {
            document.getElementById('previewImage').src = imageUrl;
            document.getElementById('previewTitle').innerText = title;

            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('previewImage').src = '';
        }

        window.addEventListener('click', function(e) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const previewModal = document.getElementById('previewModal');

            if (e.target === addModal) closeAddModal();
            if (e.target === editModal) closeEditModal();
            if (e.target === previewModal) closePreviewModal();
        });
    </script>
@endpush
