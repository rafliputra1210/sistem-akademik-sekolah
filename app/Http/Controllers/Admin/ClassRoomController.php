<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;

class ClassRoomController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:class_rooms,name']);
        ClassRoom::create($request->all());
        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }
}