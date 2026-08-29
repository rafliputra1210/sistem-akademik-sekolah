@extends('layouts.admin')
@section('header', 'Manajemen Jadwal Pelajaran')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600 text-sm">Kelola pemetaan jadwal mengajar dan kelas.</p>
    <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-blue-700 transition font-medium text-sm">
        + Tambah Jadwal
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-4 border-b font-semibold">Hari</th>
                    <th class="p-4 border-b font-semibold">Waktu</th>
                    <th class="p-4 border-b font-semibold">Kelas</th>
                    <th class="p-4 border-b font-semibold">Mata Pelajaran</th>
                    <th class="p-4 border-b font-semibold">Guru Pengampu</th>
                    <th class="p-4 border-b font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($schedules as $jadwal)
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-4 font-bold text-gray-900">{{ $jadwal->day }}</td>
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold border border-blue-100">
                            {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                        </span>
                    </td>
                    <td class="p-4">{{ $jadwal->classRoom->name }}</td>
                    <td class="p-4">{{ $jadwal->subject->name }}</td>
                    <td class="p-4 font-medium">{{ $jadwal->teacher->name }}</td>
                    <td class="p-4 text-center space-x-2">
                        <button class="text-blue-500 hover:text-blue-700 font-medium text-xs">Edit</button>
                        <button class="text-red-500 hover:text-red-700 font-medium text-xs">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">Belum ada jadwal yang dipetakan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection