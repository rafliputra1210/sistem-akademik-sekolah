<!-- resources/views/admin/schedules/create.blade.php -->
<h1>Buat Jadwal Pelajaran Baru</h1>

@if(session('success'))
    <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.schedules.store') }}" method="POST">
    @csrf
    
    <div style="margin-bottom: 10px;">
        <label>Pilih Kelas:</label><br>
        <select name="class_room_id" required>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Mata Pelajaran:</label><br>
        <select name="subject_id" required>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Guru Pengampu:</label><br>
        <select name="teacher_id" required>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Hari:</label><br>
        <select name="day" required>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
        </select>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <div>
            <label>Jam Mulai:</label><br>
            <input type="time" name="start_time" required>
        </div>
        <div>
            <label>Jam Selesai:</label><br>
            <input type="time" name="end_time" required>
        </div>
    </div>

    <button type="submit">Simpan Jadwal</button>
</form>