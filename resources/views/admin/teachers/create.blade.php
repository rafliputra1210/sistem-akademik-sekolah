@extends('layouts.admin')
@section('header', 'Tambah Data Guru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Formulir Pendaftaran Guru</h3>
            <p class="text-sm text-gray-500 mt-1">Masukkan data profil guru. Sistem akan otomatis membuatkan akun login[cite: 1].</p>
        </div>
        
        <form action="{{ route('admin.teachers.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-5">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP / ID Guru <span class="text-red-500">*</span></label>
                <input type="text" name="nip" id="nip" required placeholder="Masukkan NIP (Akan menjadi Username)" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                @error('nip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan / Posisi</label>
                <input type="text" name="jabatan" id="jabatan" placeholder="Contoh: Wali Kelas 10, Guru Mapel, dsb" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                <p class="text-xs text-gray-500 mt-1">Opsional, bisa dikosongkan.</p>
            </div>
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required placeholder="Masukkan Nama Lengkap Guru" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kotak Informasi Generate Akun -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Pembuatan Akun Otomatis</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Username:</strong> Menggunakan NIP yang diinputkan di atas.</li>
                                <li><strong>Password Default:</strong> <code>password123</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.teachers.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow transition">
                    Simpan & Generate Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection