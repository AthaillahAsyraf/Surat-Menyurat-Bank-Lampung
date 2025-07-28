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
        'sifat_surat',
        'pengirim_id',
        'file_lampiran',
        'status',
        'parent_id'  // Tambahkan ini
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

    // Tambahkan relasi parent
    public function parent()
    {
        return $this->belongsTo(Surat::class, 'parent_id');
    }

    // Tambahkan relasi replies
    public function replies()
    {
        return $this->hasMany(Surat::class, 'parent_id');
    }

    public function isReadByKantor($kantorId)
    {
        return $this->reads()->where('kantor_cabang_id', $kantorId)->exists();
    }

    public function isForKantor($kantorId)
    {
        return $this->penerimas()->where('kantor_cabang_id', $kantorId)->exists();
    }

    public function isReply()
    {
        return $this->parent_id !== null;
    }

    public function isPrivateReply()
    {
        return $this->parent_id !== null;
    }

    public function hasReplies()
    {
        return $this->replies()->exists();
    }

    public function getRepliesCount()
    {
        return $this->replies()->count();
    }

    public function getOriginalRecipient()
    {
        // Untuk surat balasan, ambil pengirim dari surat asli
        if ($this->parent_id) {
            return $this->parent->pengirim_id;
        }
        return null;
    }
}