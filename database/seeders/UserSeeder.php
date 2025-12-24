<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun Admin
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@tokotekstil.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Buat akun Staff
        User::create([
            'name' => 'Staff Toko',
            'email' => 'staff@tokotekstil.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
    }
}
