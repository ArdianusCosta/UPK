<?php

namespace App\Models;

use App\Models\MasterData\MDKategoriAlat;
use App\Traits\LogsActivityWithIp;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Alat extends Model
{
    use LogsActivity, LogsActivityWithIp;

    protected $table = 'alats';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_alat_id',
        'foto',
        'stok',
        'status',
    ];

    public function kategoriAlat()
    {
        return $this->belongsTo(MDKategoriAlat::class, 'kategori_alat_id');
    }

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }

        return url('uploads/alat/' . $this->foto);
    }
}