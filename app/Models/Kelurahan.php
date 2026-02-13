<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = "kelurahan";
    protected $primaryKey = "KEL_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [];

    // RELATION ------------------------------------------------------------------------------------------------------

    public function provinsi()
    {
        return $this->hasOne(Provinsi::class, "PROV_ID", "PROV_ID");
    }

    public function kota()
    {
        return $this->hasOne(Kota::class, "KOTA_ID", "KOTA_ID");
    }

    public function kecamatan()
    {
        return $this->hasOne(Kecamatan::class, "KEC_ID", "KEC_ID");
    }
}
