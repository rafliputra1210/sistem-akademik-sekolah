<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;

class ScheduleController extends Controller
{
    public function index()
    {
        // Mengambil data jadwal beserta relasi, diurutkan berdasarkan hari lalu jam mulai
        $schedules = Schedule::with(['classRoom', 'subject', 'teacher'])
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get();
            
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        return view('admin.schedules.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil dipetakan!');
    }
}