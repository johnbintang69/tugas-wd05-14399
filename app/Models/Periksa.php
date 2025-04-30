<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periksa extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'periksa';
    
    /**
     * Atribut yang dapat diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pasien',
        'id_dokter',
        'tgl_periksa',
        'keluhan',
        'catatan_dokter',
        'status',
        'biaya_periksa',
    ];

    /**
     * Get the user that is the patient in this examination.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    /**
     * Get the user that is the doctor in this examination.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    /**
     * Get the detail periksa records associated with this examination.
     */
    public function detailPeriksa(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    /**
     * Get all medications prescribed in this examination.
     */
    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'detail_periksa', 'id_periksa', 'id_obat');
    }
}