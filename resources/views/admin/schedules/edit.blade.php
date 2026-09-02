@extends('layouts.admin')
@section('header', 'Edit Jadwal Pelajaran')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/75 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Edit Pemetaan Jadwal Pelajaran</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui kelas, mata pelajaran, guru, atau waktu mengajar.</p>
            </div>
            <a href="{{ route('admin.schedules.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">
                &larr; Kembali ke Matriks
            </a>
        </div>

        @if(session('error'))
            <div class="m-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl text-sm shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilihan Hari -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hari Pelajaran <span class="text-red-500">*</span></label>
                    <select name="day" id="select-day" required class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm bg-white">
                        <option value="">-- Pilih Hari --</option>
                        @foreach($days as $d)
                            <option value="{{ $d }}" {{ old('day', $schedule->day) == $d ? 'selected' : '' }}>Hari {{ $d }}</option>
                        @endforeach
                    </select>
                    @error('day')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="class_room_id" id="select-class" required class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_room_id', $schedule->class_room_id) == $class->id ? 'selected' : '' }}>
                                Kelas {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_room_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Mata Pelajaran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="subject_id" id="select-subject" required class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm bg-white">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Guru Pengampu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Guru Pengampu <span class="text-red-500">*</span></label>
                    <select name="teacher_id" id="select-teacher" required class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm bg-white">
                        <option value="">-- Pilih Guru Pengampu --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} {{ $teacher->jabatan ? '('.$teacher->jabatan.')' : '' }} (NIP: {{ $teacher->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preset Slot Jam Pelajaran -->
                <div class="md:col-span-2 bg-amber-50/60 p-4 rounded-xl border border-amber-200/80">
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        ⏱️ Pilih Slot Jam Pelajaran (Preset Cepat)
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                        @foreach($timeSlots as $slotIdx => $slot)
                            <button type="button" onclick="setSlotTime('{{ $slot['start'] }}', '{{ $slot['end'] }}')" 
                                class="p-2 text-center rounded-lg bg-white border border-amber-200 hover:bg-amber-400 hover:text-gray-950 text-xs font-semibold shadow-xs transition">
                                <span class="block font-bold text-gray-900">{{ $slot['label'] }}</span>
                                <span class="block text-[11px] text-gray-500 mt-0.5">{{ $slot['start'] }} - {{ $slot['end'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Jam Mulai & Jam Selesai -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" required 
                        value="{{ old('start_time', substr($schedule->start_time, 0, 5)) }}" 
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                    @error('start_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" id="end_time" required 
                        value="{{ old('end_time', substr($schedule->end_time, 0, 5)) }}" 
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border text-sm">
                    @error('end_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.schedules.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setSlotTime(start, end) {
        document.getElementById('start_time').value = start;
        document.getElementById('end_time').value = end;
    }
</script>
@endsection
