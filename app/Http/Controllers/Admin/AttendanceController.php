<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Default menampilkan absensi hari ini jika tidak ada filter
        $filterDate = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Mengambil data absensi beserta seluruh relasi tabelnya
        $attendances = Attendance::with(['student.classRoom', 'schedule.subject', 'schedule.teacher'])
            ->whereDate('date', $filterDate)
            ->orderBy('schedule_id')
            ->get();

        // Rekapitulasi status kehadiran untuk summary
        $rekap = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Izin'  => $attendances->where('status', 'Izin')->count(),
            'Alpa'  => $attendances->where('status', 'Alpa')->count(),
        ];

        return view('admin.attendances.index', compact('attendances', 'filterDate', 'rekap'));
    }
}