<?php

namespace App\Models;

use App\Traits\LogsActivityWithIp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pengembalian extends Model
{
    use SoftDeletes, LogsActivity, LogsActivityWithIp;
    
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
