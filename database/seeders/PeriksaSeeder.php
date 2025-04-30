<?php

namespace Database\Seeders;

use App\Models\DetailPeriksa;
use App\Models\Periksa;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pemeriksaan yang sudah selesai
        $periksa1 = Periksa::create([
            'id_pasien' => 3, // Citra Dewi
            'id_dokter' => 1, // Dr. Andi Pratama
            'tgl_periksa' => Carbon::now()->subDays(5),
            'status' => 'done',
            'keluhan' => 'Keluhan: Demam dan sakit kepala.',
            'catatan_dokter' => ' Diagnosis: Demam virus.',
            'biaya_periksa' => 185000, // 150000 + 35000 (obat)
        ]);

        // Tambahkan obat untuk periksa1
        DetailPeriksa::create([
            'id_periksa' => $periksa1->id,
            'id_obat' => 1, // Paracetamol
        ]);
        
        DetailPeriksa::create([
            'id_periksa' => $periksa1->id,
            'id_obat' => 7, // Vitamin C
        ]);

        // Pemeriksaan yang sudah selesai 2
        $periksa2 = Periksa::create([
            'id_pasien' => 4, // Deni Kurniawan
            'id_dokter' => 2, // Dr. Budi Santoso
            'tgl_periksa' => Carbon::now()->subDays(3),
            'status' => 'done',
            'keluhan' => 'Keluhan: Batuk pilek dan demam ringan.',
            'catatan_dokter' => ' Diagnosis: Infeksi saluran pernapasan atas.',
            'biaya_periksa' => 198000, // 150000 + 48000 (obat)
        ]);

        // Tambahkan obat untuk periksa2
        DetailPeriksa::create([
            'id_periksa' => $periksa2->id,
            'id_obat' => 1, // Paracetamol
        ]);
        
        DetailPeriksa::create([
            'id_periksa' => $periksa2->id,
            'id_obat' => 2, // Amoxicillin
        ]);
        
        DetailPeriksa::create([
            'id_periksa' => $periksa2->id,
            'id_obat' => 6, // Cetirizine
        ]);

        // Pemeriksaan yang belum selesai (menunggu dokter)
        Periksa::create([
            'id_pasien' => 5, // Eka Putri
            'id_dokter' => 1, // Dr. Andi Pratama
            'tgl_periksa' => Carbon::now()->addHours(2),
            'status' => 'pending',
            'keluhan' => 'Keluhan: Sakit perut bagian bawah dan mual sejak 2 hari yang lalu.',
            'catatan_dokter' => 'pasien di diagnosa : diare',
            'biaya_periksa' => 0, // Belum diperiksa
        ]);

        // Pemeriksaan yang belum selesai (menunggu dokter)
        Periksa::create([
            'id_pasien' => 3, // Citra Dewi
            'id_dokter' => 2, // Dr. Budi Santoso
            'tgl_periksa' => Carbon::now()->addDays(1),
            'status' => 'pending',
            'keluhan' => 'Keluhan: Nyeri sendi dan otot, terutama di pagi hari.',
            'catatan_dokter' => 'pasien di diagnosa : sakit sendi',
            'biaya_periksa' => 0, // Belum diperiksa
        ]);
    }
}