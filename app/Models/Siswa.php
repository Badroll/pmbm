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
        return $this->hasOne(Kecamatan::class, "KEC_ID", "SISWA_ALAMAT_KECAMATAN");
    }

    //
    public function kelurahanAlamat(){
        return $this->hasOne(Kelurahan::class, "KEL_ID", "SISWA_ALAMAT_KELURAHAN");
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

    public static function getByNo($no){
        return self::where("SISWA_NO", $no)->first();
    }

    public function hitungSkor()
    {
        $siswa = $this;

        /* ======================
        * A : Rata-rata nilai rapor
        * ====================== */
        $nilai = [
            $siswa->SISWA_NILAI_52_MTK,
            $siswa->SISWA_NILAI_52_IPA,
            $siswa->SISWA_NILAI_52_BIND,
            $siswa->SISWA_NILAI_52_PAI,
            $siswa->SISWA_NILAI_61_MTK,
            $siswa->SISWA_NILAI_61_IPA,
            $siswa->SISWA_NILAI_61_BIND,
            $siswa->SISWA_NILAI_61_PAI
        ];

        $A = array_sum($nilai) / count($nilai);

        /* ======================
        * B C D
        * ====================== */
        $B = $siswa->SISWA_TES_CBT_AKADEMIK;
        $C = $siswa->SISWA_TES_CBT_PSIKO;
        $D = $siswa->SISWA_TES_QURAN;

        /* ======================
        * E F G (skor khusus)
        * ====================== */
        $E = skorKhusus($siswa->SISWA_AFIRMASI);
        $F = skorKhusus($siswa->SISWA_PRESTASI_KEJUARAAN);
        $G = skorKhusus($siswa->SISWA_PRESTASI_KEAGAMAAN);

        /* ======================
        * H : Umur
        * ====================== */
        $H = \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR)->age;

        /* ======================
        * HITUNG SKOR
        * ====================== */
        $skor = $A 
            + (3 * $B)
            + (2 * $C)
            + (2 * $D)
            + (2 * $E)
            + (3 * ($F + $G))
            + $H;

        $this->SISWA_SKOR = $skor;
        $this->save();

        return $skor;
    }

}
