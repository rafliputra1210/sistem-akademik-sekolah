<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Statistik Master Data
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalClasses = ClassRoom::count();

        // Monitoring Terpusat: Rekap Absensi Hari Ini secara real-time
        $attendancesToday = Attendance::with(['student.classRoom', 'schedule.subject', 'schedule.teacher'])
                                      ->whereDate('date', $today)
                                      ->get();

        // Menghitung rekapitulasi status hari ini
        $rekapToday = [
            'Hadir' => $attendancesToday->where('status', 'Hadir')->count(),
            'Sakit' => $attendancesToday->where('status', 'Sakit')->count(),
            'Izin'  => $attendancesToday->where('status', 'Izin')->count(),
            'Alpa'  => $attendancesToday->where('status', 'Alpa')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalTeachers', 
            'totalStudents', 
            'totalClasses', 
            'attendancesToday', 
            'rekapToday'
        ));
    }
}