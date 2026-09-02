<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        $photoData = $request->photo;

        // Validasi format Data URL Base64 dan ekstrak mime-type
        if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $photoData, $matches)) {
            return redirect()->back()->with('error', 'Format foto tidak valid. Harus berupa gambar JPEG, PNG, atau WEBP.');
        }

        $mimeType = strtolower($matches[1]);
        $extension = $mimeType === 'jpeg' ? 'jpg' : $mimeType;
        $imageBase64 = substr($photoData, strpos($photoData, ',') + 1);
        $decodedImage = base64_decode($imageBase64, true);

        // Validasi keabsahan data biner gambar dan batasi ukuran maksimum (contoh: 5MB)
        if ($decodedImage === false || strlen($decodedImage) > 5 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Foto tidak dapat diproses atau melebihi batas ukuran maksimal (5MB).');
        }

        // Verifikasi integritas gambar menggunakan getimagesizefromstring
        $imageInfo = @getimagesizefromstring($decodedImage);
        if ($imageInfo === false) {
            return redirect()->back()->with('error', 'Berkas yang diunggah bukan format gambar yang valid.');
        }

        $teacherId = $teacher->id;
        $now = Carbon::now();

        // Pastikan direktori attendance ada di disk public
        if (!Storage::disk('public')->exists('attendance')) {
            Storage::disk('public')->makeDirectory('attendance');
        }

        // Simpan gambar dengan nama acak aman dan path unik
        $fileName = 'attendance/' . $teacherId . '_' . $now->format('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        Storage::disk('public')->put($fileName, $decodedImage);

        // Simpan atau perbarui status absensi dalam database transaction
        $msg = DB::transaction(function () use ($teacherId, $now, $fileName) {
            $attendance = TeacherAttendance::where('teacher_id', $teacherId)
                ->whereDate('date', $now->toDateString())
                ->lockForUpdate()
                ->first();

            if (!$attendance) {
                // Absen Masuk (Clock In)
                TeacherAttendance::create([
                    'teacher_id' => $teacherId,
                    'date'       => $now->toDateString(),
                    'clock_in'   => $now->toTimeString(),
                    'photo_in'   => $fileName,
                    'status'     => $now->format('H:i') > '07:00' ? 'Terlambat' : 'Hadir',
                ]);
                return 'Absen masuk berhasil dicatat.';
            }

            if ($attendance->clock_out) {
                return 'Absensi hari ini sudah lengkap (Masuk & Pulang).';
            }

            // Absen Pulang (Clock Out)
            $attendance->update([
                'clock_out' => $now->toTimeString(),
                'photo_out' => $fileName,
            ]);
            return 'Absen pulang berhasil dicatat.';
        });

        return redirect()->route('guru.dashboard')->with('success', $msg);
    }

    // Menampilkan form absensi dan daftar siswa
    public function create(Schedule $schedule)
    {
        $teacher = auth()->user()->teacher;
        // Keamanan: Pastikan guru hanya mengakses jadwalnya sendiri
        if (!$teacher || $schedule->teacher_id !== $teacher->id) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses jadwal ini.');
        }

        $today = Carbon::today()->format('Y-m-d');
        $students = $schedule->classRoom->students;

        // Cek apakah guru sudah melakukan absensi untuk jadwal ini pada hari ini
        $isAttendanceDone = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->exists();

        return view('guru.attendance.create', compact('schedule', 'students', 'today', 'isAttendanceDone'));
    }

    // Menyimpan data absensi ke database (Optimasi Batch Upsert & Keamanan Otorisasi)
    public function store(Request $request, Schedule $schedule)
    {
        $teacher = auth()->user()->teacher;
        // Keamanan Otorisasi: Pastikan guru hanya dapat menyimpan absensi jadwal miliknya sendiri
        if (!$teacher || $schedule->teacher_id !== $teacher->id) {
            abort(403, 'Akses ditolak. Anda tidak berhak menyimpan absensi untuk jadwal ini.');
        }

        $request->validate([
            'attendances'   => 'required|array',
            'attendances.*' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // Validasi bahwa ID siswa yang dikirim memang terdaftar di kelas jadwal tersebut
        $validStudentIds = $schedule->classRoom->students()->pluck('id')->toArray();

        $records = [];
        foreach ($request->attendances as $studentId => $status) {
            if (in_array((int)$studentId, $validStudentIds, true)) {
                $records[] = [
                    'schedule_id' => $schedule->id,
                    'student_id'  => (int)$studentId,
                    'date'        => $today,
                    'status'      => $status,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        if (empty($records)) {
            return redirect()->back()->with('error', 'Tidak ada data siswa yang valid untuk diabsen.');
        }

        // Eksekusi upsert dalam satu database transaction (1 query tunggal, anti N+1)
        DB::transaction(function () use ($records) {
            Attendance::upsert(
                $records,
                ['schedule_id', 'student_id', 'date'],
                ['status', 'updated_at']
            );
        });

        return redirect()->route('guru.dashboard')->with('success', 'Data absensi kelas berhasil disimpan!');
    }
}