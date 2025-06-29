<?php
// app/Models/DaftarPoli.php (FIXED - Queue Management)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model
{
    use HasFactory;

    protected $table = 'daftar_poli';
    
    protected $fillable = [
        'id_pasien',
        'id_jadwal',
        'keluhan',
        'no_antrian',
        'status',
        'tanggal_daftar'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date'
    ];

    // Auto assign nomor antrian dan tanggal
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($daftarPoli) {
            // Set tanggal daftar ke hari ini jika belum diset
            if (!$daftarPoli->tanggal_daftar) {
                $daftarPoli->tanggal_daftar = now()->format('Y-m-d');
            }
            
            // Auto assign nomor antrian
            if (!$daftarPoli->no_antrian) {
                $daftarPoli->no_antrian = $daftarPoli->jadwal->getNextQueueNumber($daftarPoli->tanggal_daftar);
            }
        });
    }

    // Relasi ke pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    // Relasi ke jadwal periksa
    public function jadwal()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    // Relasi ke periksa
    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli');
    }

    // Helper: apakah sudah diperiksa
    public function sudahDiperiksa()
    {
        return $this->periksa()->exists();
    }

    // Helper: mendapatkan estimasi waktu tunggu
    public function estimasiWaktu()
    {
        $antrianSebelum = static::where('id_jadwal', $this->id_jadwal)
                               ->where('tanggal_daftar', $this->tanggal_daftar)
                               ->where('no_antrian', '<', $this->no_antrian)
                               ->where('status', '!=', 'selesai')
                               ->count();
        
        // Asumsi 15 menit per pasien
        $estimasiMenit = $antrianSebelum * 15;
        $waktuMulai = $this->jadwal->jam_mulai;
        
        return $waktuMulai->addMinutes($estimasiMenit);
    }

    // Helper: status badge untuk UI
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu' => 'badge-warning',
            'sedang_diperiksa' => 'badge-info', 
            'selesai' => 'badge-success',
            'batal' => 'badge-danger'
        ];
        
        return $badges[$this->status] ?? 'badge-secondary';
    }
}