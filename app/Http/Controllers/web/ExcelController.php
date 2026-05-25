<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Provinsi as mProvinsi;
use App\Models\Kota as mKota;
use App\Models\Kecamatan as mKecamatan;
use App\Models\Kelurahan as mKelurahan;
use App\Models\_reference as _reference;
use App\Models\Siswa as mSiswa;


// ===========================================
//  MANUAL AUTOLOADER (tanpa Composer)
// ===========================================

// 1️MyCLabs Enum
spl_autoload_register(function ($class) {
    $prefix = 'MyCLabs\\Enum\\';
    $base_dir = app_path('Libraries/php-enum/src/');

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2️ZipStream
spl_autoload_register(function ($class) {
    $prefix = 'ZipStream\\';
    $base_dir = app_path('Libraries/ZipStream-PHP-2.1.0/src/');

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 3️PhpSpreadsheet
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\';
    $base_dir = app_path('Libraries/PhpSpreadsheet-1.17.1/src/');

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
        return;
    }

    // fallback jika struktur agak berbeda
    $file2 = $base_dir . 'PhpSpreadsheet/' . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file2)) {
        require $file2;
    }
});

// ===========================================
//  IMPORT KELAS PhpSpreadsheet
// ===========================================
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Phpword
use PhpOffice\PhpWord\TemplateProcessor;


class ExcelController extends Controller
{


    public function __construct(){
        Carbon::setLocale('id');
    }


    public function importWilayah(Request $request)
    {
        /*
        buatkan fingsi import csv unutk provinsi, kota, kecataman, dan kelurahan. dan masukan ke tabel (dg model tadi)
        contoh format csv untuk

        provinsi:
            province,code
            Aceh (NAD),11
            Bali,51

        kota:
            type,regency,province_code,,code
            Kabupaten,Aceh Barat,11,05,1105
            Kabupaten,Aceh Barat Daya,11,12,1112

        kecamatan:
            district,Kode Wilayah,,,regency_code,code
            2 x 11 Enam Lingkuang,13,05,04,1305,130504
            2 x 11 Kayu Tanam,13,05,15,1305,130515
            Abab,16,12,04,1612,161204

        kelurahan:
            postal_code,village,Kode Wilayah,,,,code,district_code
            99037,Gembileme,95,04,32,2006,9504322006,950432
            99037,Kanggilo,95,04,32,2009,9504322009,950432


        */

        return compose("SUCCESS", "Import berhasil");
    }

    
    public function importWilayah2(Request $request)
    {
        set_time_limit(0); // unlimited execution time
        ini_set('memory_limit', '-1');
        DB::beginTransaction();

        try {

            /*
            ======================
            IMPORT PROVINSI
            ======================
            */
            if ($request->hasFile('provinsi')) {
                $rows = $this->readCsv($request->file('provinsi')->getRealPath());

                foreach ($rows as $row) {
                    mProvinsi::updateOrCreate(
                        ['PROV_ID' => $row['code']],
                        ['PROV_NAMA' => $row['province']]
                    );
                }
            }

            /*
            ======================
            IMPORT KOTA
            ======================
            */
            if ($request->hasFile('kota')) {
                $rows = $this->readCsv($request->file('kota')->getRealPath());

                foreach ($rows as $row) {
                    mKota::updateOrCreate(
                        ['KOTA_ID' => $row['code']],
                        [
                            'PROV_ID'    => $row['province_code'],
                            'PROV_JENIS' => $row['type'],
                            'KOTA_NAMA'  => $row['regency'],
                        ]
                    );
                }
            }

            /*
            ======================
            IMPORT KECAMATAN
            ======================
            */
            if ($request->hasFile('kecamatan')) {
                $rows = $this->readCsv($request->file('kecamatan')->getRealPath());

                foreach ($rows as $row) {

                    $kotaId = $row['regency_code'];
                    $provId = substr($kotaId, 0, 2);

                    mKecamatan::updateOrCreate(
                        ['KEC_ID' => $row['code']],
                        [
                            'KEC_NAMA' => $row['district'],
                            'KOTA_ID'  => $kotaId,
                            'PROV_ID'  => $provId,
                        ]
                    );
                }
            }

            /*
            ======================
            IMPORT KELURAHAN
            ======================
            */
            if ($request->hasFile('kelurahan')) {
                $rows = $this->readCsv($request->file('kelurahan')->getRealPath());

                foreach ($rows as $row) {

                    $kecId  = $row['district_code'];
                    $kotaId = substr($kecId, 0, 4);
                    $provId = substr($kecId, 0, 2);

                    mKelurahan::updateOrCreate(
                        ['KEL_ID' => $row['code']],
                        [
                            'KEL_NAMA' => $row['village'],
                            'KEC_ID'   => $kecId,
                            'KOTA_ID'  => $kotaId,
                            'PROV_ID'  => $provId,
                        ]
                    );
                }
            }

            DB::commit();
            return compose("SUCCESS", "Import wilayah berhasil");

        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", $e->getMessage());
        }
    }

