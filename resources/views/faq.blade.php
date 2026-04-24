@extends('layouts.app')

@section('title', 'ACMI - FaQ Management')
@section('page_title', 'FaQ')

@section('header_right')
    <button onclick="openAddModal()" class="bg-#0C1C87 text-black px-6 py-2 rounded-lg text-base font-base shadow-md hover:bg-#0B1357 transition-all mt-5">
        Add FaQ
    </button>
@endsection

@section('content')
    <div class="space-y-3 max-w-5xl">
        @for ($i = 0; $i < 8; $i++)
        <div class="rounded-lg overflow-hidden border border-blue-100 shadow-sm">
            <div onclick="toggleFaq(this)" class="flex items-center justify-between px-6 py-3 bg-[#DAE7FF] cursor-pointer">
                <p class="text-black text-sm font-medium">Kenapa Memilih Acmi untuk di redesign oleh Tim Breyole Ujikom?</p>
                <div class="flex items-center gap-3">
                    <div class="bg-[#3D4BC9] p-1.5 rounded-md shadow-sm flex items-center justify-center cursor-pointer hover:bg-blue-800 transition">
                        <img src="{{ asset('assets/iconedit.svg') }}" alt="Edit" class="w-4 h-4 object-contain">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 text-gray-700 arrow-icon transition-transform duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>
            <div class="faq-answer hidden px-6 py-4 bg-#F4F8FF border-t border-blue-100">
                <p class="text-gray-700 text-xs leading-relaxed italic">Karena kita keren</p>
            </div>
        </div>
        @endfor
    </div>

    <div id="addFaqModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white w-600px rounded-xl shadow-2xl relative overflow-hidden">
            <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-black">Add FaQ</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-black transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('faq.store') }}" method="POST" class="px-8 py-6 flex flex-col gap-5">
                @csrf
                
                <div>
                    <label for="question" class="block text-xs font-bold text-black mb-2">Question</label>
                    <input type="text" id="question" name="question" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#0C1C87] text-sm">
                </div>

                <div>
                    <label for="answer" class="block text-xs font-bold text-black mb-2">Answer</label>
                    <textarea id="answer" name="answer" rows="4" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#0C1C87] text-sm resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-2.5 border border-gray-300 rounded-md text-xs font-bold text-gray-700 hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0C1C87] text-white rounded-md text-xs font-bold hover:bg-[#0B1357] transition-all shadow-sm">
                        Publish Now
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Script untuk Accordion
    function toggleFaq(element) {
        const answer = element.nextElementSibling;
        const arrow = element.querySelector('.arrow-icon');
        answer.classList.toggle('hidden');
        arrow.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    // 3. Script untuk Modal Add FaQ
    function openAddModal() {
        document.getElementById('addFaqModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addFaqModal').classList.add('hidden');
    }
</script>
@endpush