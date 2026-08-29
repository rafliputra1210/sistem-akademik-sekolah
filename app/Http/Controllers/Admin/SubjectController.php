<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name', 'asc')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:subjects,name']);
        Subject::create($request->all());
        return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }
}