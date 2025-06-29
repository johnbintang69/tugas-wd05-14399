<?php

// database/seeders/DemoDataSeeder.php (Optional - untuk data demo)
namespace Database\Seeders;

use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Demo data daftar poli (beberapa sudah diperiksa, beberapa belum)
        $daftarPolis = [
            [
                'id_pasien' => 1,
                'id_jadwal' => 1, // Dr. Andi - Senin pagi
                'keluhan' => 'Demam tinggi dan sakit kepala sejak 3 hari',
                'tanggal_daftar' => Carbon::now()->subDays(3),
                'status' => 'selesai'
            ],
            [
                'id_pasien' => 2,
                'id_jadwal' => 4, // Dr. Budi - Selasa pagi (Anak)
                'keluhan' => 'Anak rewel, demam, dan tidak mau makan',
                'tanggal_daftar' => Carbon::now()->subDays(2),
                'status' => 'selesai'
            ],
            [
                'id_pasien' => 3,
                'id_jadwal' => 7, // drg. Citra - Senin sore (Gigi)
                'keluhan' => 'Sakit gigi di bagian kiri bawah',
                'tanggal_daftar' => Carbon::now()->subDays(1),
                'status' => 'menunggu'
            ],
            [
                'id_pasien' => 4,
                'id_jadwal' => 2, // Dr. Andi - Rabu pagi
                'keluhan' => 'Batuk pilek berkepanjangan dan sesak napas',
                'tanggal_daftar' => Carbon::today(),
                'status' => 'menunggu'
            ]
        ];

        foreach ($daftarPolis as $index => $data) {
            $daftarPoli = DaftarPoli::create($data);
            
            // Buat data periksa untuk yang statusnya selesai
            if ($data['status'] === 'selesai') {
                $periksa = Periksa::create([
                    'id_daftar_poli' => $daftarPoli->id,
                    'tgl_periksa' => $data['tanggal_daftar'],
                    'catatan' => $index === 0 
                        ? 'Diagnosis: Demam virus. Istirahat cukup, minum air putih banyak.'
                        : 'Diagnosis: ISPA. Berikan obat sesuai dosis, kontrol jika tidak membaik.',
                    'biaya_periksa' => 150000
                ]);

                // Tambahkan resep obat
                if ($index === 0) {
                    // Pasien dewasa
                    DetailPeriksa::create(['id_periksa' => $periksa->id, 'id_obat' => 1]); // Paracetamol
                    DetailPeriksa::create(['id_periksa' => $periksa->id, 'id_obat' => 7]); // Vitamin C
                } else {
                    // Pasien anak
                    DetailPeriksa::create(['id_periksa' => $periksa->id, 'id_obat' => 1]); // Paracetamol
                    DetailPeriksa::create(['id_periksa' => $periksa->id, 'id_obat' => 2]); // Amoxicillin
                    DetailPeriksa::create(['id_periksa' => $periksa->id, 'id_obat' => 6]); // Cetirizine
                }
            }
        }
    }
}