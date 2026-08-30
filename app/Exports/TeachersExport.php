<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Teacher::select('nip', 'name')->get();
    }

    public function headings(): array
    {
        return ['NIP', 'NAMA GURU'];
    }
}