<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = "provinsi";
    protected $primaryKey = "PROV_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [];

    // RELATION ------------------------------------------------------------------------------------------------------

    public function kota()
    {
        return $this->hasMany(Kota::class, "PROV_ID", "PROV_ID");
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, "PROV_ID", "PROV_ID");
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, "PROV_ID", "PROV_ID");
    }
    
}
