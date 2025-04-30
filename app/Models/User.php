<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is a doctor.
     */
    public function isDokter(): bool
    {
        return $this->role === 'dokter';
    }

    /**
     * Check if the user is a patient.
     */
    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }

    /**
     * Get the examinations where this user is the patient.
     */
    public function periksaSebagaiPasien(): HasMany
    {
        return $this->hasMany(Periksa::class, 'id_pasien');
    }

    /**
     * Get the examinations where this user is the doctor.
     */
    public function periksaSebagaiDokter(): HasMany
    {
        return $this->hasMany(Periksa::class, 'id_dokter');
    }
}