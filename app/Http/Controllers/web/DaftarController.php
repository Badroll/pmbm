<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;
use App\Models\Provinsi as mProvinsi;
use App\Models\Kota as mKota;
use App\Models\Kecamatan as mKecamatan;
use App\Models\Kelurahan as mKelurahan;

use App\Models\SiswaDaftar;
use App\Models\_setting;

use App\Services\InboxService as mInboxService;
use App\Http\Controllers\web\ExcelController as mExcelController;

class DaftarController extends Controller
{
    protected $inboxService;
    protected $mExcelController;

    public function __construct(){
        $this->mExcelController = new mExcelController();
    }


    // public function daftar(Request $request){
    //     $loginUser = $request->loginUser;
    //     $req = $request->all();

    //     $refProvinsi = mProvinsi::all();
    //     $refKota = mKota::all();
    //     $refKecamatan = mKecamatan::all();
    //     $refKelurahan = mKelurahan::all();

    //     $siswa = mSiswa::getByUserId($loginUser->U_ID);
    //     if(!in_array($loginUser->U_ROLE, ["ROLE_SISWA"])) {
    //         $userId = $req->userId ?? null;
    //         if($userId){
    //             $siswa = mSiswa::getByUserId($userId);
    //         }
    //     }

    //     $viewData = [
    //         "refProvinsi" => $refProvinsi,
    //         "refKota" => $refKota,
    //         "refKecamatan" => $refKecamatan,
    //         "refKelurahan" => $refKelurahan,
    //         "siswa" => $siswa,
    //     ];
    //     //dd($viewData);
    //     return view("daftar", $viewData);
    // }

