<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin utama
        User::create([
            'name' => 'Administrator',
            'username' => 'admin_utama',
            'password' => Hash::make('password123'), // Default password
            'role' => 'admin', //
        ]);
    }
}