@extends('layouts.app')

@section('title', 'ACMI - FAQ Management')
@section('page_title', 'FAQ')

@section('content')
    <div class="w-full space-y-4">

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

        {{-- Filter Tabs + Button --}}
        <div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            <x-filters-tab :tabs="$tabs"/>

            <div class="flex justify-end">
                <button type="button" onclick="openAddModal()"
                    class="inline-flex items-center gap-3 rounded-lg bg-acmi-blueprimer px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                    <span>Add FAQ</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- FAQ List --}}
        @forelse($faqs as $faq)
            <div class="overflow-hidden rounded-xl bg-acmi-softblue shadow-sm">
                <div class="flex items-center justify-between gap-4 px-5 py-4">
                    <p class="flex-1 text-sm font-medium leading-6 text-gray-900 md:text-[15px]">
                        {{ $faq->question }}
                    </p>

                    <div class="flex shrink-0 items-center gap-3">
                        <button type="button" data-id="{{ $faq->id }}" data-question="{{ e($faq->question) }}"
                            data-answer="{{ e($faq->answer) }}" data-status="{{ $faq->status ?? 'published' }}"
                            onclick="openEditModalFromButton(this)"
                            class="flex h-9 w-9 items-center justify-center rounded-md bg-acmi-blueaccent text-white transition hover:bg-acmi-blueprimer">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </button>

                        <button type="button"
                            onclick="openDeleteModal('{{ route('faq.destroy', $faq->id) }}', 'Are you sure want to delete this FAQ?')"
                            class="flex h-9 w-9 items-center justify-center rounded-md bg-acmi-yellowaccent text-white transition hover:opacity-90">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>

                        <button type="button" onclick="toggleFaq(this)"
                            class="flex h-8 w-8 items-center justify-center text-gray-800 transition">
                            <i class="faq-arrow fa-solid fa-chevron-down text-sm transition-transform duration-300"></i>
                        </button>
                    </div>
                </div>

                <div class="faq-answer hidden border-t border-white/70 bg-[#F4F8FF] px-5 py-4">
                    <p class="text-sm leading-relaxed text-gray-700">
                        {{ $faq->answer }}
                    </p>
                </div>
            </div>
        @empty
            <div
                class="flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-acmi-bordercolor bg-white">
                <p class="text-sm italic text-gray-400">No FAQ available yet.</p>
            </div>
        @endforelse
        <x-pagination :paginator="$faqs"/>
    </div>

    {{-- Add FAQ Modal --}}
    <div id="addFaqModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="addFaqBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Add FAQ</h2>

                <button type="button" onclick="closeAddModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('faq.store') }}" method="POST" class="space-y-5 px-6 pb-6 pt-5">
                @csrf

                <div>
                    <label for="question" class="mb-2 block text-xs font-semibold text-gray-600">Question</label>
                    <input type="text" id="question" name="question" required value="{{ old('question') }}"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label for="answer" class="mb-2 block text-xs font-semibold text-gray-600">Answer</label>
                    <textarea id="answer" name="answer" rows="6" required
                        class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">{{ old('answer') }}</textarea>
                </div>
                <x-form-status-buttons />
            </form>
        </div>
    </div>

    {{-- Edit FAQ Modal --}}
    <div id="editFaqModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
        <div id="editFaqBox"
            class="w-full max-w-xl scale-95 rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300">

            <div class="flex items-center justify-between px-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-800">Edit FAQ</h2>

                <button type="button" onclick="closeEditModal()"
                    class="inline-flex items-center justify-center text-gray-500 transition hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editFaqForm" method="POST" class="space-y-5 px-6 pb-6 pt-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_question" class="mb-2 block text-xs font-semibold text-gray-600">Question</label>
                    <input type="text" id="edit_question" name="question" required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
                </div>

                <div>
                    <label for="edit_answer" class="mb-2 block text-xs font-semibold text-gray-600">Answer</label>
                    <textarea id="edit_answer" name="answer" rows="6" required
                        class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"></textarea>
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

        function toggleFaq(element) {
            const wrapper = element.closest('.overflow-hidden');
            const answer = wrapper.querySelector('.faq-answer');
            const arrow = element.querySelector('.faq-arrow');

            answer.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
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
            animateModalOpen('addFaqModal', 'addFaqBox');
        }

        function closeAddModal() {
            animateModalClose('addFaqModal', 'addFaqBox');
        }

        function openEditModalFromButton(button) {
            const form = document.getElementById('editFaqForm');
            const question = document.getElementById('edit_question');
            const answer = document.getElementById('edit_answer');
            const status = document.getElementById('edit_status');

            form.action = `/faq/${button.dataset.id}`;
            question.value = button.dataset.question;
            answer.value = button.dataset.answer;
            status.value = button.dataset.status || 'published';

            animateModalOpen('editFaqModal', 'editFaqBox');
        }

        function closeEditModal() {
            animateModalClose('editFaqModal', 'editFaqBox');
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
            const addModal = document.getElementById('addFaqModal');
            const editModal = document.getElementById('editFaqModal');

            if (e.target === addModal) closeAddModal();
            if (e.target === editModal) closeEditModal();
        });
    </script>
@endpush
