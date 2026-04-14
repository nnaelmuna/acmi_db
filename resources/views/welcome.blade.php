@extends('layouts.app')

@section('title', 'ACMI - Bersinergi Memimpin Indonesia')

@section('content')

{{-- HERO SECTION --}}
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab" class="absolute inset-0 w-full h-full object-cover scale-105" />
    <div class="absolute inset-0 bg-gradient-to-b from-white/50 via-white/80 to-white/95"></div>

    <div class="relative z-10 text-center px-6 max-w-4xl">
        <div class="inline-block px-4 py-1 rounded-full bg-orange-100 text-orange-500 text-xs font-poppins mb-6">
            Asosiasi CEO & Eksekutif Indonesia
        </div>

        <h1 class="text-4xl md:text-7xl leading-tight text-gray-900">
            <span class="font-poppins font-semibold">Bersinergi, Bertumbuh,</span><br>
            <span class="font-serif font-bold italic text-orange-500">Memimpin Indonesia</span>
        </h1>

        <p class="mt-6 text-gray-600 text-sm md:text-base font-poppins max-w-xl mx-auto">
            Komunitas eksklusif bagi CEO & eksekutif puncak Indonesia untuk berjejaring dan berkembang.
        </p>

        <div class="mt-8 flex justify-center gap-4 flex-wrap">
            <button class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold shadow hover:bg-orange-600 transition">
                Gabung Keanggotaan
            </button>
            <button class="px-6 py-3 border border-orange-400 text-orange-500 rounded-lg font-semibold hover:bg-orange-50 transition">
                Eksplorasi Kegiatan Kami →
            </button>
        </div>

        {{-- Stats --}}
        <div class="mt-14 grid grid-cols-3 gap-4 max-w-3xl mx-auto">
            @php
                $stats = [
                    ['val' => '500+', 'label' => 'Anggota CEO'],
                    ['val' => '50+', 'label' => 'Acara Tahunan'],
                    ['val' => '15+', 'label' => 'Industri']
                ];
            @endphp
            @foreach($stats as $stat)
                <div class="bg-white/60 backdrop-blur-lg rounded-xl py-4">
                    <p class="text-2xl font-semibold font-poppins text-gray-900">{{ $stat['val'] }}</p>
                    <p class="text-xs text-gray-500 font-poppins">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PARTNER SECTION --}}
<section class="relative py-20 bg-white/90">
    <div class="text-center">
        <h2 class="text-gray-500 text-sm font-semibold tracking-widest">DIDUKUNG OLEH PARTNER STRATEGIS</h2>
        <div class="mt-10 grid grid-cols-2 md:grid-cols-6 gap-4 max-w-5xl mx-auto px-6">
            @foreach(['BCA', 'TELKOM', 'PERTAMINA', 'ASTRA', 'INDOFOOD', 'SINARMAS'] as $partner)
                <div class="bg-white/60 hover:-translate-y-1 hover:shadow-xl transition backdrop-blur-lg rounded-xl py-4 border border-gray-200">
                    <p class="font-semibold text-gray-600">{{ $partner }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- EVENT BANNER --}}
<section class="bg-white px-6 md:px-10 pt-10 pb-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-[#f5f5f7] border border-gray-200 rounded-2xl px-8 py-6 flex flex-col md:flex-row items-center justify-between shadow-sm gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-orange-100 text-orange-500 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sparkles"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold bg-orange-100 text-orange-500 px-3 py-1 rounded-full uppercase tracking-wider">
                        Event Eksklusif
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900 mt-2">ACMI Annual Summit 2025</h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        <i class="fa-regular fa-calendar"></i> 15-16 Maret 2025 - Jakarta Convention Center
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-gray-400">Early Bird Discount</p>
                    <p class="text-orange-500 font-bold text-lg">30% OFF</p>
                </div>
                <button class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-3 rounded-lg flex items-center gap-2 transition duration-300">
                    Daftar Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- CHALLENGE SECTION --}}
