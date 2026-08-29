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
    // Method untuk menampilkan form tambah jadwal
    public function create()
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        
        return view('admin.schedules.create', compact('classes', 'subjects', 'teachers'));
    }

    // Method untuk menyimpan jadwal[cite: 1]
    public function store(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|string', // Senin, Selasa, dst
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($request->all());

        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil dipetakan!');
    }
}