    public function daftar(Request $request){
        $loginUser = $request->loginUser;
        $req = $request->all();

        $refProvinsi = mProvinsi::all();
        $refKota = mKota::all();
        $refKecamatan = mKecamatan::all();
        $refKelurahan = mKelurahan::all();

        $siswa = mSiswa::getByUserId($loginUser->U_ID);
        
        // Admin bisa akses data siswa tertentu via ?userId=xxx
        if(!in_array($loginUser->U_ROLE, ["ROLE_SISWA"])) {
            $userId = $req['userId'] ?? null;
            if($userId){
                $siswa = mSiswa::getByUserId($userId);
            }
        }

        $openDate = Carbon::create(2026, 3, 30, 0, 0, 0);
        $closeDate = Carbon::create(2026, 5, 13, 23, 59, 59);
        $now = Carbon::now();
        $isInRange = $now->gte($openDate) && $now->lte($closeDate);

        $role = $loginUser->U_ROLE;
        $isSiswa = $role === 'ROLE_SISWA';

        $isEdit = isset($siswa);
        $siswaStatus = $isEdit ? $siswa->SISWA_STATUS : null;

        // Locked hanya berlaku untuk siswa, admin selalu bisa edit (sesuai permission)
        $isAdminRole = in_array($role, ['ROLE_ADMIN_BERKAS', 'ROLE_ADMIN_PRESTASI', 'ROLE_ADMIN_AFIRMASI', 'ROLE_SUPERADMIN']);
        $isStatusLocked = in_array($siswaStatus, ['STATUS_TERVERIFIKASI', 'STATUS_MENUNGGU', 'STATUS_LOLOS', 'STATUS_DITOLAK', 'STATUS_CADANGAN', 'STATUS_DITERIMA', 'STATUS_MENGUNDURKAN', 'STATUS_TERDAFTAR']);
        $isLocked = $isStatusLocked && !$isAdminRole;

        $editPermissions = [
            'section_pribadi'        => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS']),
            'section_sekolah'        => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS']),
            'section_jalur_radio'    => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS']),
            'section_jalur_prestasi' => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS', 'ROLE_ADMIN_PRESTASI']),
            'section_jalur_afirmasi' => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS', 'ROLE_ADMIN_AFIRMASI']),
            'section_dokumen'        => ($isSiswa && $isInRange) || in_array($role, ['ROLE_SUPERADMIN', 'ROLE_ADMIN_BERKAS']),
        ];

        $viewData = [
            "refProvinsi"     => $refProvinsi,
            "refKota"         => $refKota,
            "refKecamatan"    => $refKecamatan,
            "refKelurahan"    => $refKelurahan,
            "siswa"           => $siswa,
            "editPermissions" => $editPermissions,
            "loginRole"       => $role,
            "isAdminEdit"     => $req['userId'] ?? null,
            "openDate"        => $openDate,
            "closeDate"       => $closeDate,
            "now"             => $now,
            "isLocked"        => $isLocked,
            "isOpen"          => !$isSiswa || $isInRange,  // admin selalu "open", siswa cek range
            "isEdit"          => $isEdit,
            "siswaStatus"     => $siswaStatus,
        ];

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

        //$record = mSiswa::getByUserId($loginUser->U_ID);
        $record = mSiswa::find($request->siswaId);
        //dd($record);
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
            $jalur = $request->jalur_pendaftaran ?? $record->SISWA_JALUR;
            $savedRow = [
                'SISWA_JALUR' => $jalur,

                'SISWA_NAMA' => $request->nama_lengkap ?? $record->SISWA_NAMA,
                'SISWA_NISN' => $request->nisn ?? $record->SISWA_NISN,
                'SISWA_JENIS_KELAMIN' => $request->jenis_kelamin ?? $record->SISWA_JENIS_KELAMIN,
                'SISWA_AYAH' => $request->nama_ayah ?? $record->SISWA_AYAH,
                'SISWA_IBU' => $request->nama_ibu ?? $record->SISWA_IBU,
                'SISWA_TEMPAT_LAHIR' => $request->tempat_lahir ?? $record->SISWA_TEMPAT_LAHIR,
                'SISWA_TGL_LAHIR' => $request->tanggal_lahir ?? $record->SISWA_TGL_LAHIR,
                'SISWA_WA' => $request->no_wa ?? $record->SISWA_WA,

                'SISWA_ALAMAT_PROVINSI' => $request->provinsi ?? $record->SISWA_ALAMAT_PROVINSI,
                'SISWA_ALAMAT_KOTA' => $request->kota ?? $record->SISWA_ALAMAT_KOTA,
                'SISWA_ALAMAT_KECAMATAN' => $request->kecamatan ?? $record->SISWA_ALAMAT_KECAMATAN,
                'SISWA_ALAMAT_KELURAHAN' => $request->kelurahan ?? $record->SISWA_ALAMAT_KELURAHAN,
                'SISWA_ALAMAT' => $request->alamat ?? $record->SISWA_ALAMAT,

                'SISWA_SEKOLAH' => $request->asal_sekolah ?? $record->SISWA_SEKOLAH,
                'SISWA_SEKOLAH_TAHUN_LULUS' => $request->tahun_lulus ?? $record->SISWA_SEKOLAH_TAHUN_LULUS,

                'SISWA_NILAI_52_MTK' => $request->nilai_52_mtk ?? $record->SISWA_NILAI_52_MTK,
                'SISWA_NILAI_52_IPA' => $request->nilai_52_ipa ?? $record->SISWA_NILAI_52_IPA,
                'SISWA_NILAI_52_BIND' => $request->nilai_52_bind ?? $record->SISWA_NILAI_52_BIND,
                'SISWA_NILAI_52_PAI' => $request->nilai_52_pai ?? $record->SISWA_NILAI_52_PAI,

                'SISWA_NILAI_61_MTK' => $request->nilai_61_mtk ?? $record->SISWA_NILAI_61_MTK,
                'SISWA_NILAI_61_IPA' => $request->nilai_61_ipa ?? $record->SISWA_NILAI_61_IPA,
                'SISWA_NILAI_61_BIND' => $request->nilai_61_bind ?? $record->SISWA_NILAI_61_BIND,
                'SISWA_NILAI_61_PAI' => $request->nilai_61_pai ?? $record->SISWA_NILAI_61_PAI,

                'SISWA_FILE_FOTO' => $fileFotoPath,
                'SISWA_FILE_NISN' => $fileNisnPath,
                'SISWA_FILE_KK' => $fileKKPath,
                'SISWA_FILE_AKTA' => $fileAktaPath,
                'SISWA_FILE_RAPOR_52' => $fileRapor52Path,
                'SISWA_FILE_RAPOR_61' => $fileRapor61Path,
            ];
            if($jalur == "JALUR_REGULER"){
                $savedRow['SISWA_AFIRMASI'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = "-";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = "Offline";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = "";
            }
            if($jalur == "JALUR_AFIRMASI"){
                $savedRow['SISWA_AFIRMASI'] = $request->pilihan_afirmasi;
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = "-";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = "Offline";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = "";
            }
            if($jalur == "JALUR_PRESTASI"){
                $savedRow['SISWA_AFIRMASI'] = "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN'] = $request->tingkat_juara ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_JUDUL'] = $request->penyelenggara_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_KETERANGAN'] = $request->keterangan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEJUARAAN_PELAKSANAAN'] = $request->pelaksanaan_kejuaraan ?? "";
                $savedRow['SISWA_PRESTASI_KEAGAMAAN'] = $request->hafalan_quran ?? "";
            }
            //dd($savedRow);

            $record->update($savedRow);
            $record->hitungSkor();

            DB::commit();

            if($loginUser->U_ROLE == "SISWA"){
                $this->sendInbox($record, $request->jalur_pendaftaran, "Pembaruan data pendaftaran");
            }

            return compose("SUCCESS", "Data pendaftaran berhasil diperbarui");

        } catch (\Exception $e) {
            return compose("ERROR", "Terjadi kesalahan internal", [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
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
            //"JALUR_REGULER" => "https://chat.whatsapp.com/JZCi5wUZ3jlIaliL9hozLC?mode=gi_t",
            "JALUR_REGULER" => "https://chat.whatsapp.com/Lsy2g1wDpjA3o7pf5ZuY2Q",

            //"JALUR_AFIRMASI" => "https://chat.whatsapp.com/FnJsLeJsEHhEDdyCXBR6sh?mode=gi_t",
            "JALUR_AFIRMASI" => "https://chat.whatsapp.com/BHoNjxXCtw6Ap8INyNZnCw",

            //"JALUR_PRESTASI" => "https://chat.whatsapp.com/BgENQff1i2iAYF7EaGcxXu",
            "JALUR_PRESTASI" => "https://chat.whatsapp.com/IALv1nnspd7IxwxMrdc3kW"
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


    public function daftarUlang(Request $request){
    $loginUser = $request->loginUser;

    if (
    $loginUser->siswa->SISWA_STATUS != "STATUS_LOLOS" &&
    $loginUser->U_ID != 1 &&
    !in_array($loginUser->siswa->SISWA_ID, [92, 193, 113, 376, 252, 514, 178, 498, 151, 102, 484
])
) {
    return redirect()->back()->with("info", "anda tidak lolos");
}

// lanjut kodenta

    $siswa = $loginUser->siswa;
    $siswaDaftar = $siswa->siswaDaftar;

    // Jika belum ada record di siswa_daftar, buat baru dengan data dari tabel siswa
    if(!isset($siswaDaftar)){
        $siswaDaftar = SiswaDaftar::create([
            'SISWA_ID'              => $siswa->SISWA_ID,
            'SD_NAMA_LENGKAP'       => $siswa->SISWA_NAMA,
            'SD_NISN'               => $siswa->SISWA_NISN,
            'SD_TEMPAT_LAHIR'       => $siswa->kotaTempatLahir->KOTA_JENIS . " " . $siswa->kotaTempatLahir->KOTA_NAMA,
            'SD_TANGGAL_LAHIR'      => $siswa->SISWA_TGL_LAHIR,
            'SD_JENIS_KELAMIN'      => $siswa->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'L' : 'P',
            'SD_NO_HP'              => $siswa->SISWA_WA,
            'SD_ASAL_SEKOLAH'       => $siswa->SISWA_SEKOLAH,
            'SD_AYAH_NAMA'          => $siswa->SISWA_AYAH,
            'SD_IBU_NAMA'           => $siswa->SISWA_IBU,
            'SD_PROVINSI'           => $siswa->provinsiAlamat->PROV_NAMA,
            'SD_KABUPATEN'          => $siswa->kotaAlamat->KOTA_JENIS . " " . $siswa->kotaTempatLahir->KOTA_NAMA,
            'SD_KECAMATAN'          => $siswa->kecamatanAlamat->KEC_NAMA,
            'SD_KELURAHAN'          => $siswa->kelurahanAlamat->KEL_NAMA,
            'SD_ALAMAT'             => $siswa->SISWA_ALAMAT,
            'SD_WAKTU_BUAT'         => now(),
        ]);
    }

    $viewData = [
        "siswa"      => $siswa,
        "sd"         => $siswaDaftar,
        "formAction" => "/daftar-ulang",
        "isEdit"     => true, // sudah pasti ada setelah logic di atas
        "isLocked"   => false
    ];

    return view("daftar-ulang", $viewData);
}

    public function daftarUlangSave(Request $request)
    {
        $loginUser = $request->loginUser;
        
        // ambil semua fillable dari model
        $fillable = (new SiswaDaftar())->getFillable();

        // siapkan data
        $data = [];

        foreach ($fillable as $field) {
            // skip field meta manual
            if (in_array($field, ['SD_WAKTU_BUAT', 'SD_WAKTU_UBAH'])) {
                continue;
            }

            // karena input form lowercase
            // contoh: SD_NAMA_LENGKAP -> sd_nama_lengkap
            $inputName = strtolower($field);

            $data[$field] = $request->input($inputName);
        }

        // contoh otomatis ambil siswa_id dari user login
        // sesuaikan sendiri jika beda
        $data['SISWA_ID'] = $loginUser->siswa->SISWA_ID;

        // waktu
        $data['SD_WAKTU_UBAH'] = now();

        // cek apakah sudah pernah daftar
        $pendaftaran = SiswaDaftar::where('SISWA_ID', $data['SISWA_ID'])->first();

        if ($pendaftaran) {

            // UPDATE
            $pendaftaran->update($data);

        } else {

            // INSERT
            $data['SD_WAKTU_BUAT'] = now();

            $pendaftaran = SiswaDaftar::create($data);
        }

        return $this->mExcelController->daftarUlang($pendaftaran->SISWA_ID);
        //return redirect()->back()->with('success', 'Data pendaftaran berhasil disimpan.');
    }

    public function pengumuman(Request $request){
        $loginUser = $request->loginUser;

        if(!$loginUser->siswa){
            return redirect()->back()->with("warning", "anda tidak mendaftar");
        }

        $status = "tidak_diterima";
        if($loginUser->siswa->SISWA_STATUS == "STATUS_LOLOS"){
            $status = "diterima";
        }
        if($loginUser->siswa->SISWA_STATUS == "STATUS_CADANGAN"){
            $status = "cadangan";
        }

        $catatan = "";
        if($status == "tidak_diterima" && $loginUser->siswa->SISWA_TES_QURAN < 71){
            $catatan = "Tidak lolos Baca Al-Qur`an";
        }

        return view('pengumuman', [
            'status'  => $status,      // 'diterima' | 'tidak_diterima' | 'cadangan'
            'nama'    => $loginUser->siswa->SISWA_NAMA,
            'nomor'   => str_pad($loginUser->siswa->SISWA_ID, 4, '0', STR_PAD_LEFT),
            'catatan' => $catatan, // hanya ditampilkan jika status 'tidak_diterima'
        ]);
    }


    public function siswa(Request $request)
    {
        $loginUser = $request->loginUser;

        if (!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS", "ROLE_ADMIN_BTA", "ROLE_ADMIN_PRESTASI", "ROLE_ADMIN_AFIRMASI"])) {
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

        if (!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS", "ROLE_ADMIN_BTA", "ROLE_ADMIN_PRESTASI", "ROLE_ADMIN_AFIRMASI"])) {
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


    public function updateBTA(Request $request){
        $loginUser = $request->loginUser;
        $req = $request->all();

        // if(!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS"])){
        //     return compose("ERROR", "Anda tidak berhak mengakses");
        // }

        $siswa = mSiswa::find($req["siswaId"]);
        $siswa->SISWA_TES_QURAN = $req["nilai"];
        $siswa->save();
        $siswa->hitungSkor();

        return response()->json([
            'success'   => true,
        ]);

    }

    public function siswaUpdateStatus(Request $request){
        $loginUser = $request->loginUser;
        $req = $request->all();

        if(!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN", "ROLE_ADMIN_BERKAS"])){
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $siswa = mSiswa::find($req["id"]);
        $siswa->SISWA_STATUS = $req["status"];
        $siswa->save();

        return compose("SUCCESS", "Status diperbarui");

    }


public function updateBulkKhusus(Request $request)
{
    $loginUser = $request->loginUser;

    if ($loginUser->U_ROLE !== "ROLE_SUPERADMIN") {
        return compose("ERROR", "Anda tidak berhak mengakses");
    }

    DB::beginTransaction();
    
    $flag = _setting::find("UPDATE_BULK");
    if($flag->S_VALUE == "Y"){
        DB::rollBack();
        return redirect()->back()->with("info", "proses ini sudah dilakukan"); 
    }

    try {
        $kuotaKhusus = 51;    // kuota untuk afirmasi & prestasi
        $kuotaReguler = 238;  // kuota untuk reguler
        $kuotaCadangan = 68;  // kuota cadangan dari reguler

        // Proses jalur khusus (Afirmasi & Prestasi) - ada alih jalur ke reguler
        $prosesJalurKhusus = function ($jalur) use ($kuotaKhusus) {
            $rows = mSiswa::select('SISWA_ID')
                ->where('SISWA_STATUS', 'STATUS_TERVERIFIKASI')
                ->where('SISWA_TES_QURAN', '>', 70)
                ->where('SISWA_JALUR', $jalur)
                ->orderByDesc('SISWA_SKOR')
                ->get();

            $ids = [];
            foreach ($rows as $r) {
                $ids[] = $r->SISWA_ID;
            }

            $lolosIds = array_slice($ids, 0, $kuotaKhusus);
            $alihIds  = array_slice($ids, $kuotaKhusus);

            logcmd($alihIds);

            if (!empty($lolosIds)) {
                mSiswa::whereIn('SISWA_ID', $lolosIds)->update(['SISWA_STATUS' => 'STATUS_LOLOS']);
            }

            if (!empty($alihIds)) {
                // Ubah jalur dulu ke reguler
                mSiswa::whereIn('SISWA_ID', $alihIds)->update(['SISWA_JALUR' => 'JALUR_REGULER']);

                // Hitung ulang skor satu per satu
                $siswaAlih = mSiswa::whereIn('SISWA_ID', $alihIds)->get();
                foreach ($siswaAlih as $s) {
                    $s->hitungSkor();
                }
            }
        };

        // Proses jalur reguler - TIDAK ada alih jalur, sisanya tetap di reguler (tidak lolos)
        $prosesJalurReguler = function () use ($kuotaReguler) {
            $rows = mSiswa::select('SISWA_ID')
                ->where('SISWA_STATUS', 'STATUS_TERVERIFIKASI')
                ->where('SISWA_TES_QURAN', '>', 70)
                ->where('SISWA_JALUR', 'JALUR_REGULER')
                ->orderByDesc('SISWA_SKOR')
                ->get();

            $ids = [];
            foreach ($rows as $r) {
                $ids[] = $r->SISWA_ID;
            }

            $lolosIds = array_slice($ids, 0, $kuotaReguler);
            // Tidak ada alih jalur untuk reguler, sisanya tetap STATUS_TERVERIFIKASI (tidak lolos)

            logcmd("Reguler lolos: " . count($lolosIds));

            if (!empty($lolosIds)) {
                mSiswa::whereIn('SISWA_ID', $lolosIds)->update(['SISWA_STATUS' => 'STATUS_LOLOS']);
            }
        };

        // Proses cadangan - ambil 68 teratas dari sisa reguler yang masih STATUS_TERVERIFIKASI
        $prosesCadangan = function () use ($kuotaCadangan) {
            $rows = mSiswa::select('SISWA_ID')
                ->where('SISWA_STATUS', 'STATUS_TERVERIFIKASI')
                ->where('SISWA_TES_QURAN', '>', 70)
                ->where('SISWA_JALUR', 'JALUR_REGULER')
                ->orderByDesc('SISWA_SKOR')
                ->get();

            $ids = [];
            foreach ($rows as $r) {
                $ids[] = $r->SISWA_ID;
            }

            $cadanganIds = array_slice($ids, 0, $kuotaCadangan);

            logcmd("Cadangan: " . count($cadanganIds));

            if (!empty($cadanganIds)) {
                mSiswa::whereIn('SISWA_ID', $cadanganIds)->update(['SISWA_STATUS' => 'STATUS_CADANGAN']);
            }
        };

        // Jalankan proses jalur khusus dulu (agar yang dialihkan ikut diproses di reguler)
        $prosesJalurKhusus('JALUR_AFIRMASI');
        $prosesJalurKhusus('JALUR_PRESTASI');

        // Baru proses jalur reguler (sudah termasuk siswa yang dialihkan dari afirmasi/prestasi)
        $prosesJalurReguler();

        // Setelah reguler diproses, ambil 68 teratas dari sisa reguler sebagai cadangan
        $prosesCadangan();

        $flag->S_VALUE = "Y";
        $flag->save();

        DB::commit();
        return redirect()->back()->with("success", "berhasil diperbarui massal");

    } catch (\Throwable $e) {
        DB::rollBack();
        return compose("ERROR", "Gagal: " . $e->getMessage());
    }
}

public function updateBulkRollback(Request $request)
{
    $loginUser = $request->loginUser;

    if ($loginUser->U_ROLE !== "ROLE_SUPERADMIN") {
        return compose("ERROR", "Anda tidak berhak mengakses");
    }

    DB::beginTransaction();
    try {
        $affectedRows = mSiswa::whereIn('SISWA_STATUS', [
            'STATUS_LOLOS',
            'STATUS_DITOLAK',
            'STATUS_CADANGAN'
        ])->update([
            'SISWA_STATUS' => 'STATUS_TERVERIFIKASI'
        ]);

        _setting::find("UPDATE_BULK")->update([
            "S_VALUE" => "N"
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Berhasil memverifikasi {$affectedRows} siswa",
            'affected_rows' => $affectedRows
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        logcmd('Error verifikasi siswa: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Gagal memverifikasi siswa',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