<section class="bg-white px-6 md:px-10 py-20">
    <div class="max-w-7xl mx-auto flex flex-col gap-16">
        
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-block px-4 py-1 rounded-full bg-orange-100 text-orange-500 text-xs font-poppins font-semibold mb-6 uppercase tracking-widest">
                Tantangan Pemimpin
            </div>
            <h1 class="text-4xl md:text-5xl leading-tight text-gray-900 font-poppins">
                <span class="font-semibold text-[#1a1a1a]">Menghadapi Kompleksitas Bisnis</span><br>
                <span class="font-serif font-bold italic text-orange-500">Sendirian?</span>
            </h1>
            <p class="mt-6 text-gray-500 text-base md:text-lg font-poppins max-w-2xl mx-auto leading-relaxed">
                Setiap CEO menghadapi tantangan unik yang memerlukan perspektif dan dukungan dari sesama pemimpin.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $challenges = [
                    ['icon' => 'fa-users', 'title' => 'Isolasi Strategis', 'desc' => 'Kurangnya forum sejawat untuk berdiskusi dan validasi ide bisnis strategis.'],
                    ['icon' => 'fa-bolt', 'title' => 'Perubahan Terlalu Cepat', 'desc' => 'Sulit mengikuti tren digital, regulasi, dan perkembangan ekonomi global.'],
                    ['icon' => 'fa-lock', 'title' => 'Keterbatasan Akses', 'desc' => 'Ke jaringan tingkat tinggi, mitra strategis, atau pakar kunci industri.'],
                    ['icon' => 'fa-bullseye', 'title' => 'Pengembangan Diri', 'desc' => 'Kebutuhan tetap relevan dan visionary sebagai pemimpin bisnis.'],
                ];
            @endphp

            @foreach($challenges as $item)
            <div class="bg-[#f5f5f7] border border-gray-200 rounded-2xl p-8 text-left hover:-translate-y-2 hover:shadow-xl transition duration-300 group">
                <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-xl flex items-center justify-center text-lg mb-6 group-hover:bg-orange-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                </div>
                <h3 class="font-poppins font-semibold text-gray-900 mb-3 text-lg">{{ $item['title'] }}</h3>
                <p class="text-sm text-gray-500 font-poppins leading-relaxed">
                    {{ $item['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SOLUTION SECTION --}}
<section class="relative min-h-screen w-full flex items-center justify-center py-24 px-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80" 
            class="w-full h-full object-cover"
            alt="Office background"
        >
        <div class="absolute inset-0 bg-white/90 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-10">
            <div>
                <span class="inline-block px-4 py-1 rounded-full border border-orange-200 bg-orange-50 text-orange-600 text-xs font-poppins font-semibold mb-6 uppercase tracking-wider">
                    Solusi ACMI
                </span>
                <h2 class="text-4xl md:text-5xl font-poppins font-semibold text-[#1a1a1a] leading-tight">
                    ACMI: <span class="font-serif italic text-orange-500 font-bold">Powerhouse</span> bagi<br>
                    Pemimpin Indonesia
                </h2>
                <p class="mt-8 text-gray-600 text-base md:text-lg font-poppins max-w-lg leading-relaxed">
                    Kami menyediakan ekosistem yang dirancang khusus untuk menjawab tantangan para pemimpin bisnis Indonesia dengan pendekatan yang holistik dan eksklusif.
                </p>
            </div>

            <div class="flex gap-12 pt-4 border-t border-gray-200">
                <div>
                    <p class="text-3xl md:text-4xl font-bold text-orange-500 font-poppins">10+</p>
                    <p class="text-sm text-gray-500 font-poppins mt-2 font-medium">Tahun Pengalaman</p>
                </div>
                <div>
                    <p class="text-3xl md:text-4xl font-bold text-orange-500 font-poppins">IDR 50T+</p>
                    <p class="text-sm text-gray-500 font-poppins mt-2 font-medium">Total Revenue Anggota</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $solutions = [
                    ['icon' => 'fa-share-nodes', 'title' => 'Networking Berkualitas Tinggi', 'desc' => 'Terhubung dengan sesama CEO & founders dari berbagai industri dalam forum privat dan eksklusif.'],
                    ['icon' => 'fa-book-open', 'title' => 'Knowledge Sharing Eksklusif', 'desc' => 'Roundtable discussion, masterclass dengan pakar global, dan insight industri terkurasi.'],
                    ['icon' => 'fa-bullhorn', 'title' => 'Amplifikasi Pengaruh', 'desc' => 'Advokasi kebijakan bersama dan platform untuk memperkuat brand leadership pribadi & perusahaan.'],
                    ['icon' => 'fa-briefcase', 'title' => 'Akses ke Peluang Bisnis', 'desc' => 'Deal flow, joint venture, dan rekomendasi mitra strategis melalui jaringan kepercayaan.']
                ];
            @endphp

            @foreach($solutions as $sol)
            <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center text-white mb-6 shadow-orange-200 shadow-lg">
                    <i class="fa-solid {{ $sol['icon'] }}"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 font-poppins mb-3">{{ $sol['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed font-poppins">
                    {{ $sol['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection