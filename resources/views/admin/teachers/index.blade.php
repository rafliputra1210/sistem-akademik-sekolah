@extends('layouts.admin')
@section('header', 'Manajemen Data Guru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Guru Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 text-sm">
                <th class="p-4 border-b">NIP / Username</th>
                <th class="p-4 border-b">Nama Lengkap</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($teachers as $teacher)
            <tr class="border-b">
                <td class="p-4">{{ $teacher->nip }}</td>
                <td class="p-4">{{ $teacher->name }}</td>
                <td class="p-4">
                    <!-- Tombol Edit/Delete bisa ditambahkan di sini nantinya -->
                    <span class="text-gray-400">Akun Aktif</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection