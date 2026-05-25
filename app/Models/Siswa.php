<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

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

    //
    public function pengerjaan(){
        return $this->hasMany(Pengerjaan::class, "SISWA_ID", "SISWA_ID");
    }

    // daftar ulang
    public function siswaDatar(){
        return $this->hasOne(SiswaDaftar::class, "SISWA_ID", "SISWA_ID");
    }


    // CUSTOM ---------------------------------------------------------------------------------------------------------

    public static function getByUserId($userId){
        return self::where("U_ID", $userId)->first();
    }

    public static function getByNo($no){
        return self::where("SISWA_NO", $no)->first();
    }

    public static function getByNISN($no){
        return self::where("SISWA_NISN", $no)->first();
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
        $H = number_format(
            Carbon::parse($siswa->SISWA_TGL_LAHIR)
            ->diffInDays(Carbon::create(2026, 7, 1)) / 365.25,
            2
        );

        /* ======================
        * HITUNG SKOR
        * ====================== */

        $isAfirmasi = ($siswa->SISWA_JALUR === 'JALUR_AFIRMASI');
        $isPrestasi = ($siswa->SISWA_JALUR === 'JALUR_PRESTASI');

        $poin = [
            ["A - rata-rata nilai rapot", $A, 1],
            ["B - CBT, Tes akademik", $B, 3],
            ["C - CBT, Psikotest", $C, 2],
            ["D - Skor tes baca Al Quran", $D, 2],
            ["E - Skor afirmasi", $isAfirmasi ? $E : 0, 2],
            ["F - Nilai prestasi kejuaraan", $isPrestasi ? $F : 0, 3],
            ["G - Nilai prestasi keagamaan (tahfidz)", $isPrestasi ? $G : 0, 3],
            ["H - Umur calon murid baru", $H, 1],
        ];

        // $poin = [
        //     ["A - rata-rata nilai rapot", $A, 1],
        //     ["B - CBT, Tes akademik", $B, 3],
        //     ["C - CBT, Psikotest", $C, 2],
        //     ["D - Skor tes baca Al Quran", $D, 2],
        // ];

        // /* ======================
        // * POIN KHUSUS BERDASARKAN JALUR
        // * ====================== */
        // if ($siswa->SISWA_JALUR === 'JALUR_AFIRMASI') {
        //     $E = skorKhusus($siswa->SISWA_AFIRMASI);

        //     $poin[] = ["E - Skor afirmasi", $E, 2];
        // }

        // if ($siswa->SISWA_JALUR === 'JALUR_PRESTASI') {
        //     $F = skorKhusus($siswa->SISWA_PRESTASI_KEJUARAAN);
        //     $G = skorKhusus($siswa->SISWA_PRESTASI_KEAGAMAAN);

        //     $poin[] = ["F - Nilai prestasi kejuaraan", $F, 3];
        //     $poin[] = ["G - Nilai prestasi keagamaan (tahfidz)", $G, 3];
        // }

        // /* ======================
        // * H : Umur (selalu ada)
        // * ====================== */
        // $H = number_format(
        //     Carbon::parse($siswa->SISWA_TGL_LAHIR)
        //         ->diffInDays(Carbon::create(2026, 7, 1)) / 365.25,
        //     2
        // );

        // $poin[] = ["H - Umur calon murid baru", $H, 1];

        $skor = 0;
        foreach ($poin as $item) {
            $skor += $item[1] * $item[2];
        }

        // update if has any change
        // logcmd($this->SISWA_SKOR);
        // logcmd($skor);
        if($this->SISWA_SKOR != $skor){
            //logcmd("diff");
            $this->SISWA_SKOR = $skor;
            if($this->ranking){
                unset($this->ranking);
            }
            //logcmd($this->toArray());
            $this->save();
        }
        //logcmd("============");

        return [
            "POIN" => $poin,
            "TOTAL" => $skor
        ];
    }

}
