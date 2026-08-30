@extends('layouts.guru')

@section('header', 'Jadwal Mengajar')

@section('content')
<div class="space-y-6">

    <!-- Hero Card Profil Singkat Guru -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2">
                {{ $today }}, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </span>
            <h2 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-emerald-100 text-sm mt-1">
                Selamat bertugas! Pantau jadwal mata pelajaran Anda dan isi absensi siswa harian di bawah ini.
            </p>
        </div>
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 flex items-center gap-4 border border-white/10">
            <div class="text-right">
                <p class="text-xs text-emerald-200">Total Kelas Hari Ini</p>
                <p class="text-2xl font-bold">{{ $schedules->count() }} Kelas</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Mengajar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Daftar Jadwal Mengajar Hari Ini</h3>
                <p class="text-xs text-gray-500">Klik tombol isi absensi untuk menginput kehadiran siswa di kelas.</p>
            </div>
            <span class="text-xs font-medium px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg">
                Hari: <strong class="text-emerald-700">{{ $today }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Waktu / Jam</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($schedules as $jadwal)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $jadwal->start_time }} - {{ $jadwal->end_time }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-semibold text-gray-900">
                            {{ $jadwal->classRoom->name ?? 'Kelas tidak ditemukan' }}
                        </td>
                        <td class="py-4 px-6 text-gray-600">
                            {{ $jadwal->subject->name ?? 'Mata pelajaran tidak ditemukan' }}
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <a href="{{ route('guru.attendance.create', $jadwal->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                Isi Absensi Siswa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 px-6 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-base font-semibold text-gray-600">Tidak Ada Jadwal Mengajar Hari Ini</p>
                                <p class="text-xs text-gray-400 mt-1">Anda tidak memiliki jadwal mengajar terdaftar pada hari {{ $today }}.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection