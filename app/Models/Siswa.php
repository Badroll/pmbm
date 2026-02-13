<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = "siswa";
    protected $primaryKey = "SISWA_ID";
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
    ];


    // RELATION ------------------------------------------------------------------------------------------------------

    //
    public function user(){
        return $this->hasOne(User::class, "U_ID", "U_ID");
    }

    //
    public function refStatus(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_STATUS");
    }

    //
    public function refJalur(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_JALUR");
    }

    //
    public function refJenisKelamin(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_JENIS_KELAMIN");
    }

    //
    public function kotaTempatLahir(){
        return $this->hasOne(Kota::class, "KOTA_ID", "SISWA_TEMPAT_LAHIR");
    }

    //
    public function provinsiAlamat(){
        return $this->hasOne(Provinsi::class, "PROV_ID", "SISWA_ALAMAT_PROVINSI");
    }

    //
    public function kotaAlamat(){
        return $this->hasOne(Kota::class, "KOTA_ID", "SISWA_ALAMAT_KOTA");
    }

    //
    public function kecamatanAlamat(){
        return $this->hasOne(Kecamatan::class, "KOTA_ID", "SISWA_ALAMAT_KECAMATAN");
    }

    //
    public function kelurahanAlamat(){
        return $this->hasOne(Kelurahan::class, "KOTA_ID", "SISWA_ALAMAT_KELURAHAN");
    }

    //
    public function refAfirmasi(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_AFIRMASI");
    }

    //
    public function refPrestasiKejuaraan(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_PRESTASI_KEJUARAAN");
    }

    //
    public function refPrestasiKeagamaan(){
        return $this->hasOne(_reference::class, "R_ID", "SISWA_PRESTASI_KEAGAMAAN");
    }


    // CUSTOM ---------------------------------------------------------------------------------------------------------

    public static function getByUserId($userId){
        return self::where("U_ID", $userId)->first();
    }

}
