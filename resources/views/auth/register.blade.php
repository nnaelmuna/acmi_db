@extends('layouts.auth')
@section('title', 'Sign Up')

@section('content')
<div class="w-full max-w-md space-y-6">
    <div>
        <h1 class="text-5xl font-semibold text-black mb-3">Sign Up</h1>
        <p class="text-black text-lg font-light">
            Welcome to sign in to your background <br> management system
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
            <label class="block text-sm font-bold text-black mb-1.5">Password</label>
            <input type="password" placeholder="Enter your password" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all">
        </div>
      
        <div>
            <label class="block text-sm font-bold text-black mb-1.5">Confirm Password</label>
            <input type="password" placeholder="Repeat your password" 
                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all">
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