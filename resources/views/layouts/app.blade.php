<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard Peminjaman Alat')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-900">
    <div id="app" class="min-h-screen flex">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed lg:relative w-full lg:w-64 bg-gradient-to-b from-gray-900 to-black shadow-2xl flex-shrink-0 border-r lg:border-r border-red-900/30 transition-all duration-300 ease-in-out z-50 lg:z-40 transform -translate-y-full lg:translate-y-0 lg:translate-x-0 h-screen lg:h-auto overflow-y-auto top-0 left-0 right-0">
            <div class="h-full px-4 lg:px-3 py-4 lg:py-4 overflow-y-auto flex flex-col max-w-6xl mx-auto lg:max-w-none">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-xl flex items-center justify-center mb-3 mx-auto shadow-lg transition-all duration-300">
                            <i class="fas fa-tools text-white text-2xl transition-colors duration-300"></i>
                        </div>
                        <h1 class="text-2xl lg:text-xl font-bold text-white">SIPINJAM</h1>
                        <p class="text-sm lg:text-xs text-red-400 mt-1 transition-colors duration-300">Sistem Peminjaman Alat</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <ul class="space-y-2 font-medium flex-grow max-w-2xl mx-auto lg:max-w-none w-full">
                    <li>
                        <a href="/dashboard" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group {{ request()->is('dashboard') ? 'bg-red-900/40 text-red-400 border-l-4 border-red-500' : '' }}">
                            <i class="fas fa-home w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/tools" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group {{ request()->is('tools*') ? 'bg-red-900/40 text-red-400 border-l-4 border-red-500' : '' }}">
                            <i class="fas fa-wrench w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Data Alat</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group">
                            <i class="fas fa-hand-holding w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Peminjaman</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group">
                            <i class="fas fa-undo w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Pengembalian</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group">
                            <i class="fas fa-users w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Data Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 lg:p-3 text-gray-300 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group">
                            <i class="fas fa-chart-bar w-6 lg:w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="ml-4 lg:ml-3 text-base lg:text-sm">Laporan</span>
                        </a>
                    </li>
                </ul>

                <!-- User Profile -->
                <div class="mt-auto max-w-md mx-auto lg:max-w-none w-full">
                    <div class="bg-gradient-to-r from-red-900/30 to-red-800/20 rounded-lg p-4 lg:p-3 border border-red-800/30 transition-colors duration-300">
                        <div class="flex items-center">
                            <div class="w-12 lg:w-10 h-12 lg:h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center transition-colors duration-300">
                                <i class="fas fa-user text-white text-base lg:text-sm transition-colors duration-300"></i>
                            </div>
                            <div class="ml-4 lg:ml-3 flex-1">
                                <p class="text-white text-base lg:text-sm font-medium transition-colors duration-300">Admin User</p>
                                <p class="text-red-400 text-sm lg:text-xs transition-colors duration-300">Administrator</p>
                            </div>
                        </div>
                        <form action="/logout" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-base lg:text-sm py-3 lg:py-2 px-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-red-500/25">
                                <i class="fas fa-sign-out-alt mr-2 lg:mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <nav class="bg-gray-800 shadow-xl border-b border-red-900/30 transition-colors duration-300">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <!-- Mobile Menu Toggle -->
                            <button id="menuToggle" class="p-2 rounded-lg hover:bg-gray-700 text-gray-300 transition-colors duration-200 md:hidden">
                                <i class="fas fa-bars"></i>
                            </button>
                            
                            <!-- Page Title -->
                            <h2 class="ml-4 text-xl font-bold text-white transition-colors duration-300">
                                @yield('page-title', 'Dashboard')
                            </h2>
                        </div>

                        <!-- Right Side Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Search -->
                            <div class="hidden md:block">
                                <div class="relative">
                                    <input type="text" placeholder="Cari..." class="w-64 pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <button class="relative p-2 rounded-lg hover:bg-gray-700 text-gray-300 transition-colors duration-200">
                                <i class="fas fa-bell"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            </button>

                            <!-- User Menu (Desktop) -->
                            <div class="hidden lg:block">
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-white transition-colors duration-300">Admin User</p>
                                        <p class="text-xs text-gray-400 transition-colors duration-300">Administrator</p>
                                    </div>
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center transition-colors duration-300">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 bg-gray-900 transition-colors duration-300">
                <!-- Breadcrumb -->
                @yield('breadcrumb')
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-gradient-to-r from-green-900/50 to-green-800/30 border border-green-700/50 text-green-300 px-4 py-3 rounded-lg transition-colors duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2 text-green-400"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-gradient-to-r from-red-900/50 to-red-800/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-lg transition-colors duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2 text-red-400"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-gradient-to-r from-red-900/50 to-red-800/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-lg transition-colors duration-300">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2 text-red-400"></i>
                            <strong>Terjadi kesalahan:</strong>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300"></div>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Debug: Check if elements exist
            console.log('menuToggle:', menuToggle);
            console.log('sidebar:', sidebar);
            console.log('sidebarOverlay:', sidebarOverlay);

            if (menuToggle && sidebar && sidebarOverlay) {
                menuToggle.addEventListener('click', () => {
                    console.log('Menu toggle clicked');
                    sidebar.classList.toggle('-translate-y-full');
                    sidebar.classList.toggle('translate-y-0');
                    sidebarOverlay.classList.toggle('hidden');
                    
                    // Prevent body scroll when sidebar is open
                    if (!sidebar.classList.contains('-translate-y-full')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', () => {
                    console.log('Overlay clicked');
                    sidebar.classList.add('-translate-y-full');
                    sidebar.classList.remove('translate-y-0');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            }

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-y-full')) {
                    sidebar.classList.add('-translate-y-full');
                    sidebar.classList.remove('translate-y-0');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });

            // X button functionality
            const closeButton = document.createElement('div');
            closeButton.id = 'closeSidebar';
            closeButton.innerHTML = '✕';
            closeButton.style.cssText = `
                position: absolute;
                top: 1rem;
                right: 1rem;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                z-index: 60;
                padding: 0.5rem;
                border-radius: 0.5rem;
                background: rgba(239, 68, 68, 0.2);
                transition: background 0.2s;
                display: none;
            `;
            
            // Add X button to sidebar
            if (sidebar) {
                sidebar.appendChild(closeButton);
                
                // Show/hide X button based on screen size
                function updateCloseButtonVisibility() {
                    if (window.innerWidth <= 1024) {
                        closeButton.style.display = 'block';
                    } else {
                        closeButton.style.display = 'none';
                    }
                }
                
                // Initial check
                updateCloseButtonVisibility();
                
                // Update on resize
                window.addEventListener('resize', updateCloseButtonVisibility);
                
                // X button click handler
                closeButton.addEventListener('click', () => {
                    console.log('X button clicked');
                    sidebar.classList.add('-translate-y-full');
                    sidebar.classList.remove('translate-y-0');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                });
                
                // Hover effects
                closeButton.addEventListener('mouseenter', () => {
                    closeButton.style.background = 'rgba(239, 68, 68, 0.3)';
                });
                
                closeButton.addEventListener('mouseleave', () => {
                    closeButton.style.background = 'rgba(239, 68, 68, 0.2)';
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
