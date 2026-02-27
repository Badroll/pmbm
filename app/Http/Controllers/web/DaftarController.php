<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;
use App\Models\Provinsi as mProvinsi;
use App\Models\Kota as mKota;
use App\Models\Kecamatan as mKecamatan;
use App\Models\Kelurahan as mKelurahan;

use App\Services\InboxService as mInboxService;

class DaftarController extends Controller
{
    //protected $inboxService;

    public function __construct(){
        //$this->inboxService = new mInboxService();
    }


    public function daftar(Request $request){
        $loginUser = $request->loginUser;
        $req = $request->all();

        $refProvinsi = mProvinsi::all();
        $refKota = mKota::all();
        $refKecamatan = mKecamatan::all();
        $refKelurahan = mKelurahan::all();

        $siswa = mSiswa::getByUserId($loginUser->U_ID);

        $viewData = [
            "refProvinsi" => $refProvinsi,
            "refKota" => $refKota,
            "refKecamatan" => $refKecamatan,
            "refKelurahan" => $refKelurahan,
            "siswa" => $siswa,
        ];
        //dd($viewData);
        return view("daftar", $viewData);
    }
    

    public function doDaftar(Request $request)
    {
        $loginUser = $request->loginUser;

        // VALIDASI
        $validated = $request->validate([
            // file
            'file_pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'file_skl' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        $record = mSiswa::getByUserId($loginUser->U_ID);
        if($record){
            return compose("ERROR", "Anda sudah mendaftar");
        }

        DB::beginTransaction();
        try {
            // =====================
            // UPLOAD FILE
            // =====================
            $filePasFotoPath = null;
            if ($request->hasFile('file_pas_foto')) {
                $file = $request->file('file_pas_foto');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $filePasFotoPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileSklPath = null;
            if ($request->hasFile('file_skl')) {
                $file = $request->file('file_skl');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileSklPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }

            // ======================
            // INSERT
            // ======================
            $unique = false;
            while (!$unique){
                $no = date('YmdHis').rand(1000,9999);
                if(!mSiswa::getByNo($no)){
                    $unique = true;
                }
            }
            $siswa = mSiswa::create([
                'U_ID' => $loginUser->U_ID,
                'SISWA_NO' => $no,
                'SISWA_TGL_DAFTAR' => now(),
                'SISWA_NAMA' => $request->nama_lengkap,
                'SISWA_NISN' => $request->nisn,
                'SISWA_JENIS_KELAMIN' => $request->jenis_kelamin,
                'SISWA_AYAH' => $request->nama_ayah,
                'SISWA_IBU' => $request->nama_ibu,
                'SISWA_TEMPAT_LAHIR' => $request->tempat_lahir,
                'SISWA_TGL_LAHIR' => $request->tanggal_lahir,
                'SISWA_WA' => $request->no_wa,
                'SISWA_JALUR' => $request->jalur_pendaftaran,

                'SISWA_ALAMAT_PROVINSI' => $request->provinsi,
                'SISWA_ALAMAT_KOTA' => $request->kota,
                'SISWA_ALAMAT_KECAMATAN' => $request->kecamatan,
                'SISWA_ALAMAT_KELURAHAN' => $request->kelurahan,
                'SISWA_ALAMAT' => $request->alamat,

                'SISWA_SEKOLAH' => $request->asal_sekolah,
                'SISWA_SEKOLAH_TAHUN_LULUS' => $request->tahun_lulus,
                'SISWA_NILAI_RATA' => $request->nilai_rata,

                'SISWA_NILAI_52_MTK' => $request->nilai_52_mtk,
                'SISWA_NILAI_52_IPA' => $request->nilai_52_ipa,
                'SISWA_NILAI_52_BIND' => $request->nilai_52_bind,
                'SISWA_NILAI_52_PAI' => $request->nilai_52_pai,

                'SISWA_NILAI_61_MTK' => $request->nilai_61_mtk,
                'SISWA_NILAI_61_IPA' => $request->nilai_61_ipa,
                'SISWA_NILAI_61_BIND' => $request->nilai_61_bind,
                'SISWA_NILAI_61_PAI' => $request->nilai_61_pai,

                'SISWA_FILE_FOTO' => $filePasFotoPath,
                'SISWA_FILE_SKL' => $fileSklPath,
            ]);

            DB::commit();
            
            $msg = "";
            $msg .= "Pendaftaran siswa baru berhasil, dengan data sebagai berikut:";
            $msg .= "\n\nNama:";
            $msg .= "\n*".$siswa->SISWA_NAMA."*";
            $msg .= "\n\nNISN:";
            $msg .= "\n*".$siswa->SISWA_NISN."*";
            $msg .= "\n\nJalur:";
            $msg .= "\n*".$siswa->refJalur->R_INFO."*";
            $msg .= "\n\n_silahkan melakukan verifikasi berkas pada tanggal yang sudah ditentukan\nterimakasih._";
            
            mInboxService::send([
                'U_ID' => $siswa->U_ID,
                'jenis' => "success",
                'judul' => 'Pendaftaran Berhasil',
                'isi' => $msg,
                "to" => $siswa->SISWA_WA
            ]);

            return compose("SUCCESS", "Nomor pendaftaran anda #". str_pad($siswa->SISWA_ID, 4, '0', STR_PAD_LEFT));

        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }


}
