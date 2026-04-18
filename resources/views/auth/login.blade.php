@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="w-full max-w-md space-y-7">
    
    <div class="flex justify-start mb-6">
        <img src="{{ asset('assets/logo2.svg') }}" class="w-13">
    </div>

    <div>
      <h1 class="text-5xl font-semibold text-black mb-3">Sign In</h1>
      <p class="text-black text-lg font-light">
          Welcome to sign in to your background <br> management system
      </p>
  </div>

    <form class="space-y-6">
        <div>
            <label class="text-sm font-bold text-gray-700 block mb-2">Email Address</label>
            <input type="email" placeholder="Enter your email"
                class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#0C1C87] outline-none transition-all bg-gray-50/50">
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

        <button type="submit"
            class="w-full bg-[#0C1C87] text-white font-bold py-4 rounded-2xl hover:bg-[#091565] transition-all shadow-lg shadow-blue-900/20 transform active:scale-[0.98]">
            Sign In
        </button>
    </form>

    <p class="text-sm text-center text-gray-500 pt-4">
        Don’t have an account? 
        <a href="/signup" class="text-[#0C1C87] font-bold hover:underline">
            Sign Up
        </a>
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