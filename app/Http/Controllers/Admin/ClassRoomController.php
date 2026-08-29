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
    return view('admin.classes.index', compact('classes'));
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