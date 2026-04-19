<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-[#DFEBFF] font-sans min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-[40px] shadow-sm flex max-w-6xl w-95% w-full min-h-750px overflow-hidden scale-90">
    
    <div class="w-[45%] relative hidden md:block p-4">
      <div class="relative h-full w-full rounded-32px overflow-hidden">
        <img src="{{ asset('assets/acmibg.svg') }}" alt="background" 
        class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-300">
        <div class="relative z-10 text-white h-full flex flex-col justify-end p-10 bg-black/10">
         
        </div>
      </div>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center p-8">
        @yield('content')
    </div>

  </div>

  @stack('scripts') 
</body>
</html>