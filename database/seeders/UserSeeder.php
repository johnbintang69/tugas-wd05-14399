<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user dokter
        User::create([
            'nama' => 'Dr. Andi Pratama',
            'alamat' => 'Jl. Kesehatan No. 123, Jakarta',
            'no_hp' => '08123456789',
            'email' => 'dr.andi@example.com',
            'role' => 'dokter',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'nama' => 'Dr. Budi Santoso',
            'alamat' => 'Jl. Medis No. 45, Bandung',
            'no_hp' => '08234567890',
            'email' => 'dr.budi@example.com',
            'role' => 'dokter',
            'password' => Hash::make('password123'),
        ]);

        // Buat user pasien
        User::create([
            'nama' => 'Citra Dewi',
            'alamat' => 'Jl. Sehat No. 67, Surabaya',
            'no_hp' => '08345678901',
            'email' => 'citra@example.com',
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'nama' => 'Deni Kurniawan',
            'alamat' => 'Jl. Sentosa No. 89, Medan',
            'no_hp' => '08456789012',
            'email' => 'deni@example.com',
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'nama' => 'Eka Putri',
            'alamat' => 'Jl. Mawar No. 12, Semarang',
            'no_hp' => '08567890123',
            'email' => 'eka@example.com',
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);
    }
}