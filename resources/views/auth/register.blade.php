<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ACMI - Sign In</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center p-6">

  <div class="bg-white p-2 rounded-32px shadow-2xl flex max-w-6xl w-full min-h-fit overflow-hidden scale-70">

    <div class="w-[41%] relative rounded-24px overflow-hidden">
      <img src="{{ asset('assets/bg.svg') }}" alt="background" class="absolute inset-0 w-full h-full h-calc(100%-1.5rem) object-cover object-center">
      
      <div class="relative z-10 text-custom-dark h-full flex flex-col justify-end p-12 bg-gradient-to-t from-white/30 to-transparent">
        <p class="text-lg font-medium mb-3 opacity-80">Lorem ipsum</p>
        <h2 class="text-3xl font-medium leading-tight">
          Lorem ipsum dolor <br> sit amet  nat janam <br> remsum
        </h2>
      </div>
    </div>

    <div class="w-[58%] p-4 flex flex-col justify-center items-center">
      
      <div class="w-full max-w-md space-y-8">
       

        <div class="mt-10 mb-5"> 
          <h1 class="text-5xl font-semibold text-black mb-3">Sign Up</h1>
          <p class="text-black text-lg font-light leading-relaxed">
            Welcome to sign in to your background <br> management system
          </p>
        </div>
        <form class="space-y-4 w-full"> <div>
          <label class="block text-sm font-bold text-black mb-1.5">Name</label>
          <input type="text" placeholder="Enter your name" 
                 class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
        </div>
      
        <div>
          <label class="block text-sm font-bold text-black mb-1.5">Email Address</label>
          <input type="email" placeholder="Enter your email" 
                 class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
        </div>
      
        <div>
          <label class="block text-sm font-bold text-black mb-1.5">Password</label>
          <div class="relative">
            <input type="password" id="pass1" placeholder="Enter your password" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
            <button type="button" onclick="togglePass('pass1', 'eye1_on', 'eye1_off')" class="absolute inset-y-0 right-5 flex items-center text-gray-400">
              </button>
          </div>
        </div>
      
        <div>
          <label class="block text-sm font-bold text-black mb-1.5">Confirm Password</label>
          <div class="relative">
            <input type="password" id="pass2" placeholder="Repeat your password" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-custom-blue outline-none transition-all">
            <button type="button" onclick="togglePass('pass2', 'eye2_on', 'eye2_off')" class="absolute inset-y-0 right-5 flex items-center text-gray-400">
              </button>
          </div>
        </div>
      
        <div class="pt-5">
          <button type="submit" class="w-full bg-custom-blue text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#091565] transition-all transform hover:-translate-y-1">
            Get Started
          </button>
        </div>
      </form>

        <p class="text-sm text-center text-black">
          Already have an account? <a href="/signin" class="font-bold hover:underline">Sign In</a>
        </p>
      </div>

    </div>
  </div>
</body>
</html>