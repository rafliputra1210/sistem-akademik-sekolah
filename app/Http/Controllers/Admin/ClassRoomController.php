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
        $request->validate(['name' => 'required|string|max:255|unique:class_rooms,name']);
        ClassRoom::create($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return view('admin.classes.edit', compact('classRoom'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_rooms,name,' . $id,
        ], [
            'name.unique' => 'Nama kelas ini sudah digunakan.'
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Data Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);

        if ($classRoom->students()->count() > 0) {
            return redirect()->route('admin.classes.index')->with('error', 'Tidak dapat menghapus kelas karena masih ada siswa di kelas ini!');
        }

        $classRoom->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Data Kelas berhasil dihapus!');
    }
}