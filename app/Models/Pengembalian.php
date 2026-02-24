<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengembalian extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'peminjaman_id',
        'tanggal_dikembalikan',
        'kondisi_kembali',
        'catatan',
        'foto',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
