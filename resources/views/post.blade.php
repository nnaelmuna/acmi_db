@extends('layouts.app')

@section('title', 'ACMI - Post Management')
@section('page_title', 'Post')

@section('header_right')
    <form action="{{ route('post') }}" method="GET" class="w-full max-w-[220px] md:max-w-[260px] lg:max-w-[300px]">
        <input type="hidden" name="status" value="{{ request('status', 'published') }}">
        <input type="hidden" name="category" value="{{ request('category') }}">

        <div class="relative w-full">
            <img src="{{ asset('assets/icons/search.svg') }}" alt="Search"
                class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 object-contain opacity-60">

            <input id="postSearchInput" type="text" name="search" value="{{ request('search') }}"
                placeholder="Search posts"
                class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20">
        </div>
    </form>
@endsection


@section('content')

    @if (session('success'))
        <div id="successAlert"
            class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700 transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-5">

        {{-- Top Action Section --}}
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            {{-- Tabs --}}
            <x-filters-tab :tabs="$tabs" />

            {{-- Filter + New Post --}}
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
        <div class="flex min-h-[calc(100vh-230px)] flex-col">
            <div class="overflow-visible rounded-[20px] border border-acmi-blueprimer bg-white">

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

                    {{-- Titik 3  --}}
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
                                    {{ request('status') === 'trash' ? 'Delete Permanently' : 'Delete Selected' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Body --}}
                <div class="divide-y divide-acmi-blueprimer/70 overflow-visible">
                    @forelse($posts as $post)
                        <div class="relative grid grid-cols-12 items-center gap-3 px-4 py-4 transition row-item">

                            {{-- Title --}}
                            <div class="col-span-7 flex items-center gap-3">
                                <input type="checkbox" name="selected_posts[]" value="{{ $post->id }}"
                                    class="post-checkbox h-4 w-4 rounded border-gray-300 cursor-pointer">
                                <p class="text-sm font-medium text-gray-900">
                                    @if (request('status') === 'trash')
                                        <span class="cursor-default text-gray-500">
                                            {{ $post->title }}
                                        </span>
                                    @else
                                        <a href="{{ route('post.edit', $post) }}"
                                            class="hover:text-acmi-blueprimer transition">
                                            {{ $post->title }}
                                        </a>
                                    @endif
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
                                        class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 transition">
                                        <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                    </button>

                                    <div id="action-menu-{{ $post->id }}"
                                        class="absolute right-6 top-full z-[9999] mt-1 hidden w-52 rounded-xl border border-gray-200 bg-white py-1 shadow-lg">

                                        @if (request('status') === 'trash')
                                            <form action="{{ route('posts.restore', $post->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition">
                                                    <i class="fa-solid fa-rotate-left text-xs"></i>
                                                    Restore
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="openDeleteModal('{{ route('posts.forceDelete', $post->id) }}', 'Permanently Delete? This data cannot be recovered!')"
                                                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                Delete Permanently
                                            </button>
                                        @else
                                            <button type="button"
                                                onclick="openDeleteModal('{{ route('post.destroy', $post->id) }}', 'Are you sure want to move this post to trash?')"
                                                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                Move to Trash
                                            </button>
                                        @endif

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

            <div class="mt-auto">
                <x-pagination :paginator="$posts" />
            </div>
        </div>
    </div>

    <form id="bulk-delete-form"
        action="{{ request('status') === 'trash' ? route('posts.bulkForceDelete') : route('post.bulkDestroy') }}"
        method="POST" style="display:none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulk_delete_ids">
    </form>

    <div id="bulkDeleteModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div id="bulkDeleteBox"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-8 text-center opacity-0 shadow-xl transition-all duration-300">

            <h2 id="bulkDeleteMessage" class="mb-8 text-lg font-semibold text-black">
                Are you sure want to move selected post(s) to trash?
            </h2>

            <div class="flex justify-center gap-4">
                <button type="button" onclick="closeBulkDeleteModal()"
                    class="flex-1 rounded-xl bg-gray-100 px-6 py-3 font-semibold text-gray-700 transition hover:bg-gray-200">
                    Cancel
                </button>

                <button type="button" onclick="document.getElementById('bulk-delete-form').submit()"
                    class="flex-1 rounded-xl bg-red-500 px-6 py-3 font-semibold text-white shadow-lg shadow-red-200 transition hover:bg-red-600">
                    Delete
                </button>
            </div>
        </div>
    </div>

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

        // Tampilkan/sembunyikan bulk action
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

        // Bulk Delete
        function bulkDelete() {
            const checked = document.querySelectorAll('.post-checkbox:checked');

            if (checked.length === 0) return;

            const ids = Array.from(checked).map(cb => cb.value);

            document.getElementById('bulk_delete_ids').value = JSON.stringify(ids);
            document.getElementById('bulkDeleteMessage').innerText =
                '{{ request('status') === 'trash'
                    ? 'Permanently delete selected post(s)? This data cannot be recovered!'
                    : 'Are you sure want to move selected post(s) to trash?' }}';

            const modal = document.getElementById('bulkDeleteModal');
            const box = document.getElementById('bulkDeleteBox');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeBulkDeleteModal() {
            const modal = document.getElementById('bulkDeleteModal');
            const box = document.getElementById('bulkDeleteBox');

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
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

        // Search
        const postSearchInput = document.getElementById('postSearchInput');
        let searchTimer;

        if (postSearchInput) {
            postSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }

        // notif beberapa detik
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
    </script>
@endpush
