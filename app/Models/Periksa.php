<?php
// app/Models/Periksa.php (FIXED - Auto Calculate Biaya)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    use HasFactory;

    protected $table = 'periksa';
    
    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa'
    ];

    protected $casts = [
        'tgl_periksa' => 'date'
    ];

    const BIAYA_JASA_DOKTER = 150000; // Rp 150.000

    // Auto calculate biaya dan update status
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($periksa) {
            // Set tanggal periksa ke hari ini jika belum diset
            if (!$periksa->tgl_periksa) {
                $periksa->tgl_periksa = now()->format('Y-m-d');
            }
        });

        static::created(function ($periksa) {
            // Update status daftar poli menjadi selesai
            $periksa->daftarPoli->update(['status' => 'selesai']);
            
            // Hitung ulang biaya setelah obat ditambahkan
            $periksa->recalculateBiaya();
        });
    }

    // Relasi ke daftar poli
    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    // Relasi ke detail periksa
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    // Helper: mendapatkan obat yang diresepkan
    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'detail_periksa', 'id_periksa', 'id_obat');
    }

    // Helper: mendapatkan pasien
    public function pasien()
    {
        return $this->daftarPoli->pasien;
    }

    // Helper: mendapatkan dokter  
    public function dokter()
    {
        return $this->daftarPoli->jadwal->dokter;
    }

    // Business Logic: menghitung total biaya obat
    public function totalBiayaObat()
    {
        return $this->obat->sum('harga');
    }

    // Business Logic: hitung ulang biaya periksa
    public function recalculateBiaya()
    {
        $totalBiayaObat = $this->totalBiayaObat();
        $totalBiaya = self::BIAYA_JASA_DOKTER + $totalBiayaObat;
        
        $this->update(['biaya_periksa' => $totalBiaya]);
        
        return $totalBiaya;
    }

    // Helper: breakdown biaya
    public function getBreakdownBiaya()
    {
        return [
            'biaya_jasa_dokter' => self::BIAYA_JASA_DOKTER,
            'biaya_obat' => $this->totalBiayaObat(),
            'total' => $this->biaya_periksa
        ];
    }

    // Helper: format biaya untuk display
    public function getFormattedBiayaAttribute()
    {
        return 'Rp ' . number_format($this->biaya_periksa, 0, ',', '.');
    }
}
