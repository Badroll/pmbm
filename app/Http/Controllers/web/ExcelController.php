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
 
    private const COLUMNS = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    private const HEADERS  = ['No.', 'No. Pendaftaran', 'Nama Siswa', 'NISN', 'Jenis Kelamin', 'Tgl. Daftar', 'Skor'];
    private const WIDTHS   = [6, 20, 32, 16, 16, 16, 12];
 
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
                ->orderBy('SISWA_TGL_DAFTAR')
                ->get();
 
            $sheet = ($sheetIndex === 0)
                ? $spreadsheet->getActiveSheet()
                : $spreadsheet->createSheet();
 
            $sheet->setTitle($config['label']);
            $sheet->getSheetView()->setZoomScale(100);
 
            // Warna tab sheet
            $sheet->getTabColor()->setRGB($config['tab']);
 
            $this->writeSheet($sheet, $config, $data);
            $sheetIndex++;
        }
 
        // Aktifkan sheet pertama saat dibuka
        $spreadsheet->setActiveSheetIndex(0);
 
        $filename = "Data Pendaftar (" . date("YmdHis") . ").xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
 
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
 
    private function writeSheet($sheet, array $config, $data): void
    {
        $totalRows = $data->count();
 
        // ── Judul & metadata (baris 1–3) ─────────────────────────────────────
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DATA PENDAFTAR - JALUR ' . strtoupper($config['label']));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => $config['header_bg']], 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);
 
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Dicetak: ' . Carbon::now()->isoFormat('dddd, D MMMM Y — HH:mm') . ' WIB');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280'], 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(8);  // spasi kosong
 
        // ── Header kolom (baris 4) ────────────────────────────────────────────
        $headerRow = 4;
        foreach (self::COLUMNS as $i => $col) {
            $cell = $col . $headerRow;
            $sheet->setCellValue($cell, self::HEADERS[$i]);
            $sheet->getColumnDimension($col)->setWidth(self::WIDTHS[$i]);
        }
 
        $sheet->getStyle('A4:G4')->applyFromArray([
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
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_NONE],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);
 
        // ── Data baris ────────────────────────────────────────────────────────
        if ($totalRows === 0) {
            $emptyRow = 5;
            $sheet->mergeCells("A{$emptyRow}:G{$emptyRow}");
            $sheet->setCellValue("A{$emptyRow}", 'Tidak ada data untuk jalur ini.');
            $sheet->getStyle("A{$emptyRow}")->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['rgb' => '9CA3AF'], 'name' => 'Arial'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            return;
        }
 
        foreach ($data as $index => $s) {
            $row    = $index + 5;
            $isEven = ($index % 2 === 1);
            $bgRgb  = $isEven ? $config['accent'] : 'FFFFFF';
 
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValueExplicit("B{$row}", (string) $s->SISWA_NO, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $s->SISWA_NAMA);
            $sheet->setCellValueExplicit("D{$row}", (string) $s->SISWA_NISN, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("E{$row}", self::GENDER_MAP[$s->SISWA_JENIS_KELAMIN] ?? $s->SISWA_JENIS_KELAMIN);
            $sheet->setCellValue("F{$row}", Carbon::parse($s->SISWA_TGL_DAFTAR)->format('d M Y'));
            $sheet->setCellValue("G{$row}", $s->SISWA_SKOR);
 
            // Zebra stripe background
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgRgb]],
                'font' => ['size' => 9, 'name' => 'Arial'],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
 
            // Alignment per kolom
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 
            $sheet->getRowDimension($row)->setRowHeight(18);
        }
 
        // ── Baris total (bawah data) ──────────────────────────────────────────
        $totalRow  = $totalRows + 5;
        $dataStart = 5;
        $dataEnd   = $totalRows + 4;
 
        $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'Total Pendaftar');
        $sheet->setCellValue("F{$totalRow}", "=AVERAGE(G{$dataStart}:G{$dataEnd})");
        $sheet->setCellValue("G{$totalRow}", $totalRows . ' siswa');
 
        $sheet->getStyle("A{$totalRow}:G{$totalRow}")->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => ['rgb' => $config['header_bg']],
            ],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $config['accent']]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $config['header_bg']]],
            ],
        ]);
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(20);
 
        // ── Border luar tabel ─────────────────────────────────────────────────
        $lastDataCell = 'G' . ($totalRow);
        $sheet->getStyle("A4:{$lastDataCell}")->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);
 
        // ── Freeze pane di bawah header ───────────────────────────────────────
        $sheet->freezePane('A5');
 
        // ── Sembunyikan gridlines ─────────────────────────────────────────────
        $sheet->setShowGridlines(false);
    }




}
