<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-tab-cms.png') }}?v={{ time() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .auth-container { width: 100%; max-width: 28rem; }
        
        /* Typography & Layout */
        .auth-title { font-size: 3rem; font-weight: 600; color: black; margin-bottom: 0.75rem; line-height: 1; }
        .auth-subtitle { color: black; font-size: 1.125rem; font-weight: 300; margin-bottom: 1.75rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.5rem; }
        
        /* Input Box Style */
        .input-custom { width: 100%; padding: 1rem 1.25rem; border: 1px solid #E5E7EB; border-radius: 1rem; outline: none; transition: all 0.3s; background-color: rgba(249, 250, 251, 0.5); }
        .input-custom:focus { box-shadow: 0 0 0 2px #0C1C87; border-color: transparent; }
        .input-error { border-color: #EF4444; }
        
        /* Password Visibility Button */
        .password-wrapper { position: relative; }
        .eye-btn { position: absolute; top: 0; bottom: 0; right: 1rem; display: flex; align-items: center; color: #9CA3AF; transition: color 0.3s; border: none; background: transparent; cursor: pointer; }
        .eye-btn:hover { color: #0C1C87; }
        
        /* Submit Button Style */
        .btn-custom { width: 100%; background-color: #0C1C87; color: white; font-weight: 700; padding: 1rem; border-radius: 1rem; transition: all 0.3s; box-shadow: 0 10px 15px -3px rgba(12, 28, 135, 0.2); border: none; cursor: pointer; margin-top: 1.5rem; }
        .btn-custom:hover { background-color: #091565; }
        .btn-custom:active { transform: scale(0.98); }
        
        /* Error & Footer Texts */
        .error-text { color: #EF4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 500; display: block; }
        .auth-footer { font-size: 0.875rem; text-align: center; color: #6B7280; margin-top: 1.5rem; }
        .auth-footer a { color: #0C1C87; font-weight: 700; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-[#DFEBFF] font-sans min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-[20px] shadow-sm flex max-w-6xl w-[95%] min-h-[750px] overflow-hidden scale-90">
    
        <div class="w-[45%] relative hidden md:block p-4">
            <div class="relative h-full w-full rounded-[20px] overflow-hidden">
                <img src="{{ asset('assets/element-signin.svg') }}" alt="background" class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-300">
                
                <div class="relative z-10 h-full flex flex-col justify-end p-8 bg-black/10">
                    <div class="max-w-sm font-poppins">
                        <p class="text-md font-light text-white mb-4">
                            Welcome Back, Admin
                        </p>
                        <h2 class="text-2xl md:text-4xl font-medium text-white leading-snug">
                            Kelola Ekosistem Digital ACMI dengan Presisi.
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8">
            <div class="auth-container">
                <div class="flex justify-start mb-6">
                    <img src="{{ asset('assets/logo2.svg') }}" class="w-13">
                </div>
                
                <h1 class="auth-title">@yield('heading')</h1>
                <p class="auth-subtitle">@yield('subheading')</p>

                @yield('content')
                
                <p class="auth-footer">
                    @yield('footer_text') 
                    <a href="@yield('footer_link')">@yield('footer_action')</a>
                </p>
            </div>
        </div>

    </div>

    @stack('scripts') 
</body>
</html>