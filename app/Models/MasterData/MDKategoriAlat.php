<?php

namespace App\Models\MasterData;

use App\Models\Alat;
use Illuminate\Database\Eloquent\Model;

class MDKategoriAlat extends Model
{
    protected $fillable = ['nama_kategori_alat','status'];

    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori_alat_id');
    }
}
