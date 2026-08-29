<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // Method untuk menyimpan data guru baru
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:teachers,nip',
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate Akun User untuk Guru
            // Menggunakan NIP sebagai username default, dan 'password123' sebagai password awal
            $user = User::create([
                'name' => $request->name,
                'username' => $request->nip, 
                'password' => Hash::make('password123'), 
                'role' => 'guru',
            ]);

            // 2. Simpan Data Profil Guru
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru dan Akun Login berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}