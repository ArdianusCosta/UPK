@extends('layouts.app')

@section('title', 'Data Alat - SIPINJAM')

@section('page-title', 'Data Alat')

@section('breadcrumb')
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
            <li class="inline-flex items-center">
                <a href="/dashboard" class="text-gray-700 hover:text-blue-600 text-sm">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-500 text-sm">Data Alat</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<!-- Header Actions -->
<div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-4 sm:p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-white">Manajemen Data Alat</h2>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Kelola inventory alat yang tersedia untuk dipinjam</p>
        </div>
        <div class="w-full sm:w-auto">
            <a href="/tools/create" class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 inline-flex items-center justify-center shadow-lg hover:shadow-red-500/25">
                <i class="fas fa-plus mr-2"></i>
                Tambah Alat
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 p-4 sm:p-6 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="sm:col-span-2 lg:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Cari Alat</label>
            <div class="relative">
                <input type="text" placeholder="Masukkan nama atau kode alat..." class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
            <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <option value="">Semua Kategori</option>
                <option value="alat-berat">Alat Berat</option>
                <option value="alat-listrik">Alat Listrik</option>
                <option value="perkakas">Perkakas</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <option value="">Semua Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="dipinjam">Dipinjam</option>
                <option value="rusak">Rusak</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>
</div>

<!-- Tools Table -->
<div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700 overflow-hidden">
    <!-- Mobile Card View -->
    <div class="block lg:hidden">
        <!-- Sample Data Cards -->
        <div class="p-4 border-b border-gray-700 last:border-b-0">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500 mr-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plug text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-white">TL-001</div>
                        <div class="text-xs text-gray-400">Bor Listrik</div>
                    </div>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/30 text-green-400 border border-green-700/50">
                    Tersedia
                </span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Merek:</span>
                    <span class="text-gray-300">Makita 650W</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Kategori:</span>
                    <span class="text-gray-300">Alat Listrik</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Kondisi:</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-gray-300">Baik</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2 mt-3 pt-3 border-t border-gray-700">
                <button class="flex-1 text-red-400 hover:text-red-300 py-1 text-sm" title="Edit">
                    <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button class="flex-1 text-green-400 hover:text-green-300 py-1 text-sm" title="Pinjam">
                    <i class="fas fa-hand-holding mr-1"></i> Pinjam
                </button>
                <button class="flex-1 text-gray-400 hover:text-gray-300 py-1 text-sm" title="Detail">
                    <i class="fas fa-eye mr-1"></i> Detail
                </button>
                <button class="flex-1 text-red-400 hover:text-red-300 py-1 text-sm" title="Hapus">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
        </div>

        <div class="p-4 border-b border-gray-700 last:border-b-0">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500 mr-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-weight text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-white">AB-002</div>
                        <div class="text-xs text-gray-400">Mesin Gerinda</div>
                    </div>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-900/30 text-orange-400 border border-orange-700/50">
                    Dipinjam
                </span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Merek:</span>
                    <span class="text-gray-300">Bosch GWS 750</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Kategori:</span>
                    <span class="text-gray-300">Alat Berat</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Kondisi:</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-gray-300">Baik</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2 mt-3 pt-3 border-t border-gray-700">
                <button class="flex-1 text-red-400 hover:text-red-300 py-1 text-sm" title="Edit">
                    <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button class="flex-1 text-red-400 hover:text-red-300 py-1 text-sm" title="Kembalikan">
                    <i class="fas fa-undo mr-1"></i> Kembali
                </button>
                <button class="flex-1 text-gray-400 hover:text-gray-300 py-1 text-sm" title="Detail">
                    <i class="fas fa-eye mr-1"></i> Detail
                </button>
                <button class="flex-1 text-red-400 hover:text-red-300 py-1 text-sm" title="Hapus">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-700/50 border-b border-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nama Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <!-- Sample Data -->
                <tr class="hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">TL-001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-plug text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">Bor Listrik</div>
                                <div class="text-sm text-gray-400">Makita 650W</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Alat Listrik</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/30 text-green-400 border border-green-700/50">
                            Tersedia
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-300">Baik</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="text-red-400 hover:text-red-300" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-green-400 hover:text-green-300" title="Pinjam">
                                <i class="fas fa-hand-holding"></i>
                            </button>
                            <button class="text-gray-400 hover:text-gray-300" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">AB-002</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-weight text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">Mesin Gerinda</div>
                                <div class="text-sm text-gray-400">Bosch GWS 750</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Alat Berat</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-900/30 text-orange-400 border border-orange-700/50">
                            Dipinjam
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-300">Baik</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="text-red-400 hover:text-red-300" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300" title="Kembalikan">
                                <i class="fas fa-undo"></i>
                            </button>
                            <button class="text-gray-400 hover:text-gray-300" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">PK-003</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hammer text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">Palu Godam</div>
                                <div class="text-sm text-gray-400">Stanley 2kg</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Perkakas</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/30 text-green-400 border border-green-700/50">
                            Tersedia
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-300">Cukup</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="text-red-400 hover:text-red-300" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-green-400 hover:text-green-300" title="Pinjam">
                                <i class="fas fa-hand-holding"></i>
                            </button>
                            <button class="text-gray-400 hover:text-gray-300" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">TL-004</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-fire text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">Mesin Las</div>
                                <div class="text-sm text-gray-400">Miller 200A</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Alat Listrik</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/30 text-red-400 border border-red-700/50">
                            Rusak
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-300">Rusak</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="text-red-400 hover:text-red-300" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-orange-400 hover:text-orange-300" title="Maintenance">
                                <i class="fas fa-tools"></i>
                            </button>
                            <button class="text-gray-400 hover:text-gray-300" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-700/50 px-4 sm:px-6 py-3 border-t border-gray-600">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs sm:text-sm text-gray-400 text-center sm:text-left">
                Menampilkan <span class="font-medium text-white">1</span> hingga <span class="font-medium text-white">4</span> dari <span class="font-medium text-white">156</span> hasil
            </div>
            <div class="flex items-center justify-center sm:justify-end space-x-1 sm:space-x-2">
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm border border-gray-600 rounded-md hover:bg-gray-700 disabled:opacity-50 text-gray-400" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm bg-red-600 text-white rounded-md">1</button>
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm border border-gray-600 rounded-md hover:bg-gray-700 text-gray-300">2</button>
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm border border-gray-600 rounded-md hover:bg-gray-700 text-gray-300">3</button>
                <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm text-gray-500">...</span>
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm border border-gray-600 rounded-md hover:bg-gray-700 text-gray-300">39</button>
                <button class="px-2 sm:px-3 py-1 text-xs sm:text-sm border border-gray-600 rounded-md hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