    private function readCsv($path)
    {
        $rows = [];
        if (($handle = fopen($path, "r")) !== false) {
            $header = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        }
        return $rows;
    }


    public function kartuPendaftaran(Request $request){
        $siswaId = $request->siswaId;
        $siswa = mSiswa::find($siswaId);
        if(!isset($siswa)){
            return compose("ERROR", "data not found");
        }

        $template = new TemplateProcessor(public_path('word/templates/kartu-pendaftaran.docx'));

        $template->setValue('no', str_pad($siswa->SISWA_ID, 4, '0', STR_PAD_LEFT));
        $template->setValue('nama', $siswa->SISWA_NAMA);
        $template->setValue('nisn', $siswa->SISWA_NISN);
        $template->setValue('jalur', $siswa->refJalur->R_INFO);
        $template->setValue('tgl', tanggal($siswa->SISWA_TGL_DAFTAR, "LONG"));

        $fotoPath = storage_path('app/public/' . $siswa->SISWA_FILE_FOTO);
        if($siswa->SISWA_FILE_FOTO == ""){
            $fotoPath = public_path('images/pas_foto_3x4.png');
        }
        //dd($fotoPath);
        $template->setImageValue('foto', [
            'path' => $fotoPath,
            'width' => 120,
            'height' => 160,
        ]);

        $filename = 'Kartu Pendaftaran '.$siswa->SISWA_NAMA.'';

        $docxPath = storage_path('app/'.$filename.".docx");
        $template->saveAs($docxPath);

        if(isWindows()){
            return response()->download($docxPath)->deleteFileAfterSend(true);
        }

        $pdfPath = storage_path('app/'.$filename.".pdf");

        $command = "libreoffice --headless --nologo --nofirststartwizard --convert-to pdf --outdir "
            . escapeshellarg(storage_path('app')) . " "
            . escapeshellarg($docxPath);

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($pdfPath)) {
            dd($output, $returnCode);
        }

