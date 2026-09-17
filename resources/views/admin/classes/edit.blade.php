@extends('layouts.admin')
@section('header', 'Edit Data Kelas')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Ubah Data Kelas</h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui nama atau kode kelas ke dalam sistem.</p>
        </div>
        
        <form action="{{ route('admin.classes.update', $classRoom->id) }}" method="POST" class="p-6">
            @csrf
            <!-- Method PUT wajib ditambahkan untuk proses update di Laravel -->
            @method('PUT') 
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $classRoom->name) }}" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.classes.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
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