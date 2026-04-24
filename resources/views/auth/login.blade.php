@extends('layouts.auth')

@section('title', 'Sign In')
@section('heading', 'Sign In')
@section('subheading')
    Welcome to sign in to your background <br> management system.
@endsection

@section('footer_text', 'Don’t have an account?')
{{-- @section('footer_link', route('register')) --}}
@section('footer_action', 'Sign Up')

@section('content')
<form method="POST" action="{{ route('login') }}" novalidate>
    @csrf

    <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            placeholder="Enter your email"
            class="input-custom @error('email') input-error @enderror"
        >

        @error('email')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="password-wrapper">
            <input
                type="password"
                name="password"
                id="passwordInput"
                required
                placeholder="Enter your password"
                class="input-custom @error('password') input-error @enderror"
            >

            <button type="button" onclick="togglePassword()" class="eye-btn" aria-label="Toggle password visibility">
                <i id="eyeIcon" class="fa-solid fa-eye"></i>
            </button>
        </div>

        @error('password')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn-custom">Sign In</button>
</form>
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