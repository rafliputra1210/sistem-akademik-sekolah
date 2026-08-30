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
    // Route Export & Import Guru
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::get('teachers/export', [TeacherController::class, 'export'])->name('teachers.export');

    // Route Export & Import Siswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    // Route CRUD Data Master
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('classes', ClassRoomController::class);
    Route::resource('subjects', SubjectController::class);
});

// ==========================================
// ROUTE GURU
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    
    // Dashboard Jadwal Mengajar
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    
    // Input Absensi Siswa
    Route::get('/attendance/{schedule}', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/{schedule}', [AttendanceController::class, 'store'])->name('attendance.store');
    
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