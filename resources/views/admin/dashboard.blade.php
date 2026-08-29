@extends('layouts.admin')
@section('header', 'Dashboard Utama')

@section('content')
<!-- Dashboard Header Text -->
<div class="mb-6">
    <p class="text-gray-500 text-sm">Pantau data akademi, guru, siswa, dan absensi hari ini</p>
</div>

<!-- Summary Cards (Modern Card Layout) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card Guru -->
    <div class="bg-white p-5 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-5">
        <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Guru</h3>
            <p class="text-3xl font-bold text-gray-900 leading-none">{{ $totalTeachers }}</p>
        </div>
    </div>
    
    <!-- Card Siswa -->
    <div class="bg-white p-5 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-5">
        <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center text-green-500">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Siswa</h3>
            <p class="text-3xl font-bold text-gray-900 leading-none">{{ $totalStudents }}</p>
        </div>
    </div>
    
    <!-- Card Kelas -->
    <div class="bg-white p-5 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-5">
        <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Kelas</h3>
            <p class="text-3xl font-bold text-gray-900 leading-none">{{ $totalClasses }}</p>
        </div>
    </div>
</div>

<!-- Absensi Real-time (Clean Table UI) -->
<div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Monitoring Absensi</h3>
            <p class="text-sm text-gray-500">Hari ini, {{ \Carbon\Carbon::today()->format('d M Y') }}</p>
        </div>
        
        <!-- Summary Badges -->
        <div class="flex flex-wrap gap-3 text-sm">
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></span>
                <span class="font-medium text-gray-600">Hadir: <span class="text-gray-900 font-bold ml-1">{{ $rekapToday['Hadir'] }}</span></span>
            </div>
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 mr-2"></span>
                <span class="font-medium text-gray-600">Sakit: <span class="text-gray-900 font-bold ml-1">{{ $rekapToday['Sakit'] }}</span></span>
            </div>
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-2"></span>
                <span class="font-medium text-gray-600">Izin: <span class="text-gray-900 font-bold ml-1">{{ $rekapToday['Izin'] }}</span></span>
            </div>
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2"></span>
                <span class="font-medium text-gray-600">Alpa: <span class="text-gray-900 font-bold ml-1">{{ $rekapToday['Alpa'] }}</span></span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-50">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                    <th class="p-4 rounded-tl-xl">Nama Siswa</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4">Mata Pelajaran</th>
                    <th class="p-4">Guru Pengampu</th>
                    <th class="p-4 text-center rounded-tr-xl">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-700">
                @forelse($attendancesToday as $absen)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs mr-3">
                            {{ substr($absen->student->name, 0, 1) }}
                        </div>
                        <span class="text-gray-900 font-semibold">{{ $absen->student->name }}</span>
                    </td>
                    <td class="p-4">{{ $absen->student->classRoom->name }}</td>
                    <td class="p-4 text-gray-600">{{ $absen->schedule->subject->name }}</td>
                    <td class="p-4 text-gray-600">{{ $absen->schedule->teacher->name }}</td>
                    <td class="p-4 text-center">
                        @if($absen->status == 'Hadir')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Hadir</span>
                        @elseif($absen->status == 'Sakit')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Sakit</span>
                        @elseif($absen->status == 'Izin')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Izin</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Alpa</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Belum ada data absensi hari ini.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection