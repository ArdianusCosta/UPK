<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>403 - Akses Ditolak | SIPINJAM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom 403 page styles */
        .error-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .error-bg::before {
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
        
        .floating-tools {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .tool {
            position: absolute;
            opacity: 0.1;
            animation: shake 10s infinite ease-in-out;
            color: #dc2626;
            font-size: 2rem;
        }
        
        .tool1 {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .tool2 {
            top: 25%;
            right: 15%;
            animation-delay: 2.5s;
        }
        
        .tool3 {
            bottom: 20%;
            left: 20%;
            animation-delay: 5s;
        }
        
        .tool4 {
            top: 60%;
            right: 25%;
            animation-delay: 7.5s;
        }
        
        .lock-icon {
            animation: pulse 2s infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px) rotate(-2deg); }
            20%, 40%, 60%, 80% { transform: translateX(5px) rotate(2deg); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .error-container {
            position: relative;
            z-index: 10;
        }
        
        .error-card {
            background: linear-gradient(to bottom right, #1f2937, #111827);
            border: 1px solid #374151;
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .error-code {
            background: linear-gradient(45deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
        }
        
        .btn-home {
            background: linear-gradient(45deg, #dc2626, #991b1b);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.5);
        }
        
        .btn-home::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-home:hover::before {
            left: 100%;
        }
        
        .btn-secondary {
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            border-color: #dc2626;
            background: rgba(239, 68, 68, 0.1);
        }
        
        @media (max-width: 768px) {
            .error-card {
                margin: 1rem;
                padding: 2rem 1.5rem !important;
            }
            
            .tool {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="error-bg">
    <!-- Floating Background Tools -->
    <div class="floating-tools">
        <div class="tool tool1">
            <i class="fas fa-lock"></i>
        </div>
        <div class="tool tool2">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="tool tool3">
            <i class="fas fa-user-slash"></i>
        </div>
        <div class="tool tool4">
            <i class="fas fa-ban"></i>
        </div>
    </div>

    <!-- Main Error Container -->
    <div class="error-container min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <!-- Error Card -->
            <div class="error-card rounded-2xl p-8 md:p-12 text-center">
                <!-- Error Icon & Code -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-600 to-red-800 rounded-full mb-4 lock-icon">
                        <i class="fas fa-lock text-white text-4xl"></i>
                    </div>
                    <h1 class="error-code text-6xl md:text-8xl font-black mb-4">403</h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent mx-auto"></div>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                        Akses Ditolak
                    </h2>
                    <p class="text-gray-400 text-base md:text-lg mb-2">
                        Kamu <span class="font-semibold text-red-400">tidak memiliki izin</span>
                        untuk mengakses halaman ini.
                    </p>
                    <p class="text-gray-500 text-sm md:text-base">
                        Halaman ini memerlukan otorisasi khusus. Silakan login dengan akun yang memiliki akses yang tepat.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="{{ url('/login') }}" class="btn-home px-8 py-3 rounded-lg text-white font-semibold shadow-lg flex items-center justify-center">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login dengan Akun Lain
                        </span>
                    </a>
                    
                    <a href="{{ url('/dashboard') }}" class="btn-secondary px-8 py-3 rounded-lg text-gray-300 font-semibold flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Permission Info -->
                <div class="bg-red-900/20 border border-red-800/50 rounded-lg p-4 mb-8 text-left">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-red-400 mr-3 mt-1"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-2">Informasi Akses</h3>
                            <ul class="space-y-1 text-xs text-gray-300">
                                <li>• Pastikan kamu login dengan akun yang benar</li>
                                <li>• Hubungi admin jika kamu merasa memiliki akses</li>
                                <li>• Periksa kembali izin akun kamu</li>
                                <li>• Jangan mencoba mengakses halaman tanpa izin</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-8 pt-8 border-t border-gray-700">
                    <p class="text-gray-500 text-sm mb-4">Berguna mungkin:</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300 hover:text-white text-sm transition-all duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <a href="{{ url('/tools') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300 hover:text-white text-sm transition-all duration-200">
                            <i class="fas fa-wrench mr-2"></i>
                            Data Alat
                        </a>
                        <a href="{{ url('/profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300 hover:text-white text-sm transition-all duration-200">
                            <i class="fas fa-user mr-2"></i>
                            Profil Saya
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-gray-600 text-sm">
                    Butuh bantuan? 
                    <a href="mailto:admin@sipinjam.com" class="text-red-400 hover:text-red-300 font-semibold transition-colors duration-200">
                        Hubungi Administrator
                    </a>
                </p>
                <div class="flex items-center justify-center space-x-6 mt-4">
                    <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-red-400 text-xs transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Beranda
                    </a>
                    <a href="#" class="text-gray-600 hover:text-red-400 text-xs transition-colors duration-200">
                        <i class="fas fa-question-circle mr-1"></i>
                        Bantuan
                    </a>
                    <a href="#" class="text-gray-600 hover:text-red-400 text-xs transition-colors duration-200">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Kebijakan Keamanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add shake animation to tools on mouse move
            document.addEventListener('mousemove', (e) => {
                const tools = document.querySelectorAll('.tool');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                tools.forEach((tool, index) => {
                    const speed = (index + 1) * 6;
                    const xOffset = (x - 0.5) * speed;
                    const yOffset = (y - 0.5) * speed;
                    
                    tool.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            });

            // Add click feedback to permission info
            const permissionInfo = document.querySelector('.bg-red-900\\/20');
            if (permissionInfo) {
                permissionInfo.addEventListener('click', function() {
                    this.style.animation = 'pulse 0.5s ease';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 500);
                });
            }

            // Track access attempts for security
            const accessAttempt = {
                url: window.location.href,
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                referrer: document.referrer
            };

            // Store in localStorage for debugging (in production, send to server)
            localStorage.setItem('lastAccessAttempt', JSON.stringify(accessAttempt));
        });
    </script>
</body>
</html>
