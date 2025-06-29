<?php
// app/Models/Obat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    
    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga'
    ];

    // Relasi ke detail_periksa
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    // Helper: mendapatkan periksa yang menggunakan obat ini
    public function periksa()
    {
        return $this->belongsToMany(Periksa::class, 'detail_periksa', 'id_obat', 'id_periksa');
    }
}
