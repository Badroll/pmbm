<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = "kota";
    protected $primaryKey = "KOTA_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [];

    // RELATION ------------------------------------------------------------------------------------------------------

    public function provinsi()
    {
        return $this->hasOne(Provinsi::class, "PROV_ID", "PROV_ID");
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, "KOTA_ID", "KOTA_ID");
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, "KOTA_ID", "KOTA_ID");
    }
}
