<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'kantor_cabang_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function kantorCabang()
    {
        return $this->belongsTo(KantorCabang::class);
    }
}