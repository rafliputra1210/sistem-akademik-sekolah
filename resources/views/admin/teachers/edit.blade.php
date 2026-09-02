@extends('layouts.admin')
@section('header', 'Edit Data Guru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Form Edit Guru</h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui data profil guru dan username login.</p>
        </div>
        
        <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP / Username <span class="text-red-500">*</span></label>
                <input type="text" name="nip" id="nip" required value="{{ old('nip', $teacher->nip) }}" placeholder="Masukkan NIP (Akan menjadi Username)" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                @error('nip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan / Posisi</label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $teacher->jabatan) }}" placeholder="Contoh: Wali Kelas 10, Guru Mapel, dsb" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                <p class="text-xs text-gray-500 mt-1">Opsional, bisa dikosongkan.</p>
                @error('jabatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $teacher->name) }}" placeholder="Masukkan Nama Lengkap Guru" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.teachers.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
