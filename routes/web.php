<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\TeacherAttendanceController;



Route::get('/', function () {
    return view('welcome');
});

// Route utama setelah login, me-redirect ke dashboard masing-masing role
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    return match ($role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'guru'   => redirect()->route('guru.dashboard'),
        'kepsek' => redirect()->route('kepsek.dashboard'),
        default  => abort(403, 'Akses tidak sah atau role tidak dikenali.'),
    };
})->name('dashboard');

// ==========================================
// ROUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Route Export & Import Guru (Dilindungi rate limiter)
    Route::post('teachers/import', [TeacherController::class, 'import'])->middleware('throttle:10,1')->name('teachers.import');
    Route::get('teachers/export', [TeacherController::class, 'export'])->name('teachers.export');

    // Route Export & Import Siswa (Dilindungi rate limiter)
    Route::post('students/import', [StudentController::class, 'import'])->middleware('throttle:10,1')->name('students.import');
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    // Route CRUD Data Master
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('classes', ClassRoomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/teacher-attendances', [TeacherAttendanceController::class, 'index'])->name('teacher-attendances.index');
});

// ==========================================
// ROUTE GURU
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    
    // Dashboard Jadwal Mengajar
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    
    // Absensi Mandiri Guru (Kamera) - Dibatasi 15 request per menit untuk mencegah spam disk & DoS
    Route::get('/attendance-camera', [AttendanceController::class, 'camera'])->name('attendance.camera');
    Route::post('/attendance-camera', [AttendanceController::class, 'storeCamera'])->middleware('throttle:15,1')->name('attendance.store_camera');

    // Presensi / Absensi Siswa Terpadu Berdasarkan Jadwal Pelajaran Guru
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{schedule}', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/{schedule}', [AttendanceController::class, 'store'])->middleware('throttle:30,1')->name('attendance.store');

    // Redirect legacy route ke absensi berbasis jadwal
    Route::get('/absensi-kelas', fn() => redirect()->route('guru.attendance.index'))->name('student-attendance.index');
});


// ==========================================
// ROUTE KEPALA SEKOLAH (KEPSEK)
// ==========================================
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Selamat Datang Kepala Sekolah'; // Nanti diganti dengan Controller & View
    })->name('dashboard');
    
    // Nanti tambahkan route Laporan di sini
});

require __DIR__.'/auth.php';