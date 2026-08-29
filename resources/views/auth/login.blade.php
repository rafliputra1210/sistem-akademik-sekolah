<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIAKAD - Keamanan Akses</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex">

    <!-- Sisi Kiri: Branding & Informasi -->
    <div class="hidden lg:flex lg:w-1/2 bg-blue-700 text-white flex-col justify-center items-start p-16 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full text-white" fill="currentColor">
                <polygon points="0,100 100,0 100,100"/>
            </svg>
        </div>
        
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white text-blue-700 flex items-center justify-center rounded-lg font-bold text-2xl mb-8 shadow-lg">
                S
            </div>
            <h1 class="text-4xl font-bold mb-4">Sistem Informasi Akademik</h1>
            <p class="text-blue-100 text-lg leading-relaxed max-w-md">
                Akses portal terpusat untuk manajemen data sekolah, jadwal mengajar, dan absensi kelas harian[cite: 1].
            </p>
        </div>
        
        <div class="relative z-10 mt-auto">
            <p class="text-sm text-blue-200">Keamanan Enkripsi Aktif &bull; Akses Terbatas</p>
        </div>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl lg:shadow-none lg:bg-transparent lg:p-0">
            
            <div class="text-center lg:text-left mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                <p class="text-gray-500 text-sm">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            <!-- Form Otentikasi -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                <!-- Token Keamanan Wajib (Mencegah Serangan CSRF) -->
                @csrf

                <!-- Pesan Error Global -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded text-sm font-medium">
                        Kredensial yang Anda berikan tidak cocok dengan data kami.
                    </div>
                @endif

                <!-- Input Username / NIP -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username / NIP <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition shadow-sm"
                            placeholder="Masukkan NIP atau Username">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition shadow-sm"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                        Otorisasi Masuk
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} SIAKAD. Akses sistem tercatat secara otomatis.
            </div>
        </div>
    </div>
</body>
</html>