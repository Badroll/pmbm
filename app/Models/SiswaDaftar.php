<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaDaftar extends Model
{
    use HasFactory;

    protected $table = 'siswa_daftar';

    protected $primaryKey = 'SD_ID';

    public $timestamps = false;

    protected $fillable = [
        'SISWA_ID',

        // DATA SISWA
        'SD_NOMOR_PENDAFTARAN',
        'SD_NAMA_LENGKAP',
        'SD_KELAS_DIINGINKAN',
        'SD_NISN',
        'SD_NIK',
        'SD_ASAL_SEKOLAH',
        'SD_NPSN_ASAL',
        'SD_TEMPAT_LAHIR',
        'SD_TANGGAL_LAHIR',
        'SD_JENIS_KELAMIN',
        'SD_JUMLAH_SAUDARA',
        'SD_ANAK_KE',
        'SD_AGAMA',
        'SD_CITA_CITA',
        'SD_NO_HP',
        'SD_EMAIL',
        'SD_HOBBY',
        'SD_PEMBIAYA',
        'SD_NOMOR_KIP',
        'SD_NOMOR_KK',
        'SD_NAMA_KEPALA_KELUARGA',

        // DATA AYAH
        'SD_AYAH_NAMA',
        'SD_AYAH_STATUS',
        'SD_AYAH_KEWARGANEGARAAN',
        'SD_AYAH_NIK',
        'SD_AYAH_TEMPAT_LAHIR',
        'SD_AYAH_TANGGAL_LAHIR',
        'SD_AYAH_PENDIDIKAN',
        'SD_AYAH_PEKERJAAN',

        // DATA IBU
        'SD_IBU_NAMA',
        'SD_IBU_STATUS',
        'SD_IBU_KEWARGANEGARAAN',
        'SD_IBU_NIK',
        'SD_IBU_TEMPAT_LAHIR',
        'SD_IBU_TANGGAL_LAHIR',
        'SD_IBU_PENDIDIKAN',
        'SD_IBU_PEKERJAAN',

        // DATA WALI
        'SD_WALI_NAMA',
        'SD_WALI_KEWARGANEGARAAN',
        'SD_WALI_NIK',
        'SD_WALI_TEMPAT_LAHIR',
        'SD_WALI_TANGGAL_LAHIR',
        'SD_WALI_PENDIDIKAN',
        'SD_WALI_PEKERJAAN',
        'SD_PENGHASILAN_ORTU_WALI',

        // ALAMAT AYAH
        'SD_AYAH_LN_ALAMAT',
        'SD_AYAH_STATUS_RUMAH',
        'SD_AYAH_PROVINSI',
        'SD_AYAH_KABUPATEN',
        'SD_AYAH_KECAMATAN',
        'SD_AYAH_KELURAHAN',
        'SD_AYAH_RT_RW',
        'SD_AYAH_ALAMAT',
        'SD_AYAH_KODE_POS',

        // ALAMAT IBU
        'SD_IBU_LN_ALAMAT',
        'SD_IBU_STATUS_RUMAH',
        'SD_IBU_PROVINSI',
        'SD_IBU_KABUPATEN',
        'SD_IBU_KECAMATAN',
        'SD_IBU_KELURAHAN',
        'SD_IBU_RT_RW',
        'SD_IBU_ALAMAT',
        'SD_IBU_KODE_POS',

        // ALAMAT WALI
        'SD_WALI_LN_ALAMAT',
        'SD_WALI_STATUS_RUMAH',
        'SD_WALI_PROVINSI',
        'SD_WALI_KABUPATEN',
        'SD_WALI_KECAMATAN',
        'SD_WALI_KELURAHAN',
        'SD_WALI_RT_RW',
        'SD_WALI_ALAMAT',
        'SD_WALI_KODE_POS',

        // ALAMAT SISWA
        'SD_TEMPAT_TINGGAL',
        'SD_NAMA_YAYASAN',
        'SD_PROVINSI',
        'SD_KABUPATEN',
        'SD_KECAMATAN',
        'SD_KELURAHAN',
        'SD_RT_RW',
        'SD_ALAMAT',
        'SD_KODE_POS',

        // TRANSPORTASI
        'SD_WAKTU_TEMPUH',
        'SD_JARAK_KM',
        'SD_TRANSPORTASI',

        // META
        'SD_WAKTU_BUAT',
        'SD_WAKTU_UBAH',
    ];

    protected $casts = [
        'SD_TANGGAL_LAHIR' => 'date',
        'SD_AYAH_TANGGAL_LAHIR' => 'date',
        'SD_IBU_TANGGAL_LAHIR' => 'date',
        'SD_WALI_TANGGAL_LAHIR' => 'date',

        'SD_WAKTU_BUAT' => 'datetime',
        'SD_WAKTU_UBAH' => 'datetime',

        'SD_JUMLAH_SAUDARA' => 'integer',
        'SD_ANAK_KE' => 'integer',
        'SD_JARAK_KM' => 'decimal:2',
    ];
    

    public function siswa(){
        return $this->hasOne(Siswa::class, "SISWA_ID", "SISWA_ID");
    }

}