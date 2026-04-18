@extends('layouts.app')

@section('title', 'ACMI - FaQ Management')
@section('page_title', 'FaQ')

@section('header_right')
    <button class="bg-[#0C1C87] text-white px-6 py-2 rounded-lg text-base font-base shadow-md hover:bg-[#0B1357] transition-all mt-5">
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
                    <div class="bg-[#3D4BC9] p-1.5 rounded-md shadow-sm flex items-center justify-center">
                        <img src="{{ asset('assets/iconedit.svg') }}" alt="Edit" class="w-4 h-4 object-contain">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 text-gray-700 arrow-icon transition-transform duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>
            <div class="faq-answer hidden px-6 py-4 bg-[#F4F8FF] border-t border-blue-100">
                <p class="text-gray-700 text-xs leading-relaxed italic">Karena kita keren</p>
            </div>
        </div>
        @endfor
    </div>
@endsection

@push('scripts')
<script>
    function toggleFaq(element) {
        const answer = element.nextElementSibling;
        const arrow = element.querySelector('.arrow-icon');
        answer.classList.toggle('hidden');
        arrow.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }
</script>
@endpush