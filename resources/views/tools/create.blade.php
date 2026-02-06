@extends('layouts.app')

@section('title', 'Tambah Alat - SIPINJAM')

@section('page-title', 'Tambah Alat Baru')

@section('breadcrumb')
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/dashboard" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <a href="/tools" class="text-gray-700 hover:text-blue-600">Data Alat</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-500">Tambah Alat</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Form Card -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white">Informasi Alat</h3>
            <p class="text-sm text-gray-400 mt-1">Lengkapi data alat yang akan ditambahkan ke inventory</p>
        </div>

        <form method="POST" action="/tools" class="p-6">
            @csrf
            
            <!-- Kode Alat -->
            <div class="mb-6">
                <label for="code" class="block text-sm font-medium text-gray-300 mb-2">
                    Kode Alat <span class="text-red-400">*</span>
                </label>
                <input type="text" id="code" name="code" required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Contoh: TL-001">
                @error('code')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Alat -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                    Nama Alat <span class="text-red-400">*</span>
                </label>
                <input type="text" id="name" name="name" required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Contoh: Bor Listrik">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Merk dan Tipe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-300 mb-2">
                        Merk
                    </label>
                    <input type="text" id="brand" name="brand"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Contoh: Makita">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipe/Model
                    </label>
                    <input type="text" id="type" name="type"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Contoh: 650W">
                    @error('type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kategori -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-300 mb-2">
                    Kategori <span class="text-red-400">*</span>
                </label>
                <select id="category" name="category" required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Pilih Kategori</option>
                    <option value="alat-berat">Alat Berat</option>
                    <option value="alat-listrik">Alat Listrik</option>
                    <option value="perkakas">Perkakas</option>
                    <option value="alat-ukur">Alat Ukur</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Spesifikasi -->
            <div class="mb-6">
                <label for="specification" class="block text-sm font-medium text-gray-300 mb-2">
                    Spesifikasi
                </label>
                <textarea id="specification" name="specification" rows="3"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Masukkan spesifikasi teknis alat..."></textarea>
                @error('specification')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kondisi dan Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-300 mb-2">
                        Kondisi <span class="text-red-400">*</span>
                    </label>
                    <select id="condition" name="condition" required
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="cukup">Cukup</option>
                        <option value="rusak">Rusak</option>
                    </select>
                    @error('condition')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                        Status <span class="text-red-400">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Pilih Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="rusak">Rusak</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Lokasi Penyimpanan -->
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-300 mb-2">
                    Lokasi Penyimpanan
                </label>
                <input type="text" id="location" name="location"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Contoh: Gudang A, Rak 2">
                @error('location')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                    Keterangan Tambahan
                </label>
                <textarea id="notes" name="notes" rows="2"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Masukkan keterangan tambahan..."></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                <a href="/tools" 
                    class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-red-500/25">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Alat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
