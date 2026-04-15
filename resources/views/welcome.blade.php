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

{{-- PROGRAM UNGGULAN SECTION --}}
<section class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full border border-orange-100 bg-orange-50 text-orange-500 text-xs font-semibold mb-6 tracking-wide">
                Program Unggulan
            </span>
            <h2 class="text-4xl md:text-5xl font-poppins text-[#1a1a1a] leading-tight">
                <span class="font-semibold text-slate-800">Tingkatkan Kapasitas</span><br>
                <span class="font-serif font-bold italic text-orange-500">Kepemimpinan & Bisnis</span> <span class="font-semibold">Anda</span>
            </h2>
            <p class="mt-6 text-gray-500 text-base md:text-lg font-poppins max-w-2xl mx-auto leading-relaxed">
                Program-program berkualitas tinggi yang dirancang untuk memenuhi kebutuhan pengembangan para eksekutif puncak.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $programs = [
                    [
                        'icon' => 'fa-users-viewfinder',
                        'title' => 'CEO Roundtable',
                        'desc' => 'Sesi diskusi tertutup dan confidential tentang tantangan strategis dengan sesama CEO.',
                        'is_featured' => false
                    ],
                    [
                        'icon' => 'fa-graduation-cap',
                        'title' => 'Masterclass & Executive Briefing',
                        'desc' => 'Pembelajaran langsung dengan menteri, ekonom, dan thought leaders terkemuka.',
                        'is_featured' => false
                    ],
                    [
                        'icon' => 'fa-calendar-check',
                        'title' => 'ACMI Annual Summit & Gala Dinner',
                        'desc' => 'Acara puncak tahunan untuk networking dan perayaan prestasi anggota.',
                        'is_featured' => false
                    ],
                    [
                        'icon' => 'fa-plane-departure',
                        'title' => 'Business Mission & Delegasi',
                        'desc' => 'Kunjungan eksklusif ke korporasi dan ekosistem inovasi global.',
                        'is_featured' => true // Ini untuk memberikan border tipis seperti di gambar
                    ],
                    [
                        'icon' => 'fa-user-group',
                        'title' => 'Peer Advisory Group',
                        'desc' => 'Grup kecil pendampingan antar CEO untuk pertumbuhan berkelanjutan.',
                        'is_featured' => false
                    ]
                ];
            @endphp

            @foreach($programs as $program)
            <div class="group relative bg-[#f8f9fb] border {{ $program['is_featured'] ? 'border-orange-200' : 'border-transparent' }} p-10 rounded-[2rem] hover:bg-white hover:shadow-2xl hover:shadow-orange-100 transition-all duration-500">
                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid {{ $program['icon'] }} text-xl"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-900 font-poppins mb-4 group-hover:text-orange-500 transition-colors">
                    {{ $program['title'] }}
                </h3>
                <p class="text-gray-500 leading-relaxed font-poppins text-sm md:text-base">
                    {{ $program['desc'] }}
                </p>

                <div class="absolute bottom-8 right-8 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fa-solid fa-arrow-right-long text-orange-400"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIAL SECTION --}}
<section class="relative py-24 overflow-hidden">
    {{-- Background Image & Overlays --}}
    <div class="absolute inset-0 z-0">
        {{-- Foto Background - Menggunakan nuansa kantor yang clean --}}
        <img 
            src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&q=80" 
            class="w-full h-full object-cover"
            alt="Office Background"
        >
        {{-- White Overlay --}}
        <div class="absolute inset-0 bg-white/80 backdrop-blur-[2px]"></div>
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-white"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        {{-- Header Section --}}
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-orange-100 bg-white/80 text-orange-500 text-xs font-semibold mb-6 tracking-wide shadow-sm">
                <i class="fa-solid fa-star text-[10px]"></i>
                <span>Testimoni Anggota</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-poppins leading-tight text-slate-900">
                <span class="font-semibold">Dipimpin oleh Para</span> 
                <span class="font-serif font-bold italic text-orange-500">Pemimpin</span><br>
                <span class="font-serif font-bold italic text-orange-500">Terbaik</span>
            </h2>
        </div>

        {{-- Testimonial Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            @php
                $testimonials = [
                    [
                        'name' => 'Budi Santoso',
                        'role' => 'CEO, PT Maju Bersama Indonesia',
                        'initial' => 'B',
                        'bg_initial' => 'bg-orange-500',
                        'quote' => '"ACMI bukan sekadar asosiasi, tapi ekosistem pendukung terkuat. Jaringannya memberikan nilai yang tak terukur, dari strategi bisnis hingga pengembangan diri sebagai pemimpin."'
                    ],
                    [
                        'name' => 'Sarah Wijaya',
                        'role' => 'Founder & CEO, TechVenture ID',
                        'initial' => 'S',
                        'bg_initial' => 'bg-orange-400',
                        'quote' => '"Bergabung dengan ACMI membuka akses ke jaringan CEO terbaik Indonesia. Setiap pertemuan selalu menghasilkan insight berharga untuk bisnis saya."'
                    ],
                    [
                        'name' => 'Herman Tanaka',
                        'role' => 'Direktur Utama, Global Logistics Corp',
                        'initial' => 'H',
                        'bg_initial' => 'bg-orange-500',
                        'quote' => '"Program peer advisory ACMI membantu saya mengambil keputusan strategis dengan lebih percaya diri. Forum diskusi yang confidential."'
                    ]
                ];
            @endphp

            @foreach($testimonials as $testi)
                <div class="group bg-white/70 backdrop-blur-md border border-white p-10 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(249,115,22,0.1)] transition-all duration-500 flex flex-col justify-between border-opacity-50">
                    
                    <div>
                        {{-- Quote Icon ala Gambar --}}
                        <div class="mb-6">
                            <i class="fa-solid fa-quote-left text-4xl text-orange-200 group-hover:text-orange-400 tracking-wider transition-colors duration-500"></i>
                        </div>
                        
                        <p class="text-slate-600 font-poppins leading-relaxed text-sm md:text-base italic mb-10">
                            {{ $testi['quote'] }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4 pt-6">
                        <div class="w-12 h-12 {{ $testi['bg_initial'] }} text-white rounded-full flex items-center justify-center font-bold font-poppins shadow-lg group-hover:scale-110 transition-transform duration-500">
                            {{ $testi['initial'] }}
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-bold font-poppins text-[13px] tracking-tight">{{ $testi['name'] }}</h4>
                            <p class="text-slate-400 font-poppins text-[11px] leading-tight">{{ $testi['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap justify-center items-center gap-x-8 gap-y-6">
            @foreach(['Bank Central Asia', 'Telkom Indonesia', 'Astra International', 'GoTo Group', 'Tokopedia', 'Bank Mandiri'] as $brand)
                <div class="px-5 py-2 rounded-xl bg-white/40 backdrop-blur-sm border border-white/50 shadow-sm">
                    <span class="text-[10px] md:text-xs font-medium font-poppins text-slate-500 tracking-normal">
                        {{ $brand }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- EXCLUSIVE MEMBERSHIP SECTION --}}
<section class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        
        {{-- Left Side: Text & Features --}}
        <div class="space-y-8">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-orange-100 bg-white text-orange-400 text-xs font-medium mb-6 shadow-sm">
                    <i class="fa-regular fa-shield-check"></i>
                    <span>Keanggotaan Eksklusif</span>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-poppins text-slate-900 leading-tight">
                    <span class="font-semibold">Menjadi Bagian dari</span><br>
                    <span class="font-serif font-bold italic text-orange-500">Komunitas Eksklusif</span>
                </h2>
                
                <p class="mt-6 text-gray-500 text-base md:text-lg font-poppins leading-relaxed max-w-xl">
                    Keanggotaan ACMI bersifat undangan dan melalui proses kurasi untuk memastikan kualitas dan keselarasan nilai antar anggota. Kami mencari pemimpin yang berkomitmen untuk saling berbagi dan bertumbuh bersama.
                </p>
            </div>

            {{-- Checklist Features --}}
            <ul class="space-y-4">
                @php
                    $features = [
                        'Akses penuh ke seluruh program ACMI',
                        'Undangan ke CEO Roundtable bulanan',
                        'Masterclass eksklusif dengan pakar global',
                        'Networking dengan 500+ CEO Indonesia',
                        'Akses ke resource center dan insight industri',
                        'Prioritas untuk business mission internasional'
                    ];
                @endphp

                @foreach($features as $feature)
                <li class="flex items-center gap-3 text-slate-700 font-poppins text-sm md:text-base">
                    <div class="flex-shrink-0 w-6 h-6 border-2 border-orange-400 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-check text-[10px] text-orange-500"></i>
                    </div>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Right Side: Application Card --}}
        <div class="relative flex justify-center lg:justify-end">
            <div class="bg-[#f8f9fb] border border-gray-100 p-10 md:p-14 rounded-[3rem] w-full max-w-md text-center shadow-[0_20px_50px_rgba(0,0,0,0.03)]">
                <div class="w-16 h-16 bg-orange-400/10 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fa-solid fa-shield-halved text-2xl text-orange-400"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-slate-900 font-poppins mb-4">Ajukan Profil Anda</h3>
                <p class="text-gray-400 text-sm font-poppins leading-relaxed mb-10">
                    Proses kurasi memastikan setiap anggota mendapat nilai maksimal
                </p>

                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-200 transition-all duration-300 flex items-center justify-center gap-2 group">
                    Ajukan Profil Untuk Dipertimbangkan
                    <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                </button>

                <p class="mt-8 text-sm text-gray-400 font-poppins">
                    Tertarik menjadi mitra institusional? <a href="#" class="text-orange-400 font-semibold hover:underline">Hubungi Kami</a>
                </p>
            </div>
        </div>

    </div>
</section>

<p>p p apah</p>

@endsection