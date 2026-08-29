<!-- resources/views/admin/teachers/create.blade.php -->
<h1>Tambah Data Guru Baru</h1>

@if(session('success'))
    <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.teachers.store') }}" method="POST">
    @csrf
    <div style="margin-bottom: 10px;">
        <label>NIP Guru (Menjadi Username Login):</label><br>
        <input type="text" name="nip" required>
    </div>
    
    <div style="margin-bottom: 10px;">
        <label>Nama Lengkap Guru:</label><br>
        <input type="text" name="name" required>
    </div>
    
    <button type="submit">Simpan & Generate Akun</button>
</form>
<p><i>*Password default yang digenerate adalah: password123</i></p>