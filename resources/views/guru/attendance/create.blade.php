<!-- resources/views/guru/attendance/create.blade.php -->
<h1>Input Absensi Kelas: {{ $schedule->classRoom->name }}</h1>
<h3>Mata Pelajaran: {{ $schedule->subject->name }} | Tanggal: {{ $today }}</h3>

@if($isAttendanceDone)
    <div style="background-color: yellow; padding: 10px; margin-bottom: 15px;">
        <strong>Perhatian:</strong> Anda sudah mengisi absensi untuk jadwal ini. Menyimpan ulang akan memperbarui data sebelumnya.
    </div>
@endif

<form action="{{ route('guru.attendance.store', $schedule->id) }}" method="POST">
    @csrf
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $siswa)
            <tr>
                <td>{{ $siswa->nisn }}</td>
                <td>{{ $siswa->name }}</td>
                <td>
                    <!-- Array input untuk absensi massal -->
                    <label><input type="radio" name="attendances[{{ $siswa->id }}]" value="Hadir" required checked> Hadir</label> &nbsp;
                    <label><input type="radio" name="attendances[{{ $siswa->id }}]" value="Sakit"> Sakit</label> &nbsp;
                    <label><input type="radio" name="attendances[{{ $siswa->id }}]" value="Izin"> Izin</label> &nbsp;
                    <label><input type="radio" name="attendances[{{ $siswa->id }}]" value="Alpa"> Alpa</label>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" align="center">Belum ada data siswa di kelas ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <br>
    <a href="{{ route('guru.dashboard') }}">Kembali</a>
    <button type="submit" style="float: right;">Simpan Absensi</button>
</form>