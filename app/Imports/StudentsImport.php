<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StudentsImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nisn = $row['nisn'] ?? $row['nis'] ?? $row['no_induk'] ?? null;
            $name = $row['nama_siswa'] ?? $row['nama_lengkap'] ?? $row['nama'] ?? $row['name'] ?? null;
            $classInput = $row['kelas'] ?? $row['nama_kelas'] ?? $row['class'] ?? $row['class_id'] ?? $row['class_room_id'] ?? $row['id_kelas'] ?? null;

            if (!$nisn || !$name) {
                continue;
            }

            $classRoomId = null;
            if ($classInput !== null && $classInput !== '') {
                $classRoom = ClassRoom::where('name', (string)$classInput)
                    ->orWhere('id', $classInput)
                    ->first();

                if (!$classRoom) {
                    $classRoom = ClassRoom::create(['name' => (string)$classInput]);
                }
                $classRoomId = $classRoom->id;
            }

            // Jika kelas tidak diisi, gunakan kelas pertama yang tersedia atau buat default
            if (!$classRoomId) {
                $defaultClass = ClassRoom::first() ?? ClassRoom::create(['name' => '10']);
                $classRoomId = $defaultClass->id;
            }

            Student::updateOrCreate(
                ['nisn' => (string)$nisn],
                [
                    'name' => (string)$name,
                    'class_room_id' => $classRoomId,
                ]
            );
        }
    }
}