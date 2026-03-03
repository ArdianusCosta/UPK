<?php

namespace App\Models\MasterData;

use App\Models\Alat;
use App\Traits\LogsActivityWithIp;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MDKategoriAlat extends Model
{
    use LogsActivity, LogsActivityWithIp;
    
    protected $table = 'm_d_kategori_alats';
    
    protected $fillable = ['nama_kategori_alat','status'];

    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori_alat_id');
    }
}
