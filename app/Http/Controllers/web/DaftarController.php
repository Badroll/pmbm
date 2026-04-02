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
    protected $inboxService;

    public function __construct(){
        $this->inboxService = new mInboxService();
    }


    public function daftar(Request $request){
        $loginUser = $request->loginUser;
        $req = $request->all();

        $refProvinsi = mProvinsi::all();
        $refKota = mKota::all();
        $refKecamatan = mKecamatan::all();
        $refKelurahan = mKelurahan::all();

        $siswa = mSiswa::getByUserId($loginUser->U_ID);
        if(!in_array($loginUser->U_ROLE, ["ROLE_SISWA"])) {
            $userId = $req->userId ?? null;
            if($userId){
                $siswa = mSiswa::getByUserId($userId);
            }
        }

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
            'tanggal_lahir' => 'required|date|after_or_equal:2011-07-01',

            // file
            'file_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        //return compose("ERROR", "NISN ". $request->nisn ." sudah terdaftar");
        if(mSiswa::getByNISN($request->nisn)){
            return compose("ERROR", "NISN ". $request->nisn ." sudah terdaftar");
        }

        DB::beginTransaction();
        try {
            // =====================
            // UPLOAD FILE
            // =====================
            $fileFotoPath = $this->handleUpload($request, 'file_foto');
            $fileNisnPath = $this->handleUpload($request, 'file_nisn');
            $fileKKPath = $this->handleUpload($request, 'file_kk');
            $fileAktaPath = $this->handleUpload($request, 'file_akta');
            $fileRapor52Path = $this->handleUpload($request, 'file_rapor_52');
            $fileRapor61Path = $this->handleUpload($request, 'file_rapor_61');

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

            $savedRow = [
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
                //'SISWA_NILAI_RATA' => $request->nilai_rata,

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
            ];
            if($request->jalur_pendaftaran == "JALUR_AFIRMASI"){
                $savedRow['SISWA_AFIRMASI'] = $request->pilihan_afirmasi;
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = "-";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = "Offline";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = "";
            }
            if($request->jalur_pendaftaran == "JALUR_PRESTASI"){
                $savedRow['SISWA_AFIRMASI'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = $request->tingkat_juara ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = $request->penyelenggara_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = $request->keterangan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = $request->pelaksanaan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = $request->hafalan_quran ?? "";
            }

            $siswa = mSiswa::create($savedRow);
            $siswa->hitungSkor();

            DB::commit();
            
            $this->sendInbox($siswa, $request->jalur_pendaftaran, "Pendaftaran");

            return compose("SUCCESS", "Nomor pendaftaran anda #". str_pad($siswa->SISWA_ID, 4, '0', STR_PAD_LEFT));

        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }


    public function updateDaftar(Request $request)
    {
        $loginUser = $request->loginUser;

        // VALIDASI
        $validated = $request->validate([
            //'tanggal_lahir' => 'required|date|after_or_equal:2011-07-01',

            // file
            'file_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_nisn' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_rapor_52' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_rapor_61' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $record = mSiswa::getByUserId($loginUser->U_ID);
        if(!$record){
            return compose("ERROR", "Data pendaftaran tidak ditemukan");
        }

        DB::beginTransaction();
        try {

            // =====================
            // FUNCTION UPLOAD
            // =====================
            $fileFotoPath = $this->handleUpload($request, 'file_foto', $record->SISWA_FILE_FOTO);
            $fileNisnPath = $this->handleUpload($request, 'file_nisn', $record->SISWA_FILE_NISN);
            $fileKKPath = $this->handleUpload($request, 'file_kk', $record->SISWA_FILE_KK);
            $fileAktaPath = $this->handleUpload($request, 'file_akta', $record->SISWA_FILE_AKTA);
            $fileRapor52Path = $this->handleUpload($request, 'file_rapor_52', $record->SISWA_FILE_RAPOR_52);
            $fileRapor61Path = $this->handleUpload($request, 'file_rapor_61', $record->SISWA_FILE_RAPOR_61);

            // ======================
            // UPDATE
            // ======================
            $savedRow = [
                'SISWA_JALUR' => $request->jalur_pendaftaran,

                // 'SISWA_NAMA' => $request->nama_lengkap,
                // 'SISWA_NISN' => $request->nisn,
                // 'SISWA_JENIS_KELAMIN' => $request->jenis_kelamin,
                // 'SISWA_AYAH' => $request->nama_ayah,
                // 'SISWA_IBU' => $request->nama_ibu,
                // 'SISWA_TEMPAT_LAHIR' => $request->tempat_lahir,
                // 'SISWA_TGL_LAHIR' => $request->tanggal_lahir,
                // 'SISWA_WA' => $request->no_wa,

                // 'SISWA_ALAMAT_PROVINSI' => $request->provinsi,
                // 'SISWA_ALAMAT_KOTA' => $request->kota,
                // 'SISWA_ALAMAT_KECAMATAN' => $request->kecamatan,
                // 'SISWA_ALAMAT_KELURAHAN' => $request->kelurahan,
                // 'SISWA_ALAMAT' => $request->alamat,

                // 'SISWA_SEKOLAH' => $request->asal_sekolah,
                // 'SISWA_SEKOLAH_TAHUN_LULUS' => $request->tahun_lulus,

                // 'SISWA_NILAI_52_MTK' => $request->nilai_52_mtk,
                // 'SISWA_NILAI_52_IPA' => $request->nilai_52_ipa,
                // 'SISWA_NILAI_52_BIND' => $request->nilai_52_bind,
                // 'SISWA_NILAI_52_PAI' => $request->nilai_52_pai,

                // 'SISWA_NILAI_61_MTK' => $request->nilai_61_mtk,
                // 'SISWA_NILAI_61_IPA' => $request->nilai_61_ipa,
                // 'SISWA_NILAI_61_BIND' => $request->nilai_61_bind,
                // 'SISWA_NILAI_61_PAI' => $request->nilai_61_pai,

                'SISWA_FILE_FOTO' => $fileFotoPath,
                'SISWA_FILE_NISN' => $fileNisnPath,
                'SISWA_FILE_KK' => $fileKKPath,
                'SISWA_FILE_AKTA' => $fileAktaPath,
                'SISWA_FILE_RAPOR_52' => $fileRapor52Path,
                'SISWA_FILE_RAPOR_61' => $fileRapor61Path,
            ];
            if($request->jalur_pendaftaran == "JALUR_AFIRMASI"){
                $savedRow['SISWA_AFIRMASI'] = $request->pilihan_afirmasi;
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = "-";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = "Offline";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = "";
            }
            if($request->jalur_pendaftaran == "JALUR_PRESTASI"){
                $savedRow['SISWA_AFIRMASI'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = $request->tingkat_juara ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = $request->penyelenggara_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = $request->keterangan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = $request->pelaksanaan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = $request->hafalan_quran ?? "";
            }

            $record->update($savedRow);
            $record->hitungSkor();

            DB::commit();

            $this->sendInbox($record, $request->jalur_pendaftaran, "Pembaruan data pendaftaran");

            return compose("SUCCESS", "Data pendaftaran berhasil diperbarui");

        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }


    public function handleUpload($request, $field, $oldPath = null)
    {
        if ($request->hasFile($field)) {

            // hapus file lama jika ada
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file($field);
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            return $file->storeAs('siswa', $filename, 'public');
        }

        return $oldPath ?? "";
    }

    public function sendInbox($record, $jalur, $judul){
        $msg = "";
        $msg .= $judul . " siswa baru berhasil, dengan data sebagai berikut:";
        $msg .= "\n\nNama:";
        $msg .= "\n<b>".$record->SISWA_NAMA."</b>";
        $msg .= "\n\nNISN:";
        $msg .= "\n<b>".$record->SISWA_NISN."</b>";
        $msg .= "\n\nJalur:";
        $msg .= "\n<b>".$record->refJalur->R_INFO."</b>";
        $msg .= "\n\nsilahkan cetak kartu pendaftaran pada menu <a href='kartu' target='_blank' style='color: blue;'>CETAK KARTU</a>. Lalu verifikasi berkas pada tanggal yang sudah ditentukan dengan membawa kartu pendaftaran yang telah dicetak.";
        $this->inboxService->send([
            'U_ID' => $record->U_ID,
            'jenis' => "success",
            'judul' => 'Pendaftaran Berhasil',
            'isi' => $msg,
            "to" => $record->SISWA_WA
        ]);

        $waGrup = [
            //"JALUR_REGULER" => "https://chat.whatsapp.com/IFw7mXauoxiBjpJQ91IiTR?mode=gi_t",
            "JALUR_REGULER" => "https://chat.whatsapp.com/JZCi5wUZ3jlIaliL9hozLC?mode=gi_t",

            //"JALUR_AFIRMASI" => "https://chat.whatsapp.com/ByZRTfYRVlOAh7U2E97hrU?mode=gi_t",
            "JALUR_AFIRMASI" => "https://chat.whatsapp.com/FnJsLeJsEHhEDdyCXBR6sh?mode=gi_t",

            //"JALUR_PRESTASI" => "https://chat.whatsapp.com/C06zHp5zcKEKAR1ITtGikG?mode=hq2tswa",
            "JALUR_PRESTASI" => "https://chat.whatsapp.com/BgENQff1i2iAYF7EaGcxXu?mode=gi_t",
        ];
        $msg = "
            Silahkan bergabung pada Grup WhatsApp pendaftar untuk mendapatkan informasi lebih lengkap
            <a href='".$waGrup[$jalur]."' target='_blank' style='color: blue;'>klik untuk bergabung!</a>
            ";
        $this->inboxService->send([
            'U_ID' => $record->U_ID,
            'jenis' => "info",
            'judul' => 'Grup WhatsApp Pendaftar',
            'isi' => trim($msg),
        ]);
    }


    public function siswa(Request $request)
    {
        $loginUser = $request->loginUser;

        if (!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS"])) {
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

        if(!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS"])){
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $siswa = mSiswa::find($siswaId);
        $siswa->hitungSkor();

        $viewData = [
            "siswa" => $siswa,
        ];
        //dd($viewData);
        return view("siswa-detail", $viewData);
    }


}
