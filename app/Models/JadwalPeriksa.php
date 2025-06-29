<?php
// app/Models/JadwalPeriksa.php (FIXED - Business Logic)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPeriksa extends Model
{
    use HasFactory;

    protected $table = 'jadwal_periksa';
    
    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'aktif'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'aktif' => 'boolean'
    ];

    // Business Logic: Validasi sebelum save
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($jadwal) {
            // Validasi tidak boleh bentrok dengan jadwal lain dokter yang sama
            $bentrok = static::where('id_dokter', $jadwal->id_dokter)
                            ->where('hari', $jadwal->hari)
                            ->where(function($query) use ($jadwal) {
                                $query->whereBetween('jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                                      ->orWhereBetween('jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                                      ->orWhere(function($q) use ($jadwal) {
                                          $q->where('jam_mulai', '<=', $jadwal->jam_mulai)
                                            ->where('jam_selesai', '>=', $jadwal->jam_selesai);
                                      });
                            })
                            ->exists();
            
            if ($bentrok) {
                throw new \Exception('Jadwal bentrok dengan jadwal lain di hari yang sama');
            }
        });
        
        static::updating(function ($jadwal) {
            // Tidak boleh ubah jadwal di hari H
            $today = now()->locale('id')->dayName;
            if ($jadwal->hari === $today) {
                throw new \Exception('Tidak boleh mengubah jadwal di hari H');
            }
        });
    }

    // Relasi ke dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    // Relasi ke daftar poli
    public function daftarPoli()
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }

    // Helper: set jadwal sebagai aktif (nonaktifkan yang lain)
    public function setAsActive()
    {
        // Nonaktifkan semua jadwal dokter ini
        static::where('id_dokter', $this->id_dokter)->update(['aktif' => false]);
        
        // Aktifkan jadwal ini
        $this->update(['aktif' => true]);
    }

    // Helper: mendapatkan nomor antrian berikutnya untuk tanggal tertentu
    public function getNextQueueNumber($date)
    {
        $lastQueue = $this->daftarPoli()
                          ->where('tanggal_daftar', $date)
                          ->max('no_antrian');
        
        return ($lastQueue ?? 0) + 1;
    }

    // Helper: apakah jadwal tersedia di tanggal tertentu
    public function isAvailableOn($date)
    {
        $dayName = Carbon::parse($date)->locale('id')->dayName;
        return $this->hari === $dayName && $this->aktif;
    }

    // Helper: jumlah pasien yang sudah daftar hari ini
    public function jumlahAntrianHariIni()
    {
        return $this->daftarPoli()
                   ->where('tanggal_daftar', now()->format('Y-m-d'))
                   ->count();
    }
}