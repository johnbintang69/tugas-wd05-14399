<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'obat';
    
    /**
     * Atribut yang dapat diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
    ];

    /**
     * Get the detail periksa records that include this medication.
     */
    public function detailPeriksa(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    /**
     * Get all examinations that prescribed this medication.
     */
    public function periksa()
    {
        return $this->belongsToMany(Periksa::class, 'detail_periksa', 'id_obat', 'id_periksa');
    }
}