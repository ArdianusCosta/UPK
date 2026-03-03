<?php

namespace App\Models;

use App\Traits\LogsActivityWithIp;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Peminjaman extends Model
{
    use LogsActivity, LogsActivityWithIp;

    protected $table = 'peminjamen';

    protected $fillable = [
        'peminjam_id',
        'petugas_id',
        'alat_id',
        'kode',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    //logika untuk codenya karna nanti aku mau di generate otomatis
    protected static function boot()
    {
        parent::boot();

        static::created(function ($peminjaman) {
            $peminjaman->update([
                'kode' => 'PINJAM-' . str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT)
            ]);
        });
    }
}
