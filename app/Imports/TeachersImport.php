<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeachersImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nip = $row['nip'] ?? $row['username'] ?? $row['nip_username'] ?? $row['nip_guru'] ?? null;
            $name = $row['nama_guru'] ?? $row['nama_lengkap'] ?? $row['nama'] ?? $row['name'] ?? null;

            if (!$nip || !$name) {
                continue;
            }

            DB::transaction(function () use ($nip, $name) {
                // 1. Buat atau cari User
                $user = User::firstOrCreate(
                    ['username' => (string)$nip],
                    [
                        'name' => (string)$name,
                        'password' => Hash::make('password123'),
                        'role' => 'guru'
                    ]
                );
                
                // Pastikan nama user terupdate
                $user->update(['name' => (string)$name]);

                // 2. Buat atau perbarui Teacher
                Teacher::updateOrCreate(
                    ['nip' => (string)$nip],
                    ['user_id' => $user->id, 'name' => (string)$name]
                );
            });
        }
    }
}