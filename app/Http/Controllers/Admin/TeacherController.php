<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersExport;
use App\Imports\TeachersImport;

class TeacherController extends Controller
{
    public function export()
    {
        return Excel::download(new TeachersExport, 'data_guru.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);
        Excel::import(new TeachersImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data Guru berhasil diimpor!');
    }
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
            'nip'     => 'required|string|max:50|unique:teachers,nip',
            'name'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate Akun User untuk Guru
            // Menggunakan NIP sebagai username default, dan 'password123' sebagai password awal
            $user = User::create([
                'name'     => $request->name,
                'username' => $request->nip, 
                'password' => Hash::make('password123'), 
                'role'     => 'guru',
            ]);

            // 2. Simpan Data Profil Guru
            Teacher::create([
                'user_id' => $user->id,
                'nip'     => $request->nip,
                'name'    => $request->name,
                'jabatan' => $request->jabatan,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru dan Akun Login berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat data guru: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data guru. Silakan coba kembali.');
        }
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'nip'     => ['required', 'string', 'max:50', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'name'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            if ($teacher->user) {
                $teacher->user->update([
                    'name'     => $request->name,
                    'username' => $request->nip,
                ]);
            }

            $teacher->update([
                'nip'     => $request->nip,
                'name'    => $request->name,
                'jabatan' => $request->jabatan,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data guru ID ' . $teacher->id . ': ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data guru.');
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();
            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus data guru ID ' . $teacher->id . ': ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data guru.');
        }
    }
}