<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = "kecamatan";
    protected $primaryKey = "KEC_ID";
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

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, "KEC_ID", "KEC_ID");
    }
}
