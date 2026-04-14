<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'ACMI - Asosiasi CEO Indonesia')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Source+Serif+Pro:ital,wght@1,400;1,600&display=swap" rel="stylesheet">

    <style>
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-serif { font-family: 'Source Serif Pro', serif; }
       
        
        html { scroll-behavior: smooth; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white">

    <x-navbar />

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>