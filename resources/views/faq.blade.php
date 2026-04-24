@extends('layouts.app')

@section('title', 'ACMI - FaQ Management')
@section('page_title', 'FaQ')

@section('header_right')
    <button
        type="button"
        onclick="openAddModal()"
        class="inline-flex items-center rounded-xl bg-acmi-blueprimer px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-acmi-darkblue"
    >
        Add FaQ
    </button>
@endsection

@section('content')
<div class="w-full space-y-4">

    @if(session('success'))
        <div class="rounded-lg bg-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @forelse($faqs as $faq)
        <div class="overflow-hidden rounded-xl bg-acmi-softblue shadow-sm">
            <div class="flex items-center justify-between gap-4 px-5 py-4">
                <p class="text-sm font-medium text-gray-900 md:text-[15px]">
                    {{ $faq->question }}
                </p>

                <div class="flex shrink-0 items-center gap-3">

                    {{-- EDIT --}}
                    <button
                        type="button"
                        data-id="{{ $faq->id }}"
                        data-question="{{ e($faq->question) }}"
                        data-answer="{{ e($faq->answer) }}"
                        data-status="{{ $faq->status ?? 'published' }}"
                        onclick="openEditModalFromButton(this)"
                        class="flex h-9 w-9 items-center justify-center rounded-md bg-acmi-blueaccent text-white transition hover:bg-acmi-blueprimer"
                    >
                        <i class="fa-solid fa-pen text-sm"></i>
                    </button>

                    {{-- DELETE --}}
                    <button
                        type="button"
                        onclick="openDeleteModal('{{ route('faq.destroy', $faq->id) }}', 'Are you sure want to delete this FaQ?')"
                        class="flex h-9 w-9 items-center justify-center rounded-md bg-acmi-yellowaccent text-white transition hover:opacity-90"
                    >
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>

                    {{-- ARROW --}}
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="flex h-8 w-8 items-center justify-center text-gray-800 transition"
                    >
                        <i class="faq-arrow fa-solid fa-chevron-down text-sm transition-transform duration-300"></i>
                    </button>
                </div>
            </div>

            {{-- ANSWER --}}
            <div class="faq-answer hidden border-t border-white/70 bg-[#F4F8FF] px-5 py-4">
                <p class="text-sm leading-relaxed text-gray-700">
                    {{ $faq->answer }}
                </p>
            </div>
        </div>
    @empty
        <div class="flex min-h-[520px] w-full items-center justify-center rounded-2xl border border-dashed border-acmi-bordercolor bg-white">
            <p class="text-sm italic text-gray-400">No FAQ available yet.</p>
        </div>
    @endforelse
</div>

{{-- ADD MODAL --}}
<div id="addFaqModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
    <div id="addFaqBox" class="w-full max-w-xl scale-95 rounded-2xl bg-acmi-darkblue opacity-0 shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between px-6 pt-6">
            <h2 class="text-lg font-semibold text-white">Add FaQ</h2>

            <button type="button" onclick="closeAddModal()" class="text-white/80 transition hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('faq.store') }}" method="POST" class="space-y-5 px-6 pb-6 pt-5">
            @csrf

            <div>
                <label for="question" class="mb-2 block text-xs font-semibold text-white">Question</label>
                <input
                    type="text"
                    id="question"
                    name="question"
                    required
                    value="{{ old('question') }}"
                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"
                >
            </div>

            <div>
                <label for="answer" class="mb-2 block text-xs font-semibold text-white">Answer</label>
                <textarea
                    id="answer"
                    name="answer"
                    rows="6"
                    required
                    class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"
                >{{ old('answer') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button
                    type="submit"
                    name="status"
                    value="draft"
                    class="rounded-md border border-white/50 px-4 py-2 text-xs font-medium text-white transition hover:bg-white/10"
                >
                    Save to draft
                </button>

                <button
                    type="submit"
                    name="status"
                    value="published"
                    class="rounded-md bg-white px-4 py-2 text-xs font-medium text-acmi-blueprimer transition hover:bg-gray-100"
                >
                    Publish Now
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editFaqModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 backdrop-blur-sm">
    <div id="editFaqBox" class="w-full max-w-xl scale-95 rounded-2xl bg-acmi-darkblue opacity-0 shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between px-6 pt-6">
            <h2 class="text-lg font-semibold text-white">Edit FaQ</h2>

            <button type="button" onclick="closeEditModal()" class="text-white/80 transition hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editFaqForm" method="POST" class="space-y-5 px-6 pb-6 pt-5">
            @csrf
            @method('PUT')

            <input type="hidden" name="status" id="edit_status" value="published">

            <div>
                <label for="edit_question" class="mb-2 block text-xs font-semibold text-white">Question</label>
                <input
                    type="text"
                    id="edit_question"
                    name="question"
                    required
                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"
                >
            </div>

            <div>
                <label for="edit_answer" class="mb-2 block text-xs font-semibold text-white">Answer</label>
                <textarea
                    id="edit_answer"
                    name="answer"
                    rows="6"
                    required
                    class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"
                ></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="rounded-md border border-white/50 px-5 py-2 text-xs font-medium text-white transition hover:bg-white/10"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-md bg-white px-5 py-2 text-xs font-medium text-acmi-blueprimer transition hover:bg-gray-100"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

    window.addEventListener('click', function(e) {
        const addModal = document.getElementById('addFaqModal');
        const editModal = document.getElementById('editFaqModal');

        if (e.target === addModal) {
            closeAddModal();
        }

        if (e.target === editModal) {
            closeEditModal();
        }
    });
</script>
@endpush