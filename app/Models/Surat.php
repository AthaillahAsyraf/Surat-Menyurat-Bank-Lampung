<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'perihal',
        'isi_surat',
        'jenis_surat',
        'pengirim_id',
        'file_lampiran',
        'status'
    ];

    public function pengirim()
    {
        return $this->belongsTo(KantorCabang::class, 'pengirim_id');
    }

    public function reads()
    {
        return $this->hasMany(SuratRead::class);
    }

    public function penerimas()
    {
        return $this->hasMany(SuratPenerima::class);
    }

    public function kantorPenerimas()
    {
        return $this->belongsToMany(KantorCabang::class, 'surat_penerimas', 'surat_id', 'kantor_cabang_id');
    }

    public function isReadByKantor($kantorId)
    {
        return $this->reads()->where('kantor_cabang_id', $kantorId)->exists();
    }

    public function isForKantor($kantorId)
    {
        return $this->penerimas()->where('kantor_cabang_id', $kantorId)->exists();
    }
}