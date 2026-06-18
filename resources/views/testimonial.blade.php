@extends('layouts.app')

@section('title', 'ACMI - Testimonial Management')
@section('page_title', 'Testimonial')

@section('content')
    <div class="w-full space-y-4 pb-10">

        {{-- Success Notification --}}
        @if (session('success'))
            <div id="successAlert"
                class="mb-4 flex translate-y-[-8px] items-center justify-between rounded-xl bg-green-100 px-4 py-3 text-sm font-medium text-green-700 opacity-0 transition-all duration-500">
                <span>{{ session('success') }}</span>

                <button type="button" onclick="hideSuccessAlert()" class="ml-4 text-green-700 hover:text-green-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- Filter Tabs & Button --}}
        <div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            <x-filters-tab :tabs="$tabs" />

            <div class="flex justify-end">
                <button type="button" onclick="openAddModal()"
                    class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                    <span>Add Testimonial</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- Testimonial List --}}
        <div class="flex min-h-[calc(100vh-230px)] w-full flex-col">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($testimonials as $item)
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-100 p-6 relative">
                        
                        {{-- Top Section: Quotes & Rating --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 text-xl font-serif">
                                &ldquo;
                            </div>
                            <div class="flex gap-1 text-orange-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $item->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        {{-- Content --}}
                        <p class="text-sm font-medium leading-relaxed text-gray-700 italic mb-6">
                            "{{ $item->content }}"
                        </p>

                        <div class="border-t border-gray-100 pt-4 mt-auto"></div>

                        {{-- Bottom Section: Avatar & Author & Actions --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#F97316] text-white flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#1e293b]">{{ $item->name }}</h4>
                                    <p class="text-[10px] font-bold text-[#F97316] uppercase tracking-wider">{{ $item->role }}</p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex shrink-0 items-center gap-2">
                                @if (request('status') === 'trash')
                                    <form action="{{ route('testimonial.restore', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="flex h-8 items-center justify-center rounded-md border border-acmi-blueprimer px-3 text-[10px] font-medium text-acmi-blueprimer transition hover:bg-acmi-blueprimer hover:text-white">
                                            Restore
                                        </button>
                                    </form>

                                    <form action="{{ route('testimonial.forceDelete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('testimonial.forceDelete', $item->id) }}', 'Permanently Delete? This data cannot be recovered!')"
                                            class="flex h-8 items-center justify-center rounded-md border border-red-600 px-3 text-[10px] font-medium text-red-600 transition hover:bg-red-600 hover:text-white">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <button type="button" data-id="{{ $item->id }}"
                                        data-name="{{ e($item->name) }}" data-role="{{ e($item->role) }}"
                                        data-content="{{ e($item->content) }}" data-rating="{{ $item->rating }}"
                                        data-status="{{ $item->status ?? 'published' }}"
                                        onclick="openEditModalFromButton(this)"
                                        class="flex h-8 w-8 items-center justify-center rounded-md bg-acmi-blueaccent text-white transition hover:bg-acmi-blueprimer">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('testimonial.destroy', $item->id) }}', 'Are you sure want to delete this Testimonial?')"
                                        class="flex h-8 w-8 items-center justify-center rounded-md bg-acmi-yellowaccent text-white transition hover:opacity-90">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-acmi-bordercolor bg-white col-span-1 md:col-span-2">
                        <p class="text-sm italic text-gray-400">No Testimonial available yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-auto pt-6">
                <x-pagination :paginator="$testimonials" />
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="addBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Add Testimonial</h2>

                <button type="button" onclick="closeAddModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('testimonial.store') }}" method="POST" class="space-y-5 px-6 pb-6 pt-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="mb-2 block text-xs font-semibold text-gray-600">Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            placeholder="e.g. Dedi Kurnia"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                    <div>
                        <label for="role" class="mb-2 block text-xs font-semibold text-gray-600">Role / Company</label>
                        <input type="text" id="role" name="role" required value="{{ old('role') }}"
                            placeholder="e.g. FOUNDER, RETAIL GROUP"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                </div>

                <div>
                    <label for="content" class="mb-2 block text-xs font-semibold text-gray-600">Quote / Content</label>
                    <textarea id="content" name="content" rows="4" required
                        class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">{{ old('content') }}</textarea>
                </div>
                
                <div>
                    <label for="rating" class="mb-2 block text-xs font-semibold text-gray-600">Rating (1-5)</label>
                    <input type="number" id="rating" name="rating" required value="{{ old('rating', 5) }}" min="1" max="5"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <x-form-status-buttons />
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="editBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Edit Testimonial</h2>

                <button type="button" onclick="closeEditModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-5 px-6 pb-6 pt-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="mb-2 block text-xs font-semibold text-gray-600">Name</label>
                        <input type="text" id="edit_name" name="name" required
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                    <div>
                        <label for="edit_role" class="mb-2 block text-xs font-semibold text-gray-600">Role / Company</label>
                        <input type="text" id="edit_role" name="role" required
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                    </div>
                </div>

                <div>
                    <label for="edit_content" class="mb-2 block text-xs font-semibold text-gray-600">Quote / Content</label>
                    <textarea id="edit_content" name="content" rows="4" required
                        class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"></textarea>
                </div>

                <div>
                    <label for="edit_rating" class="mb-2 block text-xs font-semibold text-gray-600">Rating (1-5)</label>
                    <input type="number" id="edit_rating" name="rating" required min="1" max="5"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <x-form-status-select id="edit_status" name="status" />

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit"
                        class="rounded-md bg-acmi-darkblue px-5 py-2 text-xs font-medium text-white transition hover:bg-blue-900">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(button) {
            const label = button.querySelector('span').innerText.toLowerCase();

            if (label === 'published') window.location.href = '?status=published';
            if (label === 'draft') window.location.href = '?status=draft';
            if (label === 'archived') window.location.href = '?status=archived';
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

        function openEditModalFromButton(button) {
            const form = document.getElementById('editForm');
            const name = document.getElementById('edit_name');
            const role = document.getElementById('edit_role');
            const content = document.getElementById('edit_content');
            const rating = document.getElementById('edit_rating');
            const status = document.getElementById('edit_status');

            form.action = `/testimonial/${button.dataset.id}`;
            name.value = button.dataset.name;
            role.value = button.dataset.role;
            content.value = button.dataset.content;
            rating.value = button.dataset.rating;
            status.value = button.dataset.status || 'published';

            animateModalOpen('editModal', 'editBox');
        }

        function closeEditModal() {
            animateModalClose('editModal', 'editBox');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('successAlert');

            if (alert) {
                requestAnimationFrame(() => {
                    alert.classList.remove('opacity-0', 'translate-y-[-8px]');
                    alert.classList.add('opacity-100', 'translate-y-0');
                });

                setTimeout(() => {
                    hideSuccessAlert();
                }, 3000);
            }
        });

        function hideSuccessAlert() {
            const alert = document.getElementById('successAlert');

            if (!alert) return;

            alert.classList.remove('opacity-100', 'translate-y-0');
            alert.classList.add('opacity-0', 'translate-y-[-8px]');

            setTimeout(() => {
                alert.remove();
            }, 500);
        }

        window.addEventListener('click', function(e) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');

            if (e.target === addModal) closeAddModal();
            if (e.target === editModal) closeEditModal();
        });
    </script>
@endpush
