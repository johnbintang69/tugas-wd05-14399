<?php
// app/Models/User.php (Updated)
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password', 
        'role',
        'entity_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi ke dokter jika role = dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'entity_id');
    }

    // Relasi ke pasien jika role = pasien  
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'entity_id');
    }

    // Helper untuk mendapatkan nama
    public function getNamaAttribute()
    {
        if ($this->role === 'dokter' && $this->dokter) {
            return $this->dokter->nama;
        } elseif ($this->role === 'pasien' && $this->pasien) {
            return $this->pasien->nama;
        }
        return 'Unknown';
    }

    // Helper untuk mendapatkan entitas utama (dokter/pasien)
    public function getEntityAttribute()
    {
        if ($this->role === 'dokter') {
            return $this->dokter;
        } elseif ($this->role === 'pasien') {
            return $this->pasien;
        }
        return null;
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
