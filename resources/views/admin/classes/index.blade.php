@extends('layouts.admin')
@section('header', 'Manajemen Data Kelas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600 text-sm">Kelola daftar kelas dan pantau kapasitas siswa.</p>
    <a href="{{ route('admin.classes.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-blue-700 transition font-medium text-sm">
        + Tambah Kelas Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Card Ringkasan Kelas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-4 bg-blue-50 text-blue-600 rounded-lg mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Kelas Aktif</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalClasses }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-4 bg-green-50 text-green-600 rounded-lg mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Keseluruhan Siswa</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-4 bg-purple-50 text-purple-600 rounded-lg mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Rata-rata Siswa / Kelas</p>
            <p class="text-2xl font-bold text-gray-900">± {{ $avgStudentsPerClass }}</p>
        </div>
    </div>
</div>
<!-- Selesai Card Ringkasan -->

<!-- Tabel Kelas (Tetap biarkan kode tabel Anda sebelumnya di bawah ini) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-4 border-b font-semibold w-16 text-center">No</th>
                    <th class="p-4 border-b font-semibold">Nama Kelas</th>
                    <th class="p-4 border-b font-semibold text-center w-40">Jumlah Siswa</th>
                    <th class="p-4 border-b font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($classes as $index => $kelas)
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-4 text-center">{{ $index + 1 }}</td>
                    <td class="p-4 font-medium text-gray-900">{{ $kelas->name }}</td>
                    <td class="p-4 text-center">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $kelas->students_count }} Siswa
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <!-- Placeholder untuk tombol Edit/Hapus -->
                        <button class="text-blue-500 hover:text-blue-700 font-medium text-xs">Edit</button>
                        <button class="text-red-500 hover:text-red-700 font-medium text-xs">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">Belum ada data kelas yang didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection