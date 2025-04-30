<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeriksa extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'detail_periksa';
    
    /**
     * Atribut yang dapat diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_periksa',
        'id_obat',
    ];

    /**
     * Get the periksa record that owns this detail.
     */
    public function periksa(): BelongsTo
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    /**
     * Get the obat record for this detail.
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}