@extends('layouts.auth')
@section('title', 'Sign Up')

@section('content')
<div class="w-full max-w-md space-y-6">
    <div>
        <h1 class="text-5xl font-semibold text-black mb-3">Sign Up</h1>
        <p class="text-black text-lg font-light">
            Welcome to sign up to your background <br> management system
        </p>
    </div>

    <form class="space-y-4">
        <div>
            <label class="block text-sm font-bold text-black mb-1.5">Name</label>
            <input type="text" placeholder="Enter your name" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all">
        </div>
      
        <div>
            <label class="block text-sm font-bold text-black mb-1.5">Email Address</label>
            <input type="email" placeholder="Enter your email" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all">
        </div>
      
        <div>
          <label class="text-sm font-bold text-gray-700 block mb-2">Password</label>
          <div class="relative">
              <input type="password" id="passwordInput" placeholder="Enter your password"
                  class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#0C1C87] outline-none transition-all bg-gray-50/50">
              
              <button type="button" onclick="togglePassword()" 
                  class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#0C1C87] transition-colors">
                  <i id="eyeIcon" class="fa-solid fa-eye"></i>
              </button>
          </div>
      </div>
      
      <div>
        <label class="text-sm font-bold text-gray-700 block mb-2">Confirm Password</label>
        <div class="relative">
            <input type="password" id="passwordInput" placeholder="Enter your password"
                class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#0C1C87] outline-none transition-all bg-gray-50/50">
            
            <button type="button" onclick="togglePassword()" 
                class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#0C1C87] transition-colors">
                <i id="eyeIcon" class="fa-solid fa-eye"></i>
            </button>
        </div>
    </div>
      
        <div class="pt-5">
            <button type="submit" class="w-full bg-[#0C1C87] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#091565] transition-all transform hover:-translate-y-1">
                Get Started
            </button>
        </div>
    </form>

    <p class="text-sm text-center text-black">
        Already have an account? <a href="/signin" class="font-bold hover:underline">Sign In</a>
    </p>
</div>
@endsection

@push('scripts')
<script>
  function togglePassword() {
      const input = document.getElementById('passwordInput');
      const icon = document.getElementById('eyeIcon');
      
      if (input.type === 'password') {
          input.type = 'text';
         
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
      } else {
          input.type = 'password';
     
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
      }
  }
</script>
@endpush