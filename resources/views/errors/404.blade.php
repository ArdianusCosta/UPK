<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-10 w-32 h-32 bg-blue-200 rounded-full opacity-40 blur-2xl"></div>
        <div class="absolute bottom-20 right-16 w-40 h-40 bg-indigo-300 rounded-full opacity-30 blur-3xl"></div>
        <div class="absolute top-1/4 right-1/4 text-slate-300 text-6xl rotate-12">🛠️</div>
        <div class="absolute bottom-1/3 left-1/4 text-slate-300 text-7xl -rotate-12">💻</div>
        <div class="absolute top-2/3 right-1/3 text-slate-300 text-6xl rotate-6">📦</div>
    </div>
    <div class="relative z-10 bg-white/80 backdrop-blur-md shadow-xl rounded-2xl p-10 max-w-md w-full text-center">
        <h1 class="text-8xl font-extrabold text-red-500">404</h1>
        <h2 class="mt-4 text-xl font-semibold text-slate-800">
            Halaman Tidak Ditemukan
        </h2>
        <p class="mt-3 text-sm text-slate-600">
            Sepertinya alat atau halaman yang kamu cari
            <span class="font-medium">tidak tersedia</span>
            atau sudah dipindahkan.
        </p>
        <p class="mt-1 text-sm text-slate-600">
            Silakan kembali ke halaman utama aplikasi peminjaman alat.
        </p>
        <a href="/"
           class="inline-flex items-center justify-center mt-6 px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
            ⬅️ Kembali ke Beranda
        </a>
    </div>
</body>
</html>
