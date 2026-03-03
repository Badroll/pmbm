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
            'file_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'file_nisn' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_rapor_52' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_rapor_61' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
            $fileFotoPath = "";
            if ($request->hasFile('file_foto')) {
                $file = $request->file('file_foto');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileFotoPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileNisnPath = "";
            if ($request->hasFile('file_nisn')) {
                $file = $request->file('file_nisn');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileNisnPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileKKPath = "";
            if ($request->hasFile('file_kk')) {
                $file = $request->file('file_kk');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileKKPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileAktaPath = "";
            if ($request->hasFile('file_akta')) {
                $file = $request->file('file_akta');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileAktaPath = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileRapor52Path = "";
            if ($request->hasFile('file_rapor_52')) {
                $file = $request->file('file_rapor_52');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileRapor52Path = $file->storeAs(
                    'siswa',
                    $filename,
                    'public'
                );
            }
            $fileRapor61Path = "";
            if ($request->hasFile('file_rapor_61')) {
                $file = $request->file('file_rapor_61');
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $fileRapor61Path = $file->storeAs(
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

                'SISWA_FILE_FOTO' => $fileFotoPath,
                'SISWA_FILE_NISN' => $fileNisnPath,
                'SISWA_FILE_KK' => $fileKKPath,
                'SISWA_FILE_AKTA' => $fileAktaPath,
                'SISWA_FILE_RAPOR_52' => $fileRapor52Path,
                'SISWA_FILE_RAPOR_61' => $fileRapor61Path,
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


    public function siswa(Request $request)
    {
        $loginUser = $request->loginUser;

        if (!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_PMBM"])) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $query = mSiswa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('SISWA_NAMA', 'like', "%{$search}%")
                ->orWhere('SISWA_NISN', 'like', "%{$search}%");
                if (ctype_digit($search)) {
                    $q->orWhere('SISWA_ID', (int) $search);
                }
            });
        }

        if ($request->filled('jalur') && $request->jalur !== '_ALL_') {
            $query->where('SISWA_JALUR', $request->jalur);
        }

        if ($request->filled('status') && $request->status !== '_ALL_') {
            $query->where('SISWA_STATUS', $request->status);
        }

        $siswa = $query->orderBy('SISWA_TGL_DAFTAR', 'desc')->get();

        return view("siswa", [
            "siswa" => $siswa,
        ]);
    }

    public function siswaDetail(Request $request, $siswaId){
        $loginUser = $request->loginUser;
        $req = $request->all();

        if(!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_PMBM"])){
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $siswa = mSiswa::find($siswaId);

        $viewData = [
            "siswa" => $siswa,
        ];
        //dd($viewData);
        return view("siswa-detail", $viewData);
    }


}
