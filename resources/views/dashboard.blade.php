@extends('layouts.app')

@section('title', 'Dashboard - SIPINJAM')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/dashboard" class="text-gray-400 hover:text-red-300 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6 hover:shadow-red-500/10 hover:border-red-800/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Total Alat</p>
                <p class="text-3xl font-bold text-white mt-1">156</p>
                <p class="text-xs text-green-400 mt-2 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +12 dari bulan lalu
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-wrench text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6 hover:shadow-red-500/10 hover:border-red-800/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Sedang Dipinjam</p>
                <p class="text-3xl font-bold text-white mt-1">42</p>
                <p class="text-xs text-orange-400 mt-2 flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    8 akan jatuh tempo
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-hand-holding text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6 hover:shadow-red-500/10 hover:border-red-800/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Tersedia</p>
                <p class="text-3xl font-bold text-white mt-1">114</p>
                <p class="text-xs text-gray-400 mt-2 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i>
                    73% tersedia
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-check text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6 hover:shadow-red-500/10 hover:border-red-800/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Total Peminjam</p>
                <p class="text-3xl font-bold text-white mt-1">89</p>
                <p class="text-xs text-red-400 mt-2 flex items-center">
                    <i class="fas fa-users mr-1"></i>
                    23 aktif hari ini
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Activities -->
    <div class="lg:col-span-2 bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Activity Item -->
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-700/50 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-undo text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">Pengembalian Alat</p>
                        <p class="text-xs text-gray-400">Budi Santoso mengembalikan <span class="font-medium text-red-400">Bor Listrik</span></p>
                        <p class="text-xs text-gray-500 mt-1">2 menit yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 hover:bg-gray-700/50 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hand-holding text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">Peminjaman Baru</p>
                        <p class="text-xs text-gray-400">Ahmad Fadli meminjam <span class="font-medium text-red-400">Mesin Gerinda</span></p>
                        <p class="text-xs text-gray-500 mt-1">15 menit yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 hover:bg-gray-700/50 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">Pengingat Pengembalian</p>
                        <p class="text-xs text-gray-400"><span class="font-medium text-red-400">3 alat</span> akan jatuh tempo besok</p>
                        <p class="text-xs text-gray-500 mt-1">1 jam yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 hover:bg-gray-700/50 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">Alat Baru Ditambahkan</p>
                        <p class="text-xs text-gray-400"><span class="font-medium text-red-400">5 alat</span> baru ditambahkan ke inventory</p>
                        <p class="text-xs text-gray-500 mt-1">3 jam yang lalu</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="#" class="text-sm text-red-400 hover:text-red-300 font-medium transition-colors">
                    Lihat Semua Aktivitas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="/tools/create" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-red-500/25">
                    <i class="fas fa-plus mr-2"></i>
                    Pinjam Alat
                </a>
                <a href="#" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-green-500/25">
                    <i class="fas fa-undo mr-2"></i>
                    Kembalikan Alat
                </a>
                <a href="/tools/create" class="w-full border border-gray-600 hover:bg-gray-700 text-gray-300 font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Alat
                </a>
            </div>
        </div>

        <!-- Overdue Items -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">Terlambat Kembali</h3>
            <div class="space-y-3">
                <div class="p-3 bg-red-900/30 border border-red-800/50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">Bor Listrik</p>
                            <p class="text-xs text-gray-400">Andi Pratama</p>
                        </div>
                        <span class="text-xs text-red-400 font-medium">3 hari</span>
                    </div>
                </div>
                <div class="p-3 bg-red-900/30 border border-red-800/50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">Mesin Las</p>
                            <p class="text-xs text-gray-400">Rudi Hermawan</p>
                        </div>
                        <span class="text-xs text-red-400 font-medium">1 hari</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-sm text-red-400 hover:text-red-300 font-medium transition-colors">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
