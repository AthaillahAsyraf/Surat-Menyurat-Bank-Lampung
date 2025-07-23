<?php
// app/Models/KantorCabang.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KantorCabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kantor',
        'kode_kantor',
        'alamat',
        'no_telp'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function suratTerkirim()
    {
        return $this->hasMany(Surat::class, 'pengirim_id');
    }

    public function suratDibaca()
    {
        return $this->hasMany(SuratRead::class);
    }
}