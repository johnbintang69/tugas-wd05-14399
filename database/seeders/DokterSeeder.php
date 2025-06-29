<?php

namespace Database\Seeders;

use App\Models\Dokter;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $dokters = [
            [
                'nama' => 'Dr. Andi Pratama, Sp.PD',
                'alamat' => 'Jl. Kesehatan No. 123, Jakarta Pusat',
                'no_hp' => '08123456789',
                'id_poli' => 1, // Umum
            ],
            [
                'nama' => 'Dr. Budi Santoso, Sp.A',
                'alamat' => 'Jl. Medis No. 45, Bandung',
                'no_hp' => '08234567890',
                'id_poli' => 3, // Anak
            ],
            [
                'nama' => 'drg. Citra Dewi',
                'alamat' => 'Jl. Sehat No. 67, Surabaya',
                'no_hp' => '08345678901',
                'id_poli' => 2, // Gigi
            ],
            [
                'nama' => 'Dr. Deni Kurniawan, Sp.M',
                'alamat' => 'Jl. Sentosa No. 89, Medan',
                'no_hp' => '08456789012',
                'id_poli' => 4, // Mata
            ],
            [
                'nama' => 'Dr. Eka Putri, Sp.OG',
                'alamat' => 'Jl. Mawar No. 12, Semarang',
                'no_hp' => '08567890123',
                'id_poli' => 5, // Kandungan
            ],
            [
                'nama' => 'Dr. Fajar Ramadhan, Sp.PD',
                'alamat' => 'Jl. Harmoni No. 34, Yogyakarta',
                'no_hp' => '08678901234',
                'id_poli' => 1, // Umum
            ]
        ];

        foreach ($dokters as $dokter) {
            Dokter::create($dokter);
        }
    }
}