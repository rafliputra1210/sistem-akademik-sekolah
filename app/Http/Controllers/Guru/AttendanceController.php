<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Menampilkan form absensi dan daftar siswa[cite: 1]
    public function create(Schedule $schedule)
    {
        // Keamanan: Pastikan guru hanya mengakses jadwalnya sendiri
        if ($schedule->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Akses ditolak.');
        }

        $today = Carbon::today()->format('Y-m-d');
        $students = $schedule->classRoom->students;

        // Cek apakah guru sudah melakukan absensi untuk jadwal ini pada hari ini
        $isAttendanceDone = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->exists();

        return view('guru.attendance.create', compact('schedule', 'students', 'today', 'isAttendanceDone'));
    }

    // Menyimpan data absensi ke database (Tersinkronisasi otomatis)[cite: 1]
    public function store(Request $request, Schedule $schedule)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $today = Carbon::today()->format('Y-m-d');

        // Looping untuk menyimpan status absensi masing-masing siswa[cite: 1]
        foreach ($request->attendances as $student_id => $status) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $student_id,
                    'date' => $today,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('guru.dashboard')->with('success', 'Data absensi kelas berhasil disimpan!');
    }
}