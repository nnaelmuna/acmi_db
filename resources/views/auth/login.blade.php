<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ACMI - Sign In</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center p-6"> 

  <div class="bg-white p-2 rounded-4xl shadow-2xl flex max-w-6xl w-full min-h-[640px] overflow-hidden scale-70">

    <div class="w-[41%] relative rounded-3xl overflow-hidden">
      <img src="{{ asset('assets/bg.svg') }}" alt="background" class="absolute inset-0 w-full h-full object-cover object-center">
      
      <div class="relative z-10 text-custom-dark h-full flex flex-col justify-end p-12 bg-linear-to-t from-white/30 to-transparent">
        <p class="text-lg font-medium mb-3 opacity-80">Lorem ipsum</p>
        <h2 class="text-3xl font-medium leading-tight">
          Lorem ipsum dolor <br> sit amet  nat janam <br> remsum
        </h2>
      </div>
    </div>

    <div class="w-[58%] p-12 flex flex-col justify-center items-center">
      
      <div class="w-full max-w-md space-y-8">
        <div class="flex justify-start">
          <img src="{{ asset('assets/logo2.svg') }}" alt="ACMI Logo" class="w-12 h-auto">
        </div>

        <div>
          <h1 class="text-5xl font-semibold text-black mb-3">Sign In</h1>
          <p class="text-black text-lg font-light">
            Welcome to sign in to your background <br> management system
          </p>
        </div>

        <form class="space-y-6">
          <div>
            <label class="block text-sm font-bold text-black mb-2">Your email</label>
            <input type="email" placeholder="Enter your email" 
                   class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
          </div>

          <div>
            <label class="block text-sm font-bold text-black mb-2">Password</label>
            <div class="relative">
              <input type="password" id="passwordInput" placeholder="Enter your password" 
                     class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
              
              <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-5 flex items-center text-gray-400 hover:text-custom-blue">
                <svg id="eyeIconOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg id="eyeIconClosed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.278-2.278L15.071 15.07M15.071 15.071a3 3 0 11-4.143-4.143l4.143 4.143z" />
                </svg>
              </button>
            </div>
          </div>

          <button type="submit" class="mt-10 w-full bg-custom-blue text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-900/20 hover:bg-[#091565] transition-all transform hover:-translate-y-1">
            Get Started
          </button>
        </form>

        <p class="text-sm text-center text-black">
          Don't have an account? <a href="/signup" class="font-bold hover:underline">Sign Up</a>
        </p>
      </div>

    </div>
  </div>
</body>
</html>