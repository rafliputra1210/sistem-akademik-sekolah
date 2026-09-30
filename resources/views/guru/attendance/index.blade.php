@extends('layouts.guru')

@section('header', 'Absensi Siswa per Jadwal Pelajaran')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2">
                {{ $todayName }}, {{ \Carbon\Carbon::parse($todayDate)->translatedFormat('d F Y') }}
            </span>
            <h2 class="text-2xl font-bold">Presensi Siswa Berdasarkan Jadwal</h2>
            <p class="text-emerald-100 text-sm mt-1 max-w-2xl">
                Daftar jadwal mata pelajaran yang Anda ampu. Silakan pilih kelas untuk mencatat kehadiran siswa secara real-time.
            </p>
        </div>
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 flex items-center gap-4 border border-white/10">
            <div class="text-right">
                <p class="text-xs text-emerald-200">Total Jadwal Tampil</p>
                <p class="text-2xl font-bold">{{ $schedules->count() }} Jadwal</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter Hari -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center space-x-2 overflow-x-auto py-1">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-2">Pilih Hari:</span>
            
            <a href="{{ route('guru.attendance.index', ['day' => $todayName]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $selectedDay === $todayName ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Hari Ini ({{ $todayName }})
            </a>

            @foreach($allDays as $day)
                <a href="{{ route('guru.attendance.index', ['day' => $day]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $selectedDay === $day ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $day }}
                </a>
            @endforeach

            <a href="{{ route('guru.attendance.index', ['day' => 'all']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $selectedDay === 'all' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Hari
            </a>
        </div>
    </div>

    <!-- Daftar Jadwal Mengajar Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schedules as $jadwal)
            @php
                $isTodaySchedule = ($jadwal->day === $todayName);
                $isAttendedToday = in_array($jadwal->id, $attendedScheduleIds);
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
                <!-- Bar Aksen Status Atas -->
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ $isAttendedToday ? 'bg-emerald-500' : ($isTodaySchedule ? 'bg-amber-400' : 'bg-gray-300') }}"></div>

                <div>
                    <!-- Badge Hari & Jam -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $isTodaySchedule ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                            {{ $jadwal->day }} &bull; {{ substr($jadwal->start_time, 0, 5) }} - {{ substr($jadwal->end_time, 0, 5) }}
                        </span>

                        @if($isAttendedToday)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Sudah Diabsen
                            </span>
                        @elseif($isTodaySchedule)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum Diabsen
                            </span>
                        @endif
                    </div>

                    <!-- Judul Kelas & Mapel -->
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                        Kelas {{ $jadwal->classRoom->name ?? '-' }}
                    </h3>
                    <p class="text-sm font-semibold text-emerald-700 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        {{ $jadwal->subject->name ?? '-' }}
                    </p>

                    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $jadwal->classRoom->students->count() ?? 0 }} Siswa Terdaftar
                        </span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 pt-4 border-t border-gray-50">
                    <a href="{{ route('guru.attendance.create', $jadwal->id) }}" 
                       class="w-full flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isAttendedToday ? 'bg-gray-100 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm' }}">
                        @if($isAttendedToday)
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit / Perbarui Absensi
                        @else
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Input Absensi Kelas
                        @endif
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Jadwal Pelajaran</h3>
                <p class="text-sm text-gray-500 mt-1">
                    @if($selectedDay !== 'all')
                        Anda tidak memiliki jadwal mengajar pada hari <strong>{{ $selectedDay }}</strong>.
                    @else
                        Anda belum memiliki jadwal mengajar yang terdaftar.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
