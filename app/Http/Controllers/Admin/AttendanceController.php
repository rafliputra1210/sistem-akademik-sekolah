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

        // Query agregasi SQL langsung untuk rekapitulasi performa tinggi
        $rawRekap = Attendance::whereDate('date', $filterDate)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $rekap = [
            'Hadir' => $rawRekap['Hadir'] ?? 0,
            'Sakit' => $rawRekap['Sakit'] ?? 0,
            'Izin'  => $rawRekap['Izin'] ?? 0,
            'Alpa'  => $rawRekap['Alpa'] ?? 0,
        ];

        // Mengambil data absensi dengan eager loading dan pagination
        $attendances = Attendance::with(['student.classRoom', 'schedule.subject', 'schedule.teacher'])
            ->whereDate('date', $filterDate)
            ->orderBy('schedule_id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.attendances.index', compact('attendances', 'filterDate', 'rekap'));
    }
}