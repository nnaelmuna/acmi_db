@extends('layouts.app')

@section('title', 'Create New Post - ACMI')
@section('page_title', 'Create New Post')

@section('content')
<div class="max-w-5xl">
    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf <div class="grid grid-cols-3 gap-8">
            <div class="col-span-2 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Add Title</label>
                    <input type="text" name="title" 
                           class="w-full border border-[#C4C4C4] rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#0C1C87] focus:border-[#0C1C87]" 
                           placeholder="Ketik judul postingan..." required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Add Description</label>
                    <input type="text" name="description" 
                           class="w-full border border-[#C4C4C4] rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#0C1C87] focus:border-[#0C1C87]" 
                           placeholder="Ringkasan singkat...">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Filled Content</label>
                    <div class="border border-[#C4C4C4] rounded-lg bg-white overflow-hidden">
                        <div class="border-b border-[#C4C4C4] px-4 py-2 flex items-center gap-4 bg-[#F8F9FB] text-gray-500">
                            <i class="fa-solid fa-bold cursor-pointer hover:text-black"></i>
                            <i class="fa-solid fa-italic cursor-pointer hover:text-black"></i>
                            <i class="fa-solid fa-list-ul cursor-pointer hover:text-black"></i>
                            <i class="fa-solid fa-link cursor-pointer hover:text-black"></i>
                        </div>
                        <textarea name="content" rows="12" 
                                  class="w-full p-4 focus:outline-none resize-none" 
                                  placeholder="Tulis isi konten di sini..."></textarea>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('post') }}" class="text-gray-500 text-sm font-medium hover:underline">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Post
                    </a>
                </div>
                
            </div>

            <div class="col-span-1 space-y-6">
                <div class="bg-[#E4E4E4]/50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-black mb-4">Select Category</label>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach(['Edukasi Bisnis', 'Networking', 'Artikel', 'Event', 'Promo', 'Pengumuman', 'Press Release'] as $cat)
                        <label class="flex items-center gap-3 text-xs text-gray-700 cursor-pointer group">
                            <input type="radio" name="category" value="{{ $cat }}" 
                                   class="w-4 h-4 text-[#0C1C87] border-gray-300 focus:ring-[#0C1C87]" required>
                            <span class="group-hover:text-black">{{ $cat }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Upload Image</label>
                    <label class="border-2 border-dashed border-[#C4C4C4] rounded-xl h-64 flex flex-col items-center justify-center bg-white cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden relative">
                        <i class="fa-solid fa-image text-4xl text-gray-300 mb-2 group-hover:text-[#0C1C87]"></i>
                        <span class="text-xs text-gray-400 group-hover:text-gray-600">Klik untuk upload gambar</span>
                        <input type="file" name="image" class="hidden" accept="image/*">
                    </label>
                    <p class="text-[10px] text-gray-400 mt-2">*Maksimal ukuran gambar 2MB (JPG, PNG, WebP)</p>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" name="publish" value="1" 
                            class="w-full bg-[#0C1C87] text-white py-3 rounded-xl font-semibold shadow-lg hover:bg-[#0B1357] transition-all">
                        Publish Now
                    </button>
                    <button type="submit" name="draft" value="1" 
                            class="w-full border border-[#C4C4C4] text-black py-3 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        Save to Draft
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection