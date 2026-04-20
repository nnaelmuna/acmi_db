<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'ACMI - Asosiasi CEO Indonesia')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Source+Serif+Pro:ital,wght@400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-serif { font-family: 'Source Serif Pro', serif; }
        body { background-color: #F8F9FB; }
    </style>
    
    <script>
function toggleDropdown(e) {
    e.stopPropagation();

    const menu = document.getElementById('dropdownMenu');
    const icon = document.getElementById('dropdownIcon');

    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// klik luar → nutup
window.addEventListener('click', function() {
    const menu = document.getElementById('dropdownMenu');
    const icon = document.getElementById('dropdownIcon');

    if (!menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
});
    </script>
</head>
<body class="font-poppins antialiased">

    <div class="flex min-h-screen"> 
        
        <aside class="sticky top-0 h-screen">
            @include('components.sidebar')
        </aside>

        <main class="flex-1 flex flex-col pr-12 pl-4 pt-12">
            
        <nav class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    {{-- LEFT: TITLE + DATE --}}
    <div>
        <h1 class="text-xl font-semibold text-acmi-darkblue">
            @yield('page_title')
        </h1>
        <p class="mt-1 text-sm font-medium text-gray-500">
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    {{-- RIGHT: PROFILE + DROPDOWN --}}
    <div class="flex items-center gap-4">

        @yield('header_right')

        <div class="relative">

            {{-- BUTTON --}}
            <button 
                onclick="toggleDropdown(event)"
                class="flex items-center gap-3">

                {{-- Avatar --}}
                <div class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-300 bg-white text-xs font-medium text-gray-700">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>

                {{-- Name --}}
                <span class="hidden sm:block text-sm font-medium text-gray-800">
                    {{ auth()->user()->name ?? 'Admin ACMI' }}
                </span>

                {{-- ICON DARI ASSET --}}
                <img 
                    src="{{ asset('assets/icons/down-icon.svg') }}"
                    alt="Dropdown"
                    id="dropdownIcon"
                    class="h-4 w-4 transition-transform duration-200"/>
            </button>

            {{-- DROPDOWN --}}
            <div id="dropdownMenu" 
                class="absolute right-0 mt-3 w-44 bg-white border border-gray-200 rounded-xl shadow-lg hidden z-50">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-xl transition">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>

</nav>

            <div class="w-full">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')
</body>
</html>