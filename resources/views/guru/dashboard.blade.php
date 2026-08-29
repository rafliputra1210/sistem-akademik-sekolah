<!-- resources/views/guru/dashboard.blade.php -->
<h1>Dashboard Guru</h1>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

@if(session('success'))
    <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
@endif

<h3>Jadwal Mengajar Anda Hari Ini ({{ $today }}, {{ \Carbon\Carbon::today()->format('d M Y') }})</h3>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Jam Pelajaran</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($schedules as $jadwal)
        <tr>
            <td>{{ $jadwal->start_time }} - {{ $jadwal->end_time }}</td>
            <td>{{ $jadwal->classRoom->name }}</td>
            <td>{{ $jadwal->subject->name }}</td>
            <td>
                <!-- Tombol untuk mengklik jadwal yang sedang berlangsung -->
                <a href="{{ route('guru.attendance.create', $jadwal->id) }}">
                    <button>Isi Absensi Kelas</button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" align="center">Tidak ada jadwal mengajar hari ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>