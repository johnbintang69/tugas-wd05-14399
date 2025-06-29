<?php
// app/Models/Dokter.php (FIXED - Business Logic)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'id_poli'
    ];

    // Relasi ke poli
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    // Relasi ke jadwal periksa
    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    // Relasi ke user (untuk login)
    public function user()
    {
        return $this->hasOne(User::class, 'entity_id')->where('role', 'dokter');
    }

    // Helper: jadwal yang sedang aktif
    public function jadwalAktif()
    {
        return $this->jadwalPeriksa()->where('aktif', true)->first();
    }

    // Helper: daftar pasien yang perlu diperiksa hari ini
    public function pasienHariIni()
    {
        $jadwalAktif = $this->jadwalAktif();
        if (!$jadwalAktif) return collect();

        return $jadwalAktif->daftarPoli()
                          ->where('tanggal_daftar', now()->format('Y-m-d'))
                          ->where('status', '!=', 'selesai')
                          ->orderBy('no_antrian')
                          ->get();
    }

    // Helper: total pasien yang pernah diperiksa
    public function totalPasienDiperiksa()
    {
        return DaftarPoli::whereHas('jadwal', function($q) {
                    $q->where('id_dokter', $this->id);
                })
                ->whereHas('periksa')
                ->count();
    }

    // Business Logic: pastikan hanya 1 jadwal aktif
    public function activateSchedule($jadwalId)
    {
        // Nonaktifkan semua jadwal
        $this->jadwalPeriksa()->update(['aktif' => false]);
        
        // Aktifkan jadwal yang dipilih
        $this->jadwalPeriksa()->where('id', $jadwalId)->update(['aktif' => true]);
    }
}
