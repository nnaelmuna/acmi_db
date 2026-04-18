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
</head>
<body class="font-poppins antialiased">

    <div class="flex min-h-screen"> 
        
        <aside class="sticky top-0 h-screen">
            @include('components.sidebar')
        </aside>

        <main class="flex-1 flex flex-col pr-12 pl-4 pt-12">
            
            <nav class="flex justify-between items-start mb-10">
                <div>
                    <h1 class="text-2xl font-bold text-black">@yield('page_title')</h1>
                    <p class="text-black text-lg mt-2 font-medium">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-6">
                    @yield('header_right')

                    <div class="flex items-center gap-4 bg-white px-5 py-2 rounded-full shadow-sm border border-gray-100 cursor-pointer hover:bg-gray-50 transition-all">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700">
                            AD
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-black text-base">Admin ACMI</span>
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