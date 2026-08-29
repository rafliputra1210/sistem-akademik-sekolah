<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassRoom;

class StudentController extends Controller
{
    // Form tambah siswa
    public function create()
    {
        $classes = ClassRoom::all();
        return view('admin.students.create', compact('classes'));
    }

    // Menyimpan data siswa beserta kelasnya
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id', // Harus masuk kelas mana
        ]);

        Student::create([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class_room_id' => $request->class_room_id,
        ]);

        return redirect()->back()->with('success', 'Data Siswa berhasil ditambahkan ke kelas!');
    }
    
    // (Tambahkan method update & destroy jika diperlukan)
}