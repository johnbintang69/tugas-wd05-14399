<?php

// database/seeders/PasienSeeder.php
namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        $pasiens = [
            [
                'nama' => 'Ahmad Fauzi',
                'alamat' => 'Jl. Melati No. 15, Jakarta Timur',
                'no_ktp' => '3171051234567890',
                'no_hp' => '08129876543',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'alamat' => 'Jl. Anggrek No. 28, Bandung',
                'no_ktp' => '3273061234567891',
                'no_hp' => '08139876544',
            ],
            [
                'nama' => 'Budi Hartono',
                'alamat' => 'Jl. Kenanga No. 42, Surabaya',
                'no_ktp' => '3578071234567892',
                'no_hp' => '08149876545',
            ],
            [
                'nama' => 'Maya Sari',
                'alamat' => 'Jl. Tulip No. 56, Medan',
                'no_ktp' => '1271081234567893',
                'no_hp' => '08159876546',
            ],
            [
                'nama' => 'Rizki Pratama',
                'alamat' => 'Jl. Sakura No. 73, Semarang',
                'no_ktp' => '3374091234567894',
                'no_hp' => '08169876547',
            ],
            [
                'nama' => 'Indah Permata',
                'alamat' => 'Jl. Dahlia No. 91, Yogyakarta',
                'no_ktp' => '3404101234567895',
                'no_hp' => '08179876548',
            ]
        ];

        foreach ($pasiens as $pasien) {
            Pasien::create($pasien);
        }
    }
}