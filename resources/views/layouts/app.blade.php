<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'ACMI - Asosiasi CEO Indonesia')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Source+Serif+Pro:ital,wght@1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-serif { font-family: 'Source Serif Pro', serif; }
        html { scroll-behavior: smooth; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#F8F9FB] font-poppins">

    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col px-10 pt-9 relative">
            
            <nav class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold text-black mt-4">@yield('page_title')</h1>
                    <p class="text-black text-base mt-1">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-6">
                    @yield('header_right')

                    <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 cursor-pointer hover:bg-gray-50 transition-all">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-black text-sm">{{ auth()->user()->name ?? 'Admin ACMI' }}</span>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="pb-10">
                @yield('content')
            </main>

            @yield('modal')

        </div>
    </div>

    @stack('scripts')
</body>
</html>