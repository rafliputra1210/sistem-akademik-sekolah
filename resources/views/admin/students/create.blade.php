<!-- resources/views/admin/students/create.blade.php -->
<h1>Tambah Data Siswa Baru</h1>

@if(session('success'))
    <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.students.store') }}" method="POST">
    @csrf
    
    <div style="margin-bottom: 10px;">
        <label>NISN Siswa:</label><br>
        <input type="text" name="nisn" value="{{ old('nisn') }}" required>
    </div>
    
    <div style="margin-bottom: 10px;">
        <label>Nama Lengkap Siswa:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Pilih Kelas:</label><br>
        <select name="class_room_id" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_room_id') == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <button type="submit">Simpan Data Siswa</button>
</form>
