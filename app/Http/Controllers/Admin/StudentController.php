<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        // Mengambil data siswa beserta relasi kelasnya, diurutkan berdasarkan kelas lalu nama
        $students = Student::with('classRoom')
            ->orderBy('class_room_id')
            ->orderBy('name')
            ->get();
            
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        // Mengambil data kelas untuk pilihan di dropdown form
        $classes = ClassRoom::orderBy('name')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id',
        ]);

        Student::create([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class_room_id' => $request->class_room_id,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil ditambahkan ke kelas!');
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nisn' => ['required', 'string', 'max:50', Rule::unique('students', 'nisn')->ignore($student->id)],
            'name' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id',
        ]);

        $student->update([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class_room_id' => $request->class_room_id,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil dihapus!');
    }

    public function export() 
    {
        return Excel::download(new StudentsExport, 'data_siswa.xlsx'); 
    }

    public function import(Request $request) 
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);
        Excel::import(new StudentsImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data Siswa berhasil diimpor!');
    }
}