        unlink($docxPath);

        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }


    public function daftarUlang($siswaId)
{
    $data = DB::table('siswa_daftar')
        ->where('SISWA_ID', $siswaId)
        ->first();

    if (!$data) {
        abort(404, 'Data siswa tidak ditemukan');
    }

    $template = new TemplateProcessor(public_path('word/templates/template-daftar-ulang-phpword.docx'));

    // Data Siswa
    $template->setValue('nomor_pendaftaran', str_pad($data->SISWA_ID, 4, '0', STR_PAD_LEFT) ?? '-');
    $template->setValue('nama_lengkap', $data->SD_NAMA_LENGKAP ?? '-');
    $template->setValue('kelas_diinginkan', $data->SD_KELAS_DIINGINKAN ?? '-');
    $template->setValue('nisn', $data->SD_NISN ?? '-');
    $template->setValue('nik', $data->SD_NIK ?? '-');
    $template->setValue('asal_sekolah', $data->SD_ASAL_SEKOLAH ?? '-');
    $template->setValue('npsn_sekolah', $data->SD_NPSN_ASAL ?? '-');
    $template->setValue('tempat_lahir', $data->SD_TEMPAT_LAHIR ?? '-');
    $template->setValue('tanggal_lahir', $data->SD_TANGGAL_LAHIR
        ? \Carbon\Carbon::parse($data->SD_TANGGAL_LAHIR)->translatedFormat('d F Y')
        : '-');
    $template->setValue('jenis_kelamin', $data->SD_JENIS_KELAMIN === 'L' ? 'Laki-Laki' : ($data->SD_JENIS_KELAMIN === 'P' ? 'Perempuan' : '-'));
    $template->setValue('jumlah_saudara', $data->SD_JUMLAH_SAUDARA ?? '-');
    $template->setValue('anak_ke', $data->SD_ANAK_KE ?? '-');
    $template->setValue('agama', $data->SD_AGAMA ?? '-');
    $template->setValue('cita_cita', $data->SD_CITA_CITA ?? '-');
    $template->setValue('no_hp', $data->SD_NO_HP ?? '-');
    $template->setValue('email_siswa', $data->SD_EMAIL ?? '-');
    $template->setValue('hobby', $data->SD_HOBBY ?? '-');
    $template->setValue('yang_membiayai', $data->SD_PEMBIAYA ?? '-');
    $template->setValue('nomor_kip', $data->SD_NOMOR_KIP ?? '-');
    $template->setValue('nomor_kk', $data->SD_NOMOR_KK ?? '-');
    $template->setValue('nama_kepala_keluarga', $data->SD_NAMA_KEPALA_KELUARGA ?? '-');

    // Data Ayah
    $template->setValue('nama_ayah', $data->SD_AYAH_NAMA ?? '-');
    $template->setValue('status_ayah', $data->SD_AYAH_STATUS ?? '-');
    $template->setValue('kewarganegaraan_ayah', $data->SD_AYAH_KEWARGANEGARAAN ?? '-');
    $template->setValue('nik_ayah', $data->SD_AYAH_NIK ?? '-');
    $template->setValue('tempat_lahir_ayah', $data->SD_AYAH_TEMPAT_LAHIR ?? '-');
    $template->setValue('tanggal_lahir_ayah', $data->SD_AYAH_TANGGAL_LAHIR
        ? \Carbon\Carbon::parse($data->SD_AYAH_TANGGAL_LAHIR)->translatedFormat('d F Y')
        : '-');
    $template->setValue('pendidikan_ayah', $data->SD_AYAH_PENDIDIKAN ?? '-');
    $template->setValue('pekerjaan_ayah', $data->SD_AYAH_PEKERJAAN ?? '-');

    // Alamat Ayah
    $template->setValue('ln_alamat_ayah', $data->SD_AYAH_LN_ALAMAT ?? '-');
    $template->setValue('status_rumah_ayah', $data->SD_AYAH_STATUS_RUMAH ?? '-');
    $template->setValue('provinsi_ayah', $data->SD_AYAH_PROVINSI ?? '-');
    $template->setValue('kabupaten_ayah', $data->SD_AYAH_KABUPATEN ?? '-');
    $template->setValue('kecamatan_ayah', $data->SD_AYAH_KECAMATAN ?? '-');
    $template->setValue('kelurahan_ayah', $data->SD_AYAH_KELURAHAN ?? '-');
    $template->setValue('rt_rw_ayah', $data->SD_AYAH_RT_RW ?? '-');
    $template->setValue('alamat_ayah', $data->SD_AYAH_ALAMAT ?? '-');
    $template->setValue('kode_pos_ayah', $data->SD_AYAH_KODE_POS ?? '-');

    // Data Ibu
    $template->setValue('nama_ibu', $data->SD_IBU_NAMA ?? '-');
    $template->setValue('status_ibu', $data->SD_IBU_STATUS ?? '-');
    $template->setValue('kewarganegaraan_ibu', $data->SD_IBU_KEWARGANEGARAAN ?? '-');
    $template->setValue('nik_ibu', $data->SD_IBU_NIK ?? '-');
    $template->setValue('tempat_lahir_ibu', $data->SD_IBU_TEMPAT_LAHIR ?? '-');
    $template->setValue('tanggal_lahir_ibu', $data->SD_IBU_TANGGAL_LAHIR
        ? \Carbon\Carbon::parse($data->SD_IBU_TANGGAL_LAHIR)->translatedFormat('d F Y')
        : '-');
    $template->setValue('pendidikan_ibu', $data->SD_IBU_PENDIDIKAN ?? '-');
    $template->setValue('pekerjaan_ibu', $data->SD_IBU_PEKERJAAN ?? '-');

    // Alamat Ibu
    $template->setValue('ln_alamat_ibu', $data->SD_IBU_LN_ALAMAT ?? '-');
    $template->setValue('status_rumah_ibu', $data->SD_IBU_STATUS_RUMAH ?? '-');
    $template->setValue('provinsi_ibu', $data->SD_IBU_PROVINSI ?? '-');
    $template->setValue('kabupaten_ibu', $data->SD_IBU_KABUPATEN ?? '-');
    $template->setValue('kecamatan_ibu', $data->SD_IBU_KECAMATAN ?? '-');
    $template->setValue('kelurahan_ibu', $data->SD_IBU_KELURAHAN ?? '-');
    $template->setValue('rt_rw_ibu', $data->SD_IBU_RT_RW ?? '-');
    $template->setValue('alamat_ibu', $data->SD_IBU_ALAMAT ?? '-');
    $template->setValue('kode_pos_ibu', $data->SD_IBU_KODE_POS ?? '-');

    // Data Wali
    $template->setValue('nama_wali', $data->SD_WALI_NAMA ?? '-');
    $template->setValue('kewarganegaraan_wali', $data->SD_WALI_KEWARGANEGARAAN ?? '-');
    $template->setValue('nik_wali', $data->SD_WALI_NIK ?? '-');
    $template->setValue('tempat_lahir_wali', $data->SD_WALI_TEMPAT_LAHIR ?? '-');
    $template->setValue('tanggal_lahir_wali', $data->SD_WALI_TANGGAL_LAHIR
        ? \Carbon\Carbon::parse($data->SD_WALI_TANGGAL_LAHIR)->translatedFormat('d F Y')
        : '-');
    $template->setValue('pendidikan_wali', $data->SD_WALI_PENDIDIKAN ?? '-');
    $template->setValue('pekerjaan_wali', $data->SD_WALI_PEKERJAAN ?? '-');
    $template->setValue('penghasilan_ortu', $data->SD_PENGHASILAN_ORTU_WALI ?? '-');

    // Alamat Wali
    $template->setValue('ln_alamat_wali', $data->SD_WALI_LN_ALAMAT ?? '-');
    $template->setValue('status_rumah_wali', $data->SD_WALI_STATUS_RUMAH ?? '-');
    $template->setValue('provinsi_wali', $data->SD_WALI_PROVINSI ?? '-');
    $template->setValue('kabupaten_wali', $data->SD_WALI_KABUPATEN ?? '-');
    $template->setValue('kecamatan_wali', $data->SD_WALI_KECAMATAN ?? '-');
    $template->setValue('kelurahan_wali', $data->SD_WALI_KELURAHAN ?? '-');
    $template->setValue('rt_rw_wali', $data->SD_WALI_RT_RW ?? '-');
    $template->setValue('alamat_wali', $data->SD_WALI_ALAMAT ?? '-');
    $template->setValue('kode_pos_wali', $data->SD_WALI_KODE_POS ?? '-');

    // Alamat Siswa
    $template->setValue('tempat_tinggal', $data->SD_TEMPAT_TINGGAL ?? '-'); // GAK ADA!
    $template->setValue('nama_yayasan', $data->SD_NAMA_YAYASAN ?? '-');
    $template->setValue('provinsi', $data->SD_PROVINSI ?? '-');
    $template->setValue('kabupaten', $data->SD_KABUPATEN ?? '-');
    $template->setValue('kecamatan', $data->SD_KECAMATAN ?? '-');
    $template->setValue('kelurahan', $data->SD_KELURAHAN ?? '-');
    $template->setValue('rt_rw', $data->SD_RT_RW ?? '-');
    $template->setValue('alamat', $data->SD_ALAMAT ?? '-');
    $template->setValue('kode_pos', $data->SD_KODE_POS ?? '-');

    // Transportasi
    $template->setValue('waktu_tempuh', $data->SD_WAKTU_TEMPUH ?? '-');
    $template->setValue('jarak', $data->SD_JARAK_KM ?? '-');
    $template->setValue('transportasi', $data->SD_TRANSPORTASI ?? '-');
    
    $template->setValue('tglNow', date("d") ?? '-');

    // Simpan & Download
    $filename = 'form-daftar-ulang';
    $docxPath = storage_path('app/' . $filename . '.docx');
    $template->saveAs($docxPath);

    if (isWindows()) {
        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    $pdfPath = storage_path('app/' . $filename . '.pdf');
    $command = "libreoffice --headless --nologo --nofirststartwizard --convert-to pdf --outdir "
        . escapeshellarg(storage_path('app')) . " "
        . escapeshellarg($docxPath);
    exec($command, $output, $returnCode);

    if ($returnCode !== 0 || !file_exists($pdfPath)) {
        dd($output, $returnCode);
    }

    unlink($docxPath);
    return response()->download($pdfPath)->deleteFileAfterSend(true);
}



    // ================================== DATA PENDAFTAR ==================================
private const JALUR_CONFIG = [
        'JALUR_REGULER'  => ['label' => 'Reguler',  'header_bg' => '3B4FCD', 'accent' => 'EEF0FD', 'tab' => '3B4FCD'],
        'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'header_bg' => 'D97706', 'accent' => 'FEF3C7', 'tab' => 'D97706'],
        'JALUR_PRESTASI' => ['label' => 'Prestasi', 'header_bg' => '7C3AED', 'accent' => 'F3EEFF', 'tab' => '7C3AED'],
    ];
 
    private const GENDER_MAP = [
        'JENIS_KELAMIN_L' => 'Laki-laki',
        'JENIS_KELAMIN_P' => 'Perempuan',
    ];
 
    private const COLUMNS = [
        'A', 'B', 'C', 'D', 'E', 'F',
        'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'
    ];

    private const HEADERS = [
        'Rank',
        'No.',
        'Nama Siswa',
        'NISN',
        'Jenis Kelamin',
        'Asal Sekolah',

        'A - Rapor',
        'B - CBT Akademik',
        'C - Psikotest',
        'D - Baca Al Quran',
        'E - Afirmasi',
        'F - Prest. Akademik',
        'G - Prest. Keagamaan',
        'H - Umur',

        'Total Skor',
        'Status'
    ];

    private const WIDTHS = [
        6,   // A
        6,   // B
        32,  // C
        18,  // D
        16,  // E
        20,  // F

        14,  // G
        18,  // H
        16,  // I
        20,  // J (Baca Al Quran)
        14,  // K
        20,  // L
        20,  // M
        12,  // N

        12,  // O
        22   // P (Status)
    ];
 
    // ─── Entry point ─────────────────────────────────────────────────────────
    public function dataPendaftar(): void
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setTitle('Data Pendaftar')
            ->setCreator('MTsN 2 Kota Semarang')
            ->setDescription('Rekap data pendaftar per jalur penerimaan');

        $sheetIndex = 0;

        foreach (self::JALUR_CONFIG as $jalurKey => $config) {

            $data = mSiswa::where('SISWA_JALUR', $jalurKey)
                ->orderByRaw("
                    CASE SISWA_STATUS
                        WHEN 'STATUS_LOLOS' THEN 1
                        WHEN 'STATUS_CADANGAN' THEN 2
                        ELSE 3
                    END ASC
                ")
                ->orderByDesc('SISWA_SKOR')
                ->get();

            $sheet = ($sheetIndex === 0)
                ? $spreadsheet->getActiveSheet()
                : $spreadsheet->createSheet();

            $sheet->setTitle($config['label']);
            $sheet->getSheetView()->setZoomScale(100);

            // warna tab
            $sheet->getTabColor()->setRGB($config['tab']);

            $this->writeSheet($sheet, $config, $data);

            // ─── SEMBUNYIKAN KOLOM KHUSUS PER JALUR (Gunakan setVisible untuk keamanan data) ───

            // Reguler → Sembunyikan K (Afirmasi), L (Prest. Akademik), M (Prest. Keagamaan)
            if ($jalurKey === 'JALUR_REGULER') {
                $sheet->getColumnDimension('K')->setVisible(false);
                $sheet->getColumnDimension('L')->setVisible(false);
                $sheet->getColumnDimension('M')->setVisible(false);
            }

            // Afirmasi → Sembunyikan L (Prest. Akademik), M (Prest. Keagamaan)
            if ($jalurKey === 'JALUR_AFIRMASI') {
                $sheet->getColumnDimension('L')->setVisible(false);
                $sheet->getColumnDimension('M')->setVisible(false);
            }

            // Prestasi → Sembunyikan K (Afirmasi)
            if ($jalurKey === 'JALUR_PRESTASI') {
                $sheet->getColumnDimension('K')->setVisible(false);
            }

            $sheetIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = "Data Pendaftar (" . date("YmdHis") . ").xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');

        exit;
    }

    // ─────────────────────────────────────────────────────────────

    private function writeSheet($sheet, array $config, $data): void
    {
        $totalRows = $data->count();

        // ─── TITLE ───────────────────────────────────────────────

        $sheet->mergeCells('A1:P1'); // Diperluas ke P1

        $sheet->setCellValue(
            'A1',
            'DATA PENDAFTAR - JALUR ' . strtoupper($config['label'])
        );

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 13,
                'color' => ['rgb' => $config['header_bg']],
                'name'  => 'Arial',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);

        // ─── SUBTITLE ────────────────────────────────────────────

        $sheet->mergeCells('A2:P2'); // Diperluas ke P2

        $sheet->setCellValue(
            'A2',
            'Dicetak: ' .
            Carbon::now()->isoFormat('dddd, D MMMM Y — HH:mm') .
            ' WIB'
        );

        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size'   => 9,
                'color'  => ['rgb' => '6B7280'],
                'name'   => 'Arial',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(8);

        // ─── HEADER ──────────────────────────────────────────────

        $headerRow = 4;

        foreach (self::COLUMNS as $i => $col) {
            $sheet->setCellValue(
                $col . $headerRow,
                self::HEADERS[$i]
            );

            $sheet->getColumnDimension($col)
                ->setWidth(self::WIDTHS[$i]);
        }

        $sheet->getStyle('A4:P4')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 9,
                'color' => ['rgb' => 'FFFFFF'],
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $config['header_bg']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE
                ],
            ],
        ]);

        $sheet->getRowDimension(4)->setRowHeight(34);

        // ─── EMPTY DATA ──────────────────────────────────────────

        if ($totalRows === 0) {
            $emptyRow = 5;
            $sheet->mergeCells("A{$emptyRow}:P{$emptyRow}");
            $sheet->setCellValue("A{$emptyRow}", 'Tidak ada data untuk jalur ini.');

            $sheet->getStyle("A{$emptyRow}")->applyFromArray([
                'font' => [
                    'italic' => true,
                    'color'  => ['rgb' => '9CA3AF'],
                    'name'   => 'Arial',
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            return;
        }

        // ─── DATA ────────────────────────────────────────────────

        foreach ($data as $index => $s) {

            $skor = $s->hitungSkor();
            $detail = [];

            foreach ($skor["POIN"] as $item) {
                $detail[] = $item[1];
            }

            $row = $index + 5;
            $isEven = ($index % 2 === 1);

            $bgRgb = $isEven ? $config['accent'] : 'FFFFFF';

            // ─── MAIN DATA ───────────────────────────────────────

            $sheet->setCellValue("A{$row}", $index + 1);

            $sheet->setCellValueExplicit(
                "B{$row}",
                (string) str_pad($s->SISWA_ID, 4, '0', STR_PAD_LEFT),
                DataType::TYPE_STRING
            );

            $sheet->setCellValue("C{$row}", $s->SISWA_NAMA);

            $sheet->setCellValueExplicit(
                "D{$row}",
                (string) $s->SISWA_NISN,
                DataType::TYPE_STRING
            );

            $sheet->setCellValue(
                "E{$row}",
                self::GENDER_MAP[$s->SISWA_JENIS_KELAMIN] ?? $s->SISWA_JENIS_KELAMIN
            );

            $sheet->setCellValue(
                "F{$row}",
                //Carbon::parse($s->SISWA_TGL_DAFTAR)->format('d M Y')
                $s->SISWA_SEKOLAH
            );

            // ─── DETAIL SKOR ────────────────────────────────────

            $nilaiQuran = $detail[3] ?? 0; // Guna pengondisian status nanti

            $sheet->setCellValue("G{$row}", round($detail[0], 3));
            $sheet->setCellValue("H{$row}", round($detail[1], 3));
            $sheet->setCellValue("I{$row}", round($detail[2], 3));
            $sheet->setCellValue("J{$row}", round($nilaiQuran, 3));
            $sheet->setCellValue("K{$row}", round($detail[4], 3));
            $sheet->setCellValue("L{$row}", round($detail[5], 3));
            $sheet->setCellValue("M{$row}", round($detail[6], 3));
            $sheet->setCellValue("N{$row}", round($detail[7], 3));

            $sheet->setCellValue("O{$row}", round($skor["TOTAL"], 3));

            // ─── LOGIKA STATUS & COLOR KONDISIONAL ───────────────

            $statusText = 'Tidak Diterima';
            $statusFontColor = 'DC2626'; // Merah Default (Hex Code standard)

            if ($s->SISWA_STATUS === 'STATUS_LOLOS') {
                $statusText = 'Diterima';
                $statusFontColor = '15803D'; // Hijau tua prasmanan
            } elseif ($s->SISWA_STATUS === 'STATUS_CADANGAN') {
                $statusText = 'Cadangan';
                $statusFontColor = '1D4ED8'; // Biru solid
            } elseif ($nilaiQuran < 71) {
                $statusText = 'Tidak Lolos Baca Al-Qur`an';
                $statusFontColor = 'B91C1C'; // Merah BTA keras
            }

            $sheet->setCellValue("P{$row}", $statusText);

            // ─── STYLE DATA ──────────────────────────────────────────

            $sheet->getStyle("A{$row}:P{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bgRgb],
                ],
                'font' => [
                    'size' => 9,
                    'name' => 'Arial',
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Apply khusus warna teks font status di Kolom P
            $sheet->getStyle("P{$row}")->applyFromArray([
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => $statusFontColor]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // alignment angka dan teks tertentu
            $sheet->getStyle("G{$row}:O{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("A{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // ─── TOTAL ROW ──────────────────────────────────────────

        $totalRow = $totalRows + 5;

        $sheet->mergeCells("A{$totalRow}:O{$totalRow}"); // Diperluas sampai O

        $sheet->setCellValue("A{$totalRow}", 'Total Pendaftar');
        $sheet->setCellValue("P{$totalRow}", $totalRows . ' siswa'); // Di P

        $sheet->getStyle("A{$totalRow}:P{$totalRow}")
            ->applyFromArray([
                'font' => [
                    'bold'  => true,
                    'size'  => 9,
                    'name'  => 'Arial',
                    'color' => ['rgb' => $config['header_bg']],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $config['accent']],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => $config['header_bg']],
                    ],
                ],
            ]);

        $sheet->getStyle("A{$totalRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("P{$totalRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getRowDimension($totalRow)->setRowHeight(20);

        // ─── OUTER BORDER ──────────────────────────────────────

        $lastDataCell = 'P' . $totalRow;

        $sheet->getStyle("A4:{$lastDataCell}")
            ->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'D1D5DB'],
                    ],
                ],
            ]);

        // ─── FREEZE & GRIDLINES ─────────────────────────────────

        $sheet->freezePane('A5');
        $sheet->setShowGridlines(false);
    }


}
