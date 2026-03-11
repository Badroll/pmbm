<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

// Phpword
use PhpOffice\PhpWord\TemplateProcessor;


class ExcelController extends Controller
{


    public function __construct(){
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


}
