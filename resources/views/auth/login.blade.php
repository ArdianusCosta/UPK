<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login - SIPINJAM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom login page styles */
        .login-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(239, 68, 68, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape1 {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #dc2626, #991b1b);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation-delay: 0s;
        }
        
        .shape2 {
            top: 70%;
            right: 10%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #dc2626, #991b1b);
            border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
            animation-delay: 5s;
        }
        
        .shape3 {
            bottom: 10%;
            left: 30%;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #dc2626, #991b1b);
            border-radius: 50%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: linear-gradient(to bottom right, #1f2937, #111827);
            border: 1px solid #374151;
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            transform: translateY(-25px) scale(0.85);
            color: #ef4444;
        }
        
        .input-group label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
            pointer-events: none;
            color: #9ca3af;
            background: linear-gradient(to bottom right, #1f2937, #111827);
            padding: 0 4px;
        }
        
        .remember-me {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #374151;
            border-radius: 4px;
            background: #1f2937;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .remember-me:checked {
            background: linear-gradient(45deg, #dc2626, #991b1b);
            border-color: #dc2626;
        }
        
        .remember-me:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .btn-login {
            background: linear-gradient(45deg, #dc2626, #991b1b);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.5);
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .social-btn {
            transition: all 0.3s ease;
            border: 1px solid #374151;
        }
        
        .social-btn:hover {
            transform: translateY(-2px);
            border-color: #dc2626;
            background: rgba(239, 68, 68, 0.1);
        }
        
        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
                padding: 2rem 1.5rem !important;
            }
        }
    </style>
</head>
<body class="login-bg">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
    </div>

    <!-- Main Login Container -->
    <div class="login-container min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-600 to-red-800 rounded-2xl shadow-2xl mb-4">
                    <i class="fas fa-tools text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">SIPINJAM</h1>
                <p class="text-gray-400">Sistem Peminjaman Alat</p>
            </div>

            <!-- Login Card -->
            <div class="login-card rounded-2xl p-8">
                <!-- Welcome Message -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-400 text-sm">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Error/Success Messages -->
                @if(session('error'))
                    <div class="mb-4 bg-gradient-to-r from-red-900/50 to-red-800/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2 text-red-400"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 bg-gradient-to-r from-green-900/50 to-green-800/30 border border-green-700/50 text-green-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2 text-green-400"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="input-group">
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            autocomplete="email"
                            autofocus
                            placeholder=" "
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-transparent focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                        >
                        <label for="email" class="text-sm">Email Address</label>
                    </div>

                    <!-- Password Field -->
                    <div class="input-group">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required
                            autocomplete="current-password"
                            placeholder=" "
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-transparent focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                        >
                        <label for="password" class="text-sm">Password</label>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="remember-me mr-2">
                            <span class="text-gray-300 text-sm">Ingat saya</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-red-400 hover:text-red-300 text-sm transition-colors duration-200">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gradient-to-r from-gray-800 to-gray-900 text-gray-400">Atau masuk dengan</span>
                    </div>
                </div>

                <!-- Social Login Options -->
                <div class="grid grid-cols-1 gap-3">
                    <button class="social-btn py-2 px-4 rounded-lg text-gray-300 hover:text-white flex items-center justify-center">
                        <i class="fab fa-google mr-2"></i>
                        Google
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-6 pt-6 border-t border-gray-700">
                    <p class="text-gray-400 text-sm">
                        Belum punya akun? 
                        <a href="#" class="text-red-400 hover:text-red-300 font-semibold transition-colors duration-200">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-gray-500 text-xs">
                    © 2024 SIPINJAM. All rights reserved.
                </p>
                <div class="flex items-center justify-center space-x-4 mt-2">
                    <a href="#" class="text-gray-500 hover:text-red-400 text-xs transition-colors duration-200">
                        Privacy Policy
                    </a>
                    <span class="text-gray-600">•</span>
                    <a href="#" class="text-gray-500 hover:text-red-400 text-xs transition-colors duration-200">
                        Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add floating animation to shapes on mouse move
            document.addEventListener('mousemove', (e) => {
                const shapes = document.querySelectorAll('.shape');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                shapes.forEach((shape, index) => {
                    const speed = (index + 1) * 10;
                    const xOffset = (x - 0.5) * speed;
                    const yOffset = (y - 0.5) * speed;
                    
                    shape.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            });

            // Form validation feedback
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-gray-600');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-gray-600');
                    }
                });
            });

            // Loading state for login button
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Sedang masuk...
                    </span>
                `;
                
                // Reset after 3 seconds (in case of slow response)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }, 3000);
            });
        });
    </script>
</body>
</html>