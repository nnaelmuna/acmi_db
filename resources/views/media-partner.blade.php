@extends('layouts.app')

@section('title', 'Media Partner')
@section('page_title', 'Media Partner')

@section('content')

    {{-- Success --}}
    @if (session('success'))
        <div id="successAlert"
            class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-end">
        <button type="button" onclick="openAddModal()"
            class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition">
            <span>Add Partner</span>
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    {{-- Card Grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($partners as $item)
            <div class="group rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md">

                <div class="relative overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/' . $item->image) }}"
                        class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105">

                    <div class="absolute right-3 top-3 flex gap-2 opacity-0 transition group-hover:opacity-100">

                        <button type="button" onclick='openEditModal(@json($item))'
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-blue-600 shadow hover:bg-blue-600 hover:text-white">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>

                        <button type="button"
                            onclick="openDeleteModal('{{ route('media-partner.destroy', $item->id) }}', 'Are you sure want to delete this media partner?')"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-red-500 shadow hover:bg-red-500 hover:text-white">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>

                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold text-gray-900">{{ $item->name }}</h3>

                    @if ($item->link)
                        <a href="{{ $item->link }}" target="_blank"
                            class="mt-1 inline-block text-xs text-blue-600 underline">
                            Visit Link
                        </a>
                    @endif

                    <p class="mt-2 text-xs text-gray-500">
                        {{ $item->start_date ?? '-' }} - {{ $item->end_date ?? '-' }}
                    </p>
                </div>
            </div>
        @empty
            <div
                class="col-span-full flex min-h-[520px] items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white">
                <p class="text-sm italic text-gray-400">No partner yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Add Partner Modal --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="addBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Add Media Partner</h2>

                <button type="button" onclick="closeAddModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('media-partner.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 px-6 pb-6 pt-5">
                @csrf

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Partner Name</label>
                    <input type="text" name="name" required placeholder="Input partner name"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Partner Image</label>
                    <input type="file" name="image" required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Partner Link</label>
                    <input type="url" name="link" placeholder="https://example.com"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">Start Date</label>
                        <input type="date" name="start_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">End Date</label>
                        <input type="date" name="end_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-1">

                    <button type="submit"
                        class="rounded-md bg-acmi-darkblue px-5 py-2 text-xs font-medium text-white transition hover:bg-blue-900">
                        Save Partner
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Partner Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="editBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Edit Media Partner</h2>

                <button type="button" onclick="closeEditModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-5 px-6 pb-6 pt-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Partner Name</label>
                    <input id="edit_name" type="text" name="name" required
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Change Image</label>
                    <input type="file" name="image"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Partner Link</label>
                    <input id="edit_link" type="url" name="link"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">Start Date</label>
                        <input id="edit_start" type="date" name="start_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-gray-600">End Date</label>
                        <input id="edit_end" type="date" name="end_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-1">

                    <button type="submit"
                        class="rounded-md bg-acmi-darkblue px-5 py-2 text-xs font-medium text-white transition hover:bg-blue-900">
                        Update Partner
                    </button>
                </div>
            </form>
        </div>
    </div>

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

        function animateModalOpen(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const box = document.getElementById(boxId);

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

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openAddModal() {
            animateModalOpen('addModal', 'addBox');
        }

        function closeAddModal() {
            animateModalClose('addModal', 'addBox');
        }

        function openEditModal(data) {
            document.getElementById('editForm').action = '/media-partner/' + data.id;
            document.getElementById('edit_name').value = data.name ?? '';
            document.getElementById('edit_link').value = data.link ?? '';
            document.getElementById('edit_start').value = data.start_date ?? '';
            document.getElementById('edit_end').value = data.end_date ?? '';

            animateModalOpen('editModal', 'editBox');
        }

        function closeEditModal() {
            animateModalClose('editModal', 'editBox');
        }

        window.addEventListener('click', function(e) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');

            if (e.target === addModal) closeAddModal();
            if (e.target === editModal) closeEditModal();
        });
    </script>
@endpush
