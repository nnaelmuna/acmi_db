@extends('layouts.app')

@section('title', 'ACMI - Post Management')
@section('page_title', 'Post')

@section('header_right')
    <div class="w-full max-w-[220px] md:max-w-[260px] lg:max-w-[300px]">
        <div class="relative w-full">
            <img src="{{ asset('assets/icons/search.svg') }}" alt="Search"
                class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 object-contain opacity-60">
            <input type="text" placeholder="Search posts"
                class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
        </div>
    </div>
@endsection

@section('content')

    <div class="space-y-5">

        {{-- Top Action Section --}}
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            {{-- Tabs --}}
            <x-filters-tab :tabs="$tabs" />

            {{-- Kanan: Filter + New Post --}}
            <div class="flex items-center gap-2 justify-start xl:justify-end">

                {{-- Filter Dropdown --}}
                <x-filters-dropdown-category :categories="$categories" routeName="post" />

                {{-- New Post Button --}}
                <a href="{{ route('post.create') }}"
                    class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                    <span>New Post</span>
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
        </div>

        {{-- Table Wrapper --}}
        <div class="overflow-hidden rounded-[20px] border border-acmi-blueprimer bg-white">

            {{-- Table Header --}}
            <div
                class="grid grid-cols-12 items-center gap-3 border-b border-acmi-blueprimer px-4 py-3 text-xs font-medium text-gray-600">
                <div class="col-span-7 flex items-center gap-3">
                    <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-gray-300 cursor-pointer">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Title</span>
                </div>
                <div class="col-span-2 text-center">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Stats</span>
                </div>
                <div class="col-span-2 text-left">
                    <span class="rounded-md bg-[#EEF2FF] px-2 py-1 text-[11px] font-medium text-gray-700">Date</span>
                </div>

                {{-- Titik 3 di header untuk bulk action --}}
                <div class="col-span-1 flex justify-center">
                    <div class="relative" id="bulkActionWrapper">
                        <button type="button" id="bulkActionBtn" onclick="toggleBulkMenu()"
                            class="hidden h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                            <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                        </button>

                        <div id="bulkActionMenu"
                            class="absolute right-0 top-full z-50 mt-1 hidden w-36 rounded-xl border border-gray-200 bg-white py-1 shadow-lg">
                            <button type="button" onclick="bulkDelete()"
                                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                                Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-acmi-blueprimer/70">
                @forelse($posts as $post)
                    <div class="grid grid-cols-12 items-center gap-3 px-4 py-4 transition hover:bg-gray-50 row-item">

                        {{-- Title --}}
                        <div class="col-span-7 flex items-center gap-3">
                            <input type="checkbox" name="selected_posts[]" value="{{ $post->id }}"
                                class="post-checkbox h-4 w-4 rounded border-gray-300 cursor-pointer">
                            <p class="text-sm font-medium text-gray-900">
                                <a href="{{ route('post.edit', $post) }}" class="hover:text-acmi-blueprimer transition">
                                    {{ $post->title }}
                                </a>
                            </p>
                        </div>

                        {{-- Stats --}}
                        <div class="col-span-2 flex items-center justify-center gap-1 text-sm text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                            <span>{{ $post->views ?? 0 }}</span>
                        </div>

                        {{-- Date --}}
                        <div class="col-span-2 text-xs text-gray-600">
                            {{ optional($post->created_at)->format('Y/m/d \a\t h:i a') }}
                        </div>

                        {{-- Titik 3 per row --}}
                        <div class="col-span-1 flex justify-center">
                            <div class="relative" id="action-wrapper-{{ $post->id }}">
                                <button type="button" onclick="toggleActionMenu({{ $post->id }})"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                                    <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                </button>

                                <div id="action-menu-{{ $post->id }}"
                                    class="absolute right-0 top-full z-50 mt-1 hidden w-36 rounded-xl border border-gray-200 bg-white py-1 shadow-lg">
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('post.destroy', $post->id) }}', 'Are you sure want to delete this post?')"
                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="flex min-h-[340px] items-center justify-center px-6 py-10">
                        <p class="text-sm italic text-gray-400">
                            No post content has been created yet. Please click "New Post".
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <form id="delete-item-form" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

@endsection


@push('scripts')
    <script>
        // Select All
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                cb.closest('.row-item').classList.toggle('bg-blue-50', this.checked);
            });
            updateBulkButton();
        });

        // Select individual
        document.querySelectorAll('.post-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                this.closest('.row-item').classList.toggle('bg-blue-50', this.checked);

                const all = document.querySelectorAll('.post-checkbox');
                const checked = document.querySelectorAll('.post-checkbox:checked');
                document.getElementById('selectAll').checked = all.length === checked.length;
                document.getElementById('selectAll').indeterminate = checked.length > 0 && checked.length <
                    all.length;
                updateBulkButton();
            });
        });

        // Tampilkan/sembunyikan bulk action button di header
        function updateBulkButton() {
            const checked = document.querySelectorAll('.post-checkbox:checked');
            const btn = document.getElementById('bulkActionBtn');
            if (checked.length > 0) {
                btn.classList.remove('hidden');
                btn.classList.add('flex');
            } else {
                btn.classList.add('hidden');
                btn.classList.remove('flex');
            }
        }

        // Toggle bulk menu di header
        function toggleBulkMenu() {
            document.getElementById('bulkActionMenu').classList.toggle('hidden');
        }

        // Bulk delete
        function bulkDelete() {
            const checked = document.querySelectorAll('.post-checkbox:checked');
            if (checked.length === 0) return;

            if (!confirm(`Are you sure want to delete ${checked.length} post(s)?`)) return;

            const ids = Array.from(checked).map(cb => cb.value);

            fetch('{{ route('post.bulkDestroy') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        ids
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
        }

        // Toggle action menu per row
        function toggleActionMenu(id) {
            document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
                if (menu.id !== `action-menu-${id}`) menu.classList.add('hidden');
            });
            document.getElementById(`action-menu-${id}`).classList.toggle('hidden');
        }

        // Tutup semua dropdown kalau klik luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[id^="action-wrapper-"]')) {
                document.querySelectorAll('[id^="action-menu-"]').forEach(m => m.classList.add('hidden'));
            }
            if (!e.target.closest('#bulkActionWrapper')) {
                document.getElementById('bulkActionMenu').classList.add('hidden');
            }
        });
    </script>
@endpush
