<?php
// app/Models/Pasien.php (FIXED - Format No RM)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    
    protected $fillable = [
        'nama',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm'
    ];

    // Auto generate nomor rekam medis saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($pasien) {
            // Cek duplikasi no KTP
            if (static::where('no_ktp', $pasien->no_ktp)->exists()) {
                throw new \Exception('Pasien dengan No KTP ini sudah terdaftar');
            }
            
            // Generate No RM jika belum ada
            if (!$pasien->no_rm) {
                $pasien->no_rm = static::generateNoRM();
            }
        });
    }

    /**
     * Generate nomor rekam medis format: YYYYMM-XXX
     * Contoh: 202411-101
     */
    public static function generateNoRM()
    {
        $prefix = now()->format('Ym'); // 202506
        
        // Hitung jumlah pasien yang sudah terdaftar
        $totalPasien = static::count();
        $newNumber = $totalPasien + 1;
        
        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Relasi ke daftar poli
    public function daftarPoli()
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }

    // Helper: riwayat pemeriksaan yang sudah selesai
    public function riwayatPeriksa()
    {
        return $this->hasManyThrough(
            Periksa::class,
            DaftarPoli::class,
            'id_pasien',
            'id_daftar_poli',
            'id',
            'id'
        );
    }

    // Helper: cek apakah pasien punya antrian hari ini
    public function antrianHariIni()
    {
        return $this->daftarPoli()
                   ->where('tanggal_daftar', now()->format('Y-m-d'))
                   ->where('status', '!=', 'selesai')
                   ->first();
    }
}
