@extends('layouts.admin')
@section('header', 'Rekap Absensi Kehadiran Guru')

@section('content')
<!-- Filter Tanggal -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Laporan Kehadiran Harian</h3>
        <p class="text-sm text-gray-500">Pantau jam masuk dan pulang guru berserta foto bukti absen.</p>
    </div>
    
    <form action="{{ route('admin.teacher-attendances.index') }}" method="GET" class="mt-4 md:mt-0 flex items-center space-x-3">
        <label for="date" class="text-sm font-medium text-gray-600">Pilih Tanggal:</label>
        <input type="date" name="date" id="date" value="{{ $filterDate }}" 
            class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-sm"
            onchange="this.form.submit()">
    </form>
</div>

<!-- Tabel Absensi -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-sm">
                    <th class="p-4 border-b">Nama Guru</th>
                    <th class="p-4 border-b">Jam Masuk</th>
                    <th class="p-4 border-b text-center">Foto Masuk</th>
                    <th class="p-4 border-b">Jam Pulang</th>
                    <th class="p-4 border-b text-center">Foto Pulang</th>
                    <th class="p-4 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $absen)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">
                        <div class="font-bold text-gray-900">{{ $absen->teacher->name ?? 'Guru Terhapus' }}</div>
                        <div class="text-xs text-gray-500">{{ $absen->teacher->nip ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-700">
                        {{ $absen->clock_in ? \Carbon\Carbon::parse($absen->clock_in)->format('H:i') : '-' }}
                    </td>
                    <td class="p-4 text-center">
                        @if($absen->photo_in)
                            <a href="{{ asset('storage/'.$absen->photo_in) }}" target="_blank" class="text-blue-500 hover:underline text-xs">Lihat Foto</a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-700">
                        {{ $absen->clock_out ? \Carbon\Carbon::parse($absen->clock_out)->format('H:i') : '-' }}
                    </td>
                    <td class="p-4 text-center">
                        @if($absen->photo_out)
                            <a href="{{ asset('storage/'.$absen->photo_out) }}" target="_blank" class="text-blue-500 hover:underline text-xs">Lihat Foto</a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($absen->status == 'Hadir')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Hadir</span>
                        @elseif($absen->status == 'Terlambat')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Terlambat</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $absen->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        Belum ada data absensi guru pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection