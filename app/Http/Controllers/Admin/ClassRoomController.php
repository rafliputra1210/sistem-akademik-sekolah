<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;

class ClassRoomController extends Controller
{
   public function index()
{
    // Mengambil data kelas sekaligus menghitung jumlah siswa per kelas
    $classes = \App\Models\ClassRoom::withCount('students')->get();
    
    // Menghitung statistik untuk Card Summary
    $totalClasses = $classes->count();
    $totalStudents = $classes->sum('students_count');
    // Mencegah pembagian dengan nol jika belum ada kelas
    $avgStudentsPerClass = $totalClasses > 0 ? round($totalStudents / $totalClasses) : 0;

    return view('admin.classes.index', compact('classes', 'totalClasses', 'totalStudents', 'avgStudentsPerClass'));
}

public function create()
{
    return view('admin.classes.create');
}
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:class_rooms,name']);
        ClassRoom::create($request->all());
        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }
}