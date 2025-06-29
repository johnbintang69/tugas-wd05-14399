<?php

// database/seeders/JadwalPeriksaSeeder.php
namespace Database\Seeders;

use App\Models\JadwalPeriksa;
use Illuminate\Database\Seeder;

class JadwalPeriksaSeeder extends Seeder
{
    public function run(): void
    {
        $jadwals = [
            // Dr. Andi Pratama (Umum)
            ['id_dokter' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ['id_dokter' => 1, 'hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ['id_dokter' => 1, 'hari' => 'Jumat', 'jam_mulai' => '14:00', 'jam_selesai' => '17:00'],
            
            // Dr. Budi Santoso (Anak)
            ['id_dokter' => 2, 'hari' => 'Selasa', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
            ['id_dokter' => 2, 'hari' => 'Kamis', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
            ['id_dokter' => 2, 'hari' => 'Sabtu', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
            
            // drg. Citra Dewi (Gigi)
            ['id_dokter' => 3, 'hari' => 'Senin', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
            ['id_dokter' => 3, 'hari' => 'Selasa', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
            ['id_dokter' => 3, 'hari' => 'Kamis', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
            
            // Dr. Deni Kurniawan (Mata)
            ['id_dokter' => 4, 'hari' => 'Rabu', 'jam_mulai' => '10:00', 'jam_selesai' => '14:00'],
            ['id_dokter' => 4, 'hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            
            // Dr. Eka Putri (Kandungan)
            ['id_dokter' => 5, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ['id_dokter' => 5, 'hari' => 'Rabu', 'jam_mulai' => '14:00', 'jam_selesai' => '17:00'],
            ['id_dokter' => 5, 'hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            
            // Dr. Fajar Ramadhan (Umum)
            ['id_dokter' => 6, 'hari' => 'Selasa', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
            ['id_dokter' => 6, 'hari' => 'Kamis', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
            ['id_dokter' => 6, 'hari' => 'Sabtu', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
        ];

        foreach ($jadwals as $jadwal) {
            JadwalPeriksa::create($jadwal);
        }
    }
}
