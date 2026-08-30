<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Student::with('classRoom')->get();
    }

    public function map(mixed $student): array
    {
        return [
            $student->nisn,
            $student->name,
            $student->classRoom->name ?? '-'
        ];
    }

    public function headings(): array
    {
        return ['NISN', 'NAMA SISWA', 'KELAS'];
    }
}