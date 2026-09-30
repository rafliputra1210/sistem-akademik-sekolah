@extends('layouts.guru')

@section('header', 'Input Absensi Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header Kartu Info Jadwal -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('guru.attendance.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Jadwal Absensi
                </a>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Kelas: {{ $schedule->classRoom->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Mata Pelajaran: <strong class="text-gray-800">{{ $schedule->subject->name }}</strong> &bull; 
                Waktu: <span class="text-emerald-700 font-medium">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span> &bull;
                Tanggal: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($isAttendanceDone)
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2.5 rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Absensi hari ini sudah pernah disimpan. Anda dapat memperbaruinya.</span>
                </div>
            @endif

            <button type="button" onclick="setAllHadir()" class="px-3.5 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Set Semua Hadir
            </button>
        </div>
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
                            <th class="py-4 px-6 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($students as $index => $siswa)
                        @php
                            $currentStatus = $existingAttendance[$siswa->id] ?? 'Hadir';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-600">{{ $siswa->nisn }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-900">{{ $siswa->name }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-2 sm:space-x-3">
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border text-xs font-semibold transition {{ $currentStatus === 'Hadir' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Hadir" required {{ $currentStatus === 'Hadir' ? 'checked' : '' }} class="status-radio hadir-radio text-emerald-600 focus:ring-emerald-500 mr-1.5">
                                        Hadir
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border text-xs font-semibold transition {{ $currentStatus === 'Sakit' ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Sakit" {{ $currentStatus === 'Sakit' ? 'checked' : '' }} class="status-radio text-blue-600 focus:ring-blue-500 mr-1.5">
                                        Sakit
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border text-xs font-semibold transition {{ $currentStatus === 'Izin' ? 'border-amber-500 bg-amber-50 text-amber-800' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Izin" {{ $currentStatus === 'Izin' ? 'checked' : '' }} class="status-radio text-amber-600 focus:ring-amber-500 mr-1.5">
                                        Izin
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg border text-xs font-semibold transition {{ $currentStatus === 'Alpa' ? 'border-red-500 bg-red-50 text-red-800' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                        <input type="radio" name="attendances[{{ $siswa->id }}]" value="Alpa" {{ $currentStatus === 'Alpa' ? 'checked' : '' }} class="status-radio text-red-600 focus:ring-red-500 mr-1.5">
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
                <a href="{{ route('guru.attendance.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Absensi Kelas
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function setAllHadir() {
        document.querySelectorAll('.hadir-radio').forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection