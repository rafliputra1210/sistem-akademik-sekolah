<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Mendapatkan profil guru dari user yang login
        $teacher = auth()->user()->teacher;

        // Mendapatkan nama hari ini dalam bahasa Indonesia untuk pencocokan DB
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $today = $days[Carbon::now()->format('l')];

        // Query jadwal berdasarkan ID guru dan hari ini jika profil guru ada
        $schedules = collect();
        if ($teacher) {
            $schedules = Schedule::with(['classRoom', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('day', $today)
                ->orderBy('start_time')
                ->get();
        }

        $attendedScheduleIds = [];
        if ($teacher && $schedules->isNotEmpty()) {
            $todayDate = Carbon::today()->format('Y-m-d');
            $attendedScheduleIds = \App\Models\Attendance::whereIn('schedule_id', $schedules->pluck('id'))
                ->whereDate('date', $todayDate)
                ->distinct()
                ->pluck('schedule_id')
                ->toArray();
        }

        return view('guru.dashboard', compact('schedules', 'today', 'teacher', 'attendedScheduleIds'));
    }
}