@extends('layouts.app')

@section('title', 'ACMI - Post Management')
@section('page_title', 'Post')

@section('header_right')
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <input type="text" placeholder="Search post..." class="block w-72 pl-12 pr-4 py-2.5 bg-white border border-gray-200 rounded-full text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-custom-blue shadow-sm transition-all">
    </div>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center bg-[#E9E9E9]/50 p-1.5 rounded-2xl border border-gray-200">
            <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 bg-white rounded-xl shadow-sm text-sm font-base text-black transition-all">
                Published <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
            </button>
            <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-base text-gray-500 hover:text-black transition-all">
                Draft <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
            </button>
            <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-base text-gray-500 hover:text-black transition-all">
                Archived <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
            </button>
        </div>

        <button onclick="location.href='{{ route('post.create') }}'" class="flex items-center gap-3 bg-[#0C1C87] text-white px-6 py-3 rounded-2xl font-base shadow-lg hover:bg-[#0B1357] transition-all transform hover:-translate-y-0.5">
            New Post
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
    </div>

    <div class="bg-white rounded-4xl border border-gray-100 shadow-sm p-6 min-h-[400px] flex items-center justify-center">
         <p class="text-gray-400 font-light italic">Belum ada konten untuk ditampilkan.</p>
    </div>
@endsection

@push('scripts')
<script>
    function switchTab(element) {
        const tabs = document.querySelectorAll('.tab-item');
        tabs.forEach(tab => {
            tab.classList.remove('bg-white', 'shadow-sm', 'text-black');
            tab.classList.add('text-gray-500');
        });
        element.classList.remove('text-gray-500');
        element.classList.add('bg-white', 'shadow-sm', 'text-black');
    }
</script>
@endpush