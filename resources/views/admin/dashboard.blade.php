<!-- resources/views/admin/dashboard.blade.php -->
<h1>Dashboard Admin</h1>
<p>Selamat datang, Administrator!</p>

<div style="display: flex; gap: 20px; margin-bottom: 20px;">
    <div style="border: 1px solid #ccc; padding: 15px;">Total Guru: <b>{{ $totalTeachers }}</b></div>
    <div style="border: 1px solid #ccc; padding: 15px;">Total Siswa: <b>{{ $totalStudents }}</b></div>
    <div style="border: 1px solid #ccc; padding: 15px;">Total Kelas: <b>{{ $totalClasses }}</b></div>
</div>

<h3>Monitoring Absensi Hari Ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</h3>
<ul>
    <li>Hadir: {{ $rekapToday['Hadir'] }}</li>
    <li>Sakit: {{ $rekapToday['Sakit'] }}</li>
    <li>Izin: {{ $rekapToday['Izin'] }}</li>
    <li>Alpa: {{ $rekapToday['Alpa'] }}</li>
</ul>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Guru Pengampu</th>
            <th>Status Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendancesToday as $absen)
        <tr>
            <td>{{ $absen->student->name }}</td>
            <td>{{ $absen->student->classRoom->name }}</td>
            <td>{{ $absen->schedule->subject->name }}</td>
            <td>{{ $absen->schedule->teacher->name }}</td>
            <td><strong>{{ $absen->status }}</strong></td>
        </tr>
        @empty
        <tr>
            <td colspan="5" align="center">Belum ada data absensi hari ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>