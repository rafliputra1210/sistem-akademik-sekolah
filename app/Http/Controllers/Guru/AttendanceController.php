<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    // Menampilkan halaman absensi selfie / kamera guru
    public function camera()
    {
        $teacher = auth()->user()->teacher;
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil guru tidak ditemukan.');
        }

        $today = Carbon::today()->toDateString();
        $attendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        return view('guru.attendance.camera', compact('attendance'));
    }

    // Menyimpan absensi kamera (Masuk / Pulang) guru
    public function storeCamera(Request $request)
    {
        $request->validate([
            'photo' => 'required|string',
        ]);

        $teacher = auth()->user()->teacher;
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil guru tidak ditemukan.');
        }

        $teacherId = $teacher->id;
        $now = Carbon::now();

        // Decode Base64 Image
        $imageParts = explode(";base64,", $request->photo);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = isset($imageTypeAux[1]) ? $imageTypeAux[1] : 'jpeg';
        $imageBase64 = base64_decode($imageParts[1] ?? $request->photo);

        // Pastikan direktori attendance ada
        if (!Storage::disk('public')->exists('attendance')) {
            Storage::disk('public')->makeDirectory('attendance');
        }

        // Simpan gambar dengan path unik
        $fileName = 'attendance/' . $teacherId . '_' . $now->format('Ymd_His') . '.' . $imageType;
        Storage::disk('public')->put($fileName, $imageBase64);

        // Cek riwayat absensi guru hari ini
        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->whereDate('date', $now->toDateString())
            ->first();

        if (!$attendance) {
            // Absen Masuk (Clock In)
            TeacherAttendance::create([
                'teacher_id' => $teacherId,
                'date' => $now->toDateString(),
                'clock_in' => $now->toTimeString(),
                'photo_in' => $fileName,
                'status' => $now->format('H:i') > '07:00' ? 'Terlambat' : 'Hadir',
            ]);
            $msg = 'Absen masuk berhasil dicatat.';
        } else {
            // Absen Pulang (Clock Out)
            $attendance->update([
                'clock_out' => $now->toTimeString(),
                'photo_out' => $fileName,
            ]);
            $msg = 'Absen pulang berhasil dicatat.';
        }

        return redirect()->route('guru.dashboard')->with('success', $msg);
    }

    // Menampilkan form absensi dan daftar siswa
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

    // Menyimpan data absensi ke database (Tersinkronisasi otomatis)
    public function store(Request $request, Schedule $schedule)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $today = Carbon::today()->format('Y-m-d');

        // Looping untuk menyimpan status absensi masing-masing siswa
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