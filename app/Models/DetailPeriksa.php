<?php
// app/Models/DetailPeriksa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeriksa extends Model
{
    use HasFactory;

    protected $table = 'detail_periksa';
    
    protected $fillable = [
        'id_periksa',
        'id_obat'
    ];

    // Relasi ke periksa
    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    // Relasi ke obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}