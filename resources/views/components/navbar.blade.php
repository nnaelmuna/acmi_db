

<div class="absolute top-6 left-1/2 -translate-x-1/2 w-[92%] max-w-6xl z-50">
    <div class="flex items-center justify-between px-6 py-3 rounded-full 
                bg-white/40 backdrop-blur-xl border border-gray-200 
                shadow-lg">

        {{-- Gunakan asset() untuk path gambar di Laravel --}}
        <img src="{{ asset('assets/logo-acmi-new.svg') }}" class="h-11 object-contain" alt="Logo ACMI"/>

        <div class="hidden md:flex items-center gap-8 text-sm font-poppins text-gray-700">
            <a href="#" class="hover:text-black transition">Profile</a>
            <a href="#" class="hover:text-black transition">Pengurus</a>
            <a href="#" class="hover:text-black transition">Anggota</a>
            <a href="#" class="hover:text-black transition">Gallery</a>
        </div>

        <div class="flex items-center gap-3">
            <button class="px-3 h-9 rounded-full bg-gray-100 text-xs font-semibold">EN</button>
            <button class="bg-orange-500 text-white px-5 py-2 rounded-full text-sm font-semibold shadow hover:scale-105 transition">
                Join Now
            </button>
        </div>
    </div>
</div>