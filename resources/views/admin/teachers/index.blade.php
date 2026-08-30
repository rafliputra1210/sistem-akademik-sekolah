@extends('layouts.admin')
@section('header', 'Manajemen Data Guru')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <p class="text-gray-600 text-sm">Kelola daftar data ke dalam sistem.</p>
    
    <div class="flex space-x-2">
        <!-- Form Import Excel -->
        <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-2 shadow-sm">
            @csrf
            <input type="file" name="file" required class="text-xs w-48 p-1">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded transition">
                Upload
            </button>
        </form>

        <!-- Tombol Export -->
        <a href="{{ route('admin.teachers.export') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2.5 rounded-lg shadow transition font-medium text-sm flex items-center">
            Export Excel
        </a>

        <!-- Tombol Tambah Data Manual -->
        <a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow transition font-medium text-sm">
            + Tambah
        </a>
    </div>
</div>


@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 text-sm">
                <th class="p-4 border-b">NIP / Username</th>
                <th class="p-4 border-b">Nama Lengkap</th>
                <th class="p-4 border-b text-center w-40">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($teachers as $teacher)
            <tr class="border-b hover:bg-blue-50 transition">
                <td class="p-4 font-medium">{{ $teacher->nip }}</td>
                <td class="p-4">{{ $teacher->name }}</td>
                <td class="p-4 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru {{ $teacher->name }}?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="p-8 text-center text-gray-400">Belum ada data guru yang didaftarkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection