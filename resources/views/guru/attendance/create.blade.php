@extends('layouts.guru')

@section('header', 'Input Absensi Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header Kartu Info Jadwal -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center">
                    &larr; Kembali ke Jadwal
                </a>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Kelas: {{ $schedule->classRoom->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Mata Pelajaran: <strong class="text-gray-800">{{ $schedule->subject->name }}</strong> &bull; 
                Waktu: <span class="text-emerald-700 font-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span> &bull;
                Tanggal: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</span>
            </p>
        </div>
        
        @if($isAttendanceDone)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Absensi hari ini sudah pernah disimpan. Anda dapat memperbaruinya jika ada perubahan.</span>
            </div>
        @endif
    </div>

    <!-- Form Absensi -->
    <form action="{{ route('guru.attendance.store', $schedule->id) }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-900">Daftar Siswa (Total: {{ $students->count() }})</h3>
                <span class="text-xs text-gray-400">Pilih status kehadiran untuk setiap siswa</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">NISN</th>
                            <th class="py-4 px-6">Nama Siswa</th>
                            <th class="py-4 px-6 text-center">Kehadiran (Hadir / Sakit / Izin / Alpa)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($students as $index => $siswa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-600">{{ $siswa->nisn }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-900">{{ $siswa->name }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-3 sm:space-x-4">
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50/40 text-emerald-800 text-xs font-semibold hover:bg-emerald-100 transition">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Hadir" required checked class="text-emerald-600 focus:ring-emerald-500 mr-1.5">
                                        Hadir
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border border-blue-200 bg-blue-50/40 text-blue-800 text-xs font-semibold hover:bg-blue-100 transition">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Sakit" class="text-blue-600 focus:ring-blue-500 mr-1.5">
                                        Sakit
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border border-amber-200 bg-amber-50/40 text-amber-800 text-xs font-semibold hover:bg-amber-100 transition">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Izin" class="text-amber-600 focus:ring-amber-500 mr-1.5">
                                        Izin
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border border-red-200 bg-red-50/40 text-red-800 text-xs font-semibold hover:bg-red-100 transition">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Alpa" class="text-red-600 focus:ring-red-500 mr-1.5">
                                        Alpa
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-gray-400">
                                Belum ada data siswa di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50/60 border-t border-gray-100 flex justify-between items-center flex-wrap gap-4">
                <a href="{{ route('guru.dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Absensi Kelas
                </button>
            </div>
        </div>
    </form>
</div>
@endsection