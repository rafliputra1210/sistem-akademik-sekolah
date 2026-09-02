@extends('layouts.admin')
@section('header', 'Data Rekapitulasi Absensi')

@section('content')
<!-- Filter dan Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="col-span-1 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Filter Tanggal Absensi</h3>
        <form action="{{ route('admin.attendances.index') }}" method="GET" class="flex items-center space-x-2">
            <input type="date" name="date" value="{{ $filterDate }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">Filter</button>
        </form>
    </div>

    <div class="col-span-2 bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-around text-center">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Hadir</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $rekap['Hadir'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Sakit</p>
            <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $rekap['Sakit'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Izin</p>
            <p class="text-2xl font-bold text-blue-500 mt-1">{{ $rekap['Izin'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Alpa</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $rekap['Alpa'] }}</p>
        </div>
    </div>
</div>

<!-- Tabel Data Absensi -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-4 border-b font-semibold w-12 text-center">No</th>
                    <th class="p-4 border-b font-semibold">Nama Siswa</th>
                    <th class="p-4 border-b font-semibold">Kelas</th>
                    <th class="p-4 border-b font-semibold">Mata Pelajaran</th>
                    <th class="p-4 border-b font-semibold">Guru Pengampu</th>
                    <th class="p-4 border-b font-semibold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($attendances as $index => $absen)
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-4 text-center text-gray-500">{{ ($attendances->firstItem() ?? 1) + $index }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $absen->student->name }}</td>
                    <td class="p-4">{{ $absen->student->classRoom->name }}</td>
                    <td class="p-4">{{ $absen->schedule->subject->name }}</td>
                    <td class="p-4">{{ $absen->schedule->teacher->name }}</td>
                    <td class="p-4 text-center">
                        @if($absen->status == 'Hadir')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Hadir</span>
                        @elseif($absen->status == 'Sakit')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Sakit</span>
                        @elseif($absen->status == 'Izin')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Izin</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Alpa</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        Tidak ada data absensi yang tercatat pada tanggal {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection