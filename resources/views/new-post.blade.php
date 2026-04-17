<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACMI - Create New Post</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] font-sans h-screen overflow-hidden">

    <div class="flex h-full">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col h-full overflow-y-auto px-12 py-10">
            
            <header class="mb-8">
                <h1 class="text-2xl font-bold text-black">Create New Post</h1>
            </header>

            <form action="#" class="grid grid-cols-12 gap-8 pb-20">
                <div class="col-span-7 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Add Title</label>
                        <input type="text" placeholder="Enter title here..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C1C87]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Add Description</label>
                        <input type="text" placeholder="Short summary..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C1C87]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Filled</label>
                        <div class="border border-gray-300 rounded-xl overflow-hidden bg-white">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-wrap gap-5 text-gray-600 text-sm items-center">
                                <div class="flex gap-3 border-r pr-4">
                                    <span class="cursor-pointer hover:text-black">↩</span>
                                    <span class="cursor-pointer hover:text-black">↪</span>
                                </div>
                                <select class="bg-transparent focus:outline-none font-medium">
                                    <option>Paragraph</option>
                                </select>
                                <div class="flex gap-4 font-bold">
                                    <span class="cursor-pointer hover:text-black">B</span>
                                    <span class="italic cursor-pointer hover:text-black">I</span>
                                    <span class="underline cursor-pointer hover:text-black">U</span>
                                </div>
                                <div class="flex gap-3">
                                    <span>≡</span><span>⬌</span><span>≡</span>
                                </div>
                                <span class="font-bold">···</span>
                            </div>
                            <textarea rows="15" class="w-full px-6 py-4 focus:outline-none resize-none" placeholder="Write your content here..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <a href="{{ route('post.index') }}" class="px-8 py-2.5 border border-gray-300 rounded-lg text-gray-600 font-medium hover:bg-gray-50">Cancel</a>
                    </div>
                </div>

                <div class="col-span-5 space-y-8">
                    <div class="bg-gray-200/70 rounded-xl p-6">
                        <h3 class="font-bold text-sm mb-5 text-black">Select Category</h3>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                            @foreach(['Edukasi Bisnis', 'Artikel', 'Promo', 'Networking', 'Event', 'Pengumuman', 'Press Release'] as $category)
                            <label class="flex items-center gap-3 text-xs text-gray-700 cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#0C1C87] focus:ring-[#0C1C87] accent-[#0C1C87]">
                                <span class="group-hover:text-black">{{ $category }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-sm mb-3">Upload Image</h3>
                        <div class="border border-gray-300 rounded-xl bg-white aspect-square flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-all border-dashed border-2 group">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v12.905a1.5 1.5 0 0 0 1.5 1.5Z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-10">
                        <button type="button" class="px-6 py-2.5 border border-gray-300 rounded-lg text-black font-medium hover:bg-gray-50">Save to Draft</button>
                        <button type="submit" class="px-8 py-2.5 bg-[#0C1C87] text-white rounded-lg font-bold hover:bg-[#0B1357] shadow-lg shadow-blue-900/20">Publish Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>