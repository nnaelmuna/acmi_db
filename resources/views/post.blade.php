<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACMI - Post Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] font-sans">

    <div class="flex">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col px-10 pt-9">
            
            <nav class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold text-black mt-4">Post</h1>
                    <p class="text-black text-base mt-1">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400 group-focus-within:text-custom-blue transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" 
                               placeholder="Search post..." 
                               class="block w-72 pl-12 pr-4 py-2.5 bg-white border border-gray-200 rounded-full text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent shadow-sm transition-all">
                    </div>
                </div>
            </nav>

            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center bg-[#E9E9E9]/50 p-1.5 rounded-2xl border border-gray-200">
                    <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 bg-white rounded-xl shadow-sm text-sm font-base text-black transition-all">
                        Published
                        <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
                    </button>
                    <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-base text-gray-500 hover:text-black transition-all">
                        Draft
                        <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
                    </button>
                    <button onclick="switchTab(this)" class="tab-item flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-base text-gray-500 hover:text-black transition-all">
                        Archived
                        <span class="bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
                    </button>
                </div>

                <button onclick="openModal()" class="flex items-center gap-3 bg-[#0C1C87] text-white px-6 py-3 rounded-2xl font-base shadow-lg hover:bg-[#0B1357] transition-all transform hover:-translate-y-0.5">
                    New Post
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-6 min-h-[400px] flex items-center justify-center">
                 <p class="text-gray-400 font-light italic">Belum ada konten untuk ditampilkan.</p>
            </div>
        </div>
    </div>

    <div id="postModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/20 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white w-[750px] rounded-2xl shadow-2xl relative overflow-hidden border border-gray-400">
            
            <div class="flex justify-between items-center px-10 py-7">
                <h2 class="text-xl font-bold text-black">Create New Post</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="#" class="px-10 pb-10 flex gap-8">
                <div class="flex-1 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Title</label>
                        <input type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C1C87]/10 focus:border-[#0C1C87]">
                    </div>
            
                    <div>
                        <label class="block text-sm font-semibold mb-2">Description</label>
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex gap-4 text-gray-400 text-xs font-bold">
                                <span class="cursor-pointer hover:text-black">B</span>
                                <span class="italic cursor-pointer hover:text-black">I</span>
                                <span class="underline cursor-pointer hover:text-black">U</span>
                            </div>
                            <textarea rows="5" class="w-full px-4 py-3 focus:outline-none resize-none"></textarea>
                        </div>
                    </div>
            
                    <div>
                        <label class="block text-sm font-semibold mb-2">Upload Image</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl h-32 flex flex-col items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 group transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-300 group-hover:text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v12.905a1.5 1.5 0 0 0 1.5 1.5Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            
                <div class="w-60 flex flex-col justify-between">
                    <div class="bg-gray-100 rounded-xl p-5 mt-7 pb-10">
                        <label class="block text-sm font-semibold mb-4 text-black">Select Category</label>
                        <div class="space-y-3">
                            @foreach(['Edukasi Bisnis', 'Networking', 'Artikel', 'Event', 'Promo', 'Pengumuman', 'Press Release'] as $category)
                            <label class="flex items-center gap-3 text-xs text-gray-600 cursor-pointer hover:text-black transition-all">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#0C1C87] focus:ring-[#0C1C87] accent-[#0C1C87]">
                                <span>{{ $category }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
            
                    <div class="space-y-3 bg-gray-100 rounded-xl p-5 mt-10 pb-5">
                        <button type="submit" class="w-full bg-[#000EA6] text-white py-3 rounded-xl font-base text-sm shadow-lg hover:bg-[#000B82] transition-all">Publish Now</button>
                        <button type="button" class="w-full border border-gray-300 text-black py-3 rounded-xl font-base text-sm hover:bg-gray-50 transition-all">Save to draft</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika Tab Filter (yang sudah ada)
        function switchTab(element) {
            const tabs = document.querySelectorAll('.tab-item');
            tabs.forEach(tab => {
                tab.classList.remove('bg-white', 'shadow-sm', 'text-black');
                tab.classList.add('text-gray-500');
            });
            element.classList.remove('text-gray-500');
            element.classList.add('bg-white', 'shadow-sm', 'text-black');
        }

        // Logika Buka Tutup Modal
        const modal = document.getElementById('postModal');

        function openModal() {
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Klik di area burem buat nutup modal
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>
</html>