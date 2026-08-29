<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:subjects,name']);
        Subject::create($request->all());
        return redirect()->back()->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }
}