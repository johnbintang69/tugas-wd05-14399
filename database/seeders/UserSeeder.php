<?php
// database/seeders/UserSeeder.php (FIXED VERSION)
namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun login untuk semua dokter
        $dokters = Dokter::all();
        foreach ($dokters as $index => $dokter) {
            $email = $this->generateEmailFromName($dokter->nama, 'dokter', $index);
            
            User::create([
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'dokter',
                'entity_id' => $dokter->id
            ]);
        }

        // Buat akun login untuk beberapa pasien (demo)
        $pasiens = Pasien::limit(3)->get(); // Ambil 3 pasien pertama untuk demo
        foreach ($pasiens as $index => $pasien) {
            $email = $this->generateEmailFromName($pasien->nama, 'pasien', $index);
            
            User::create([
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'pasien',
                'entity_id' => $pasien->id
            ]);
        }

        // Buat akun admin
        User::create([
            'email' => 'admin@poliklinik.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'entity_id' => null
        ]);
    }

    /**
     * Generate email dari nama dengan fallback untuk duplikasi
     */
    private function generateEmailFromName($nama, $role, $index = 0)
    {
        // Bersihkan nama dari gelar dokter
        $cleanName = strtolower($nama);
        $cleanName = str_replace(['dr.', 'drg.', 'sp.pd', 'sp.a', 'sp.m', 'sp.og'], '', $cleanName);
        $cleanName = preg_replace('/[^a-zA-Z\s]/', '', $cleanName); // Hapus karakter non-huruf
        $cleanName = trim($cleanName);
        
        // Ambil kata pertama dan kedua jika ada
        $nameParts = explode(' ', $cleanName);
        $firstName = !empty($nameParts[0]) ? $nameParts[0] : 'user';
        $secondName = isset($nameParts[1]) ? $nameParts[1] : '';
        
        // Buat beberapa variasi email untuk menghindari duplikasi
        $emailVariations = [
            $firstName . '@' . $role . '.poliklinik.com',
            $firstName . $secondName . '@' . $role . '.poliklinik.com',
            $firstName . ($index + 1) . '@' . $role . '.poliklinik.com',
            $role . ($index + 1) . '@poliklinik.com'
        ];
        
        // Cari email yang belum ada di database
        foreach ($emailVariations as $email) {
            if (!User::where('email', $email)->exists()) {
                return $email;
            }
        }
        
        // Fallback jika semua variasi sudah ada
        return uniqid($role . '_') . '@poliklinik.com';
    }
}