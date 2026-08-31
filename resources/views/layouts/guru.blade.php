<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - Panel Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f8f9fa] text-gray-800 flex h-screen overflow-hidden">

    <!-- Sidebar Guru -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col shadow-sm z-10">
        <div class="h-20 flex items-center px-6 border-b border-gray-50">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-sm">
                <!-- Icon Edukasi/Logo -->
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 tracking-tight leading-tight">SIAKAD</h2>
                <p class="text-xs text-emerald-600 font-semibold">Portal Guru</p>
            </div>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
            
            <a href="{{ route('guru.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.dashboard*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-colors relative group">
                @if(request()->routeIs('guru.dashboard*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-600 rounded-r-md"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('guru.dashboard*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Jadwal Mengajar
            </a>

            <a href="{{ route('guru.attendance.camera') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.attendance.camera*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-colors relative group">
                @if(request()->routeIs('guru.attendance.camera*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-600 rounded-r-md"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('guru.attendance.camera*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Absensi Guru (Kamera)
            </a>
        </nav>
        
        <!-- Info Akun Singkat & Tombol Logout di Sidebar Bawah -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center mb-3 px-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=059669&color=fff" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">NIP: {{ auth()->user()->username }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-100">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout / Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Header / Topbar -->
        <header class="h-20 bg-white px-8 flex justify-between items-center z-0 border-b border-gray-100 shadow-sm">
            <div class="flex items-center text-gray-800">
                <svg class="w-6 h-6 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-gray-900">@yield('header', 'Dashboard Guru')</h1>
                    <p class="text-xs text-gray-500">Sistem Absensi & Jadwal Mengajar Terpadu</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- User Profile Dropdown / Badge -->
                <div class="flex items-center border-l border-gray-100 pl-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=059669&color=fff" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                    <div class="ml-3 hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-emerald-600 font-medium">Guru Pengajar</p>
                    </div>
                </div>

                <!-- Tombol Logout Topbar -->
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline-block">
                    @csrf
                    <button type="submit" title="Keluar dari sesi" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>
        
        <!-- Scrollable Page Content -->
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Alert Feedback -->
                @if(session('success'))
                    <div class="flex items-center p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

</body>
</html>
