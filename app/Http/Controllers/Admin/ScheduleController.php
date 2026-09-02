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
    // Daftar slot jam pelajaran standar sekolah
    protected $defaultTimeSlots = [
        1 => ['label' => 'JAM KE 1', 'start' => '07:00', 'end' => '08:00'],
        2 => ['label' => 'JAM KE 2', 'start' => '08:00', 'end' => '09:00'],
        3 => ['label' => 'JAM KE 3', 'start' => '09:00', 'end' => '10:00'],
        4 => ['label' => 'JAM KE 4', 'start' => '10:00', 'end' => '11:00'],
        5 => ['label' => 'JAM KE 5', 'start' => '11:00', 'end' => '12:00'],
        6 => ['label' => 'JAM KE 6', 'start' => '12:30', 'end' => '13:30'],
    ];

    protected $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function index()
    {
        $classes = ClassRoom::orderBy('name')->get();
        $days = $this->days;
        $timeSlots = $this->defaultTimeSlots;

        // Ambil semua jadwal dengan relasi
        $schedules = Schedule::with(['classRoom', 'subject', 'teacher'])
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get();

        // Susun matriks jadwal: [hari][jam_slot][class_id] = schedule
        $matrix = [];
        foreach ($days as $day) {
            $matrix[$day] = [];
            foreach ($timeSlots as $slotIndex => $slot) {
                $matrix[$day][$slotIndex] = [];
                foreach ($classes as $class) {
                    // Cari jadwal yang cocok untuk kelas, hari, dan slot waktu ini
                    $matched = $schedules->first(function ($item) use ($day, $slot, $class) {
                        if ($item->day !== $day || $item->class_room_id !== $class->id) {
                            return false;
                        }
                        $itemStart = substr($item->start_time, 0, 5);
                        $slotStart = $slot['start'];
                        // Cocokkan jam mulai atau range
                        return $itemStart === $slotStart || (
                            $itemStart >= $slot['start'] && $itemStart < $slot['end']
                        );
                    });

                    $matrix[$day][$slotIndex][$class->id] = $matched;
                }
            }
        }

        return view('admin.schedules.index', compact('schedules', 'classes', 'days', 'timeSlots', 'matrix'));
    }

    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $days = $this->days;
        $timeSlots = $this->defaultTimeSlots;

        return view('admin.schedules.create', compact('classes', 'subjects', 'teachers', 'days', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id'    => 'required|exists:subjects,id',
            'teacher_id'    => 'required|exists:teachers,id',
            'day'           => 'required|string',
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
        ]);

        $startTime = date('H:i:s', strtotime($request->start_time));
        $endTime = date('H:i:s', strtotime($request->end_time));

        // 1. Cek bentrok jadwal Guru di hari & jam yang sama
        $teacherConflict = Schedule::with('classRoom')
            ->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($teacherConflict) {
            $teacher = Teacher::find($request->teacher_id);
            return redirect()->back()->withInput()->with('error', 
                "Bentrok! Guru {$teacher->name} sudah memiliki jadwal mengajar di Kelas {$teacherConflict->classRoom->name} pada hari {$request->day} pukul {$teacherConflict->start_time} - {$teacherConflict->end_time}."
            );
        }

        // 2. Cek bentrok jadwal Kelas di hari & jam yang sama
        $classConflict = Schedule::with('subject')
            ->where('class_room_id', $request->class_room_id)
            ->where('day', $request->day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($classConflict) {
            $class = ClassRoom::find($request->class_room_id);
            return redirect()->back()->withInput()->with('error', 
                "Bentrok! Kelas {$class->name} sudah memiliki jadwal mata pelajaran {$classConflict->subject->name} pada hari {$request->day} pukul {$classConflict->start_time} - {$classConflict->end_time}."
            );
        }

        Schedule::create([
            'class_room_id' => $request->class_room_id,
            'subject_id'    => $request->subject_id,
            'teacher_id'    => $request->teacher_id,
            'day'           => $request->day,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil dipetakan!');
    }

    public function edit(Schedule $schedule)
    {
        $classes = ClassRoom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $days = $this->days;
        $timeSlots = $this->defaultTimeSlots;

        return view('admin.schedules.edit', compact('schedule', 'classes', 'subjects', 'teachers', 'days', 'timeSlots'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id'    => 'required|exists:subjects,id',
            'teacher_id'    => 'required|exists:teachers,id',
            'day'           => 'required|string',
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
        ]);

        $startTime = date('H:i:s', strtotime($request->start_time));
        $endTime = date('H:i:s', strtotime($request->end_time));

        // 1. Cek bentrok jadwal Guru (kecuali jadwal saat ini)
        $teacherConflict = Schedule::with('classRoom')
            ->where('id', '!=', $schedule->id)
            ->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($teacherConflict) {
            $teacher = Teacher::find($request->teacher_id);
            return redirect()->back()->withInput()->with('error', 
                "Bentrok! Guru {$teacher->name} sudah memiliki jadwal mengajar di Kelas {$teacherConflict->classRoom->name} pada hari {$request->day} pukul {$teacherConflict->start_time} - {$teacherConflict->end_time}."
            );
        }

        // 2. Cek bentrok jadwal Kelas (kecuali jadwal saat ini)
        $classConflict = Schedule::with('subject')
            ->where('id', '!=', $schedule->id)
            ->where('class_room_id', $request->class_room_id)
            ->where('day', $request->day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($classConflict) {
            $class = ClassRoom::find($request->class_room_id);
            return redirect()->back()->withInput()->with('error', 
                "Bentrok! Kelas {$class->name} sudah memiliki jadwal mata pelajaran {$classConflict->subject->name} pada hari {$request->day} pukul {$classConflict->start_time} - {$classConflict->end_time}."
            );
        }

        $schedule->update([
            'class_room_id' => $request->class_room_id,
            'subject_id'    => $request->subject_id,
            'teacher_id'    => $request->teacher_id,
            'day'           => $request->day,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil dihapus!');
    }
}