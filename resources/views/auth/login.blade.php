@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="w-full max-w-md space-y-7">
    
    <div class="flex justify-center mb-6">
        <img src="{{ asset('assets/logo2.svg') }}" class="w-16">
    </div>

    <div class="text-center">
        <h1 class="text-4xl font-extrabold text-black">Sign In</h1>
        <p class="text-gray-500 text-sm mt-3">
            Welcome back! Please enter your details
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
                    class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                    <i class="fa-regular fa-eye"></i>
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
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush