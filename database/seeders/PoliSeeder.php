<?php

// database/seeders/PoliSeeder.php
namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        $polikliniks = [
            [
                'nama_poli' => 'Umum',
                'keterangan' => 'Poliklinik untuk pemeriksaan kesehatan umum, konsultasi medis dasar, dan rujukan ke spesialis.'
            ],
            [
                'nama_poli' => 'Gigi',
                'keterangan' => 'Poliklinik khusus untuk perawatan dan pemeriksaan kesehatan gigi dan mulut.'
            ],
            [
                'nama_poli' => 'Anak',
                'keterangan' => 'Poliklinik khusus untuk pemeriksaan dan perawatan kesehatan bayi, anak, dan remaja.'
            ],
            [
                'nama_poli' => 'Mata',
                'keterangan' => 'Poliklinik untuk pemeriksaan dan perawatan penyakit mata serta gangguan penglihatan.'
            ],
            [
                'nama_poli' => 'Kandungan',
                'keterangan' => 'Poliklinik untuk pemeriksaan kehamilan, persalinan, dan kesehatan reproduksi wanita.'
            ]
        ];

        foreach ($polikliniks as $poli) {
            Poli::create($poli);
        }
    }
}