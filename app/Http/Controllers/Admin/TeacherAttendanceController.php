<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request, default-nya hari ini
        $filterDate = $request->get('date', Carbon::today()->toDateString());

        // Tarik data absensi guru berdasarkan tanggal yang dipilih
        $attendances = TeacherAttendance::with('teacher')
            ->whereDate('date', $filterDate)
            ->orderBy('clock_in', 'desc')
            ->get();

        return view('admin.teacher-attendances.index', compact('attendances', 'filterDate'));
    }
}