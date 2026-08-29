@extends('layouts.admin')
@section('header', 'Manajemen Data Siswa')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600 text-sm">Kelola profil siswa dan penempatan kelas.</p>
    <a href="{{ route('admin.students.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-blue-700 transition font-medium text-sm">
        + Tambah Siswa Baru
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
                    <th class="p-4 border-b font-semibold w-16 text-center">No</th>
                    <th class="p-4 border-b font-semibold">NISN</th>
                    <th class="p-4 border-b font-semibold">Nama Lengkap</th>
                    <th class="p-4 border-b font-semibold">Kelas</th>
                    <th class="p-4 border-b font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($students as $index => $siswa)
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-4 text-center">{{ $index + 1 }}</td>
                    <td class="p-4 font-medium text-gray-900">{{ $siswa->nisn }}</td>
                    <td class="p-4 font-bold">{{ $siswa->name }}</td>
                    <td class="p-4">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $siswa->classRoom->name }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button class="text-blue-500 hover:text-blue-700 font-medium text-xs">Edit</button>
                        <button class="text-red-500 hover:text-red-700 font-medium text-xs">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada data siswa yang didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection