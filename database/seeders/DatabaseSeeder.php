<?php
// database/seeders/DatabaseSeeder.php
// Update DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PoliSeeder::class,
            DokterSeeder::class,
            PasienSeeder::class,
            JadwalPeriksaSeeder::class,
            ObatSeeder::class,
            UserSeeder::class, // Setelah dokter & pasien dibuat
            // DemoDataSeeder::class, // Optional - untuk data demo
        ]);
    }
}