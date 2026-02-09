<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>500 - Server Error | SIPINJAM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom 500 page styles */
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
            animation: spin 20s infinite linear;
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
            animation-delay: 5s;
            animation-direction: reverse;
        }
        
        .tool3 {
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
        }
        
        .tool4 {
            top: 60%;
            right: 25%;
            animation-delay: 15s;
            animation-direction: reverse;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
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
            <i class="fas fa-cog"></i>
        </div>
        <div class="tool tool2">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="tool tool3">
            <i class="fas fa-server"></i>
        </div>
        <div class="tool tool4">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <!-- Main Error Container -->
    <div class="error-container min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <!-- Error Card -->
            <div class="error-card rounded-2xl p-8 md:p-12 text-center">
                <!-- Error Icon & Code -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-600 to-red-800 rounded-full mb-4 pulse">
                        <i class="fas fa-exclamation-triangle text-white text-4xl"></i>
                    </div>
                    <h1 class="error-code text-6xl md:text-8xl font-black mb-4">500</h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent mx-auto"></div>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                        Server Sedang Bermasalah
                    </h2>
                    <p class="text-gray-400 text-base md:text-lg mb-2">
                        Ups! Terjadi <span class="font-semibold text-red-400">kesalahan server</span>
                        yang tidak terduga.
                    </p>
                    <p class="text-gray-500 text-sm md:text-base">
                        Tim kami sudah diberitahu dan sedang bekerja untuk memperbaikinya.
                        Silakan coba lagi beberapa saat.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <button onclick="location.reload()" class="btn-home px-8 py-3 rounded-lg text-white font-semibold shadow-lg flex items-center justify-center">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh Halaman
                        </span>
                    </button>
                    
                    <a href="{{ url('/dashboard') }}" class="btn-secondary px-8 py-3 rounded-lg text-gray-300 font-semibold flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Error Details (Optional) -->
                <div class="bg-gray-800/50 rounded-lg p-4 mb-8 text-left">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-300">Informasi Error</h3>
                        <button onclick="this.parentElement.parentElement.classList.toggle('hidden')" class="text-gray-500 hover:text-gray-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-2 text-xs text-gray-400">
                        <p><strong>Waktu:</strong> {{ now()->format('d M Y, H:i:s') }}</p>
                        <p><strong>URL:</strong> {{ request()->fullUrl() }}</p>
                        <p><strong>IP Address:</strong> {{ request()->ip() }}</p>
                        <p><strong>User Agent:</strong> {{ request()->userAgent() }}</p>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-8 pt-8 border-t border-gray-700">
                    <p class="text-gray-500 text-sm mb-4">Apa yang bisa kamu lakukan:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                            <i class="fas fa-redo text-red-400 mr-3"></i>
                            <span class="text-gray-300 text-sm">Refresh halaman ini</span>
                        </div>
                        <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                            <i class="fas fa-clock text-red-400 mr-3"></i>
                            <span class="text-gray-300 text-sm">Coba lagi dalam 5 menit</span>
                        </div>
                        <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                            <i class="fas fa-check-circle text-red-400 mr-3"></i>
                            <span class="text-gray-300 text-sm">Periksa koneksi internet</span>
                        </div>
                        <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                            <i class="fas fa-headset text-red-400 mr-3"></i>
                            <span class="text-gray-300 text-sm">Hubungi support team</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-gray-600 text-sm">
                    Masih mengalami masalah? 
                    <a href="mailto:admin@sipinjam.com" class="text-red-400 hover:text-red-300 font-semibold transition-colors duration-200">
                        Laporkan Masalah
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
                    <a href="tel:+628123456789" class="text-gray-600 hover:text-red-400 text-xs transition-colors duration-200">
                        <i class="fas fa-phone mr-1"></i>
                        Hotline
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh after 30 seconds
            setTimeout(() => {
                const autoRefresh = confirm('Halaman akan di-refresh otomatis. Apakah Anda ingin melanjutkan?');
                if (autoRefresh) {
                    location.reload();
                }
            }, 30000);

            // Add spinning animation to tools on mouse move
            document.addEventListener('mousemove', (e) => {
                const tools = document.querySelectorAll('.tool');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                tools.forEach((tool, index) => {
                    const speed = (index + 1) * 5;
                    const xOffset = (x - 0.5) * speed;
                    const yOffset = (y - 0.5) * speed;
                    
                    tool.style.transform = `translate(${xOffset}px, ${yOffset}px) rotate(${Date.now() / 50}deg)`;
                });
            });

            // Copy error details to clipboard
            const errorDetails = document.querySelector('.bg-gray-800\\/50');
            if (errorDetails) {
                errorDetails.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'BUTTON') {
                        const text = this.innerText;
                        navigator.clipboard.writeText(text).then(() => {
                            // Show toast notification
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                            toast.textContent = 'Error details copied to clipboard!';
                            document.body.appendChild(toast);
                            
                            setTimeout(() => {
                                toast.remove();
                            }, 3000);
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
