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
            
            if (menu && icon) {
                menu.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            }
        }

        // dropdown
        window.addEventListener('click', function() {
            const menu = document.getElementById('dropdownMenu');
            const icon = document.getElementById('dropdownIcon');
        
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if(icon) icon.classList.remove('rotate-180');
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
            <nav class="mb-8 flex item-center gap-4 md:flex-row md:items-center md:justify-between">
                
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
                <div class="flex items-center gap-4 flex-nowrap">
                    @yield('header_right')
                    
                    <div class="relative shrink-0">
                        {{-- BUTTON --}}
                        <button onclick="toggleDropdown(event)" class="flex items-center gap-3">
                            
                            {{-- Avatar --}}
                            <div class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-300 bg-white text-xs font-medium text-gray-700">
                                {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                            </div>
            
                            {{-- Name --}}
                            <span class="hidden sm:block text-sm font-medium text-gray-800">
                                {{ auth()->user()->name ?? 'Admin ACMI' }}
                            </span>
            
                            {{-- ICON DARI ASSET --}}
                            <img src="{{ asset('assets/icons/down-icon.svg') }}" alt="Dropdown" id="dropdownIcon" class="h-4 w-4 transition-transform duration-200"/>
                        </button>
            
                        {{-- DROPDOWN --}}
                        <div id="dropdownMenu" class="absolute right-0 mt-3 w-44 bg-white border border-gray-200 rounded-xl shadow-lg hidden z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-xl transition">
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

    {{-- DELETE MODAL (REUSABLE) --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm">
        <div id="deleteBox" class="w-full max-w-md scale-95 rounded-2xl bg-white p-8 text-center opacity-0 shadow-xl transition-all duration-300">
            <h2 id="deleteModalTitle" class="mb-8 text-2xl font-semibold text-black">
                Are you sure want to delete this item?
            </h2>

            <div class="flex justify-center gap-6">
                <button onclick="closeDeleteModal()" class="font-semibold px-6 py-3 bg-red-200 rounded-lg">
                    Cancel
                </button>

                <button id="confirmDeleteBtn" class="font-semibold px-6 py-3 bg-green-200 rounded-lg">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openDeleteModal(url, title = 'Are you sure want to delete this item?') {
            const modal = document.getElementById('deleteModal');
            const box = document.getElementById('deleteBox');
            const form = document.getElementById('deleteForm');
            const titleText = document.getElementById('deleteModalTitle');
            const btn = document.getElementById('confirmDeleteBtn');

            form.action = url;
            titleText.innerText = title;

            btn.onclick = () => form.submit();

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const box = document.getElementById('deleteBox');

            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }
    </script>

</body>
</html>

