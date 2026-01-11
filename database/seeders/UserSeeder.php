<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                // IDENTITAS UNIK ADMIN
                'email' => 'veenmoda@gmail.com',
            ],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('veen80'),
                'role' => 'admin',
            ]
        );
    }
}