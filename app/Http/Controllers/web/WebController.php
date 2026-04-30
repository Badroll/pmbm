<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Inbox as mInbox;
use App\Models\Siswa as mSiswa;
use App\Models\User as mUser;
use App\Models\Berita as mBerita;

class WebController extends Controller
{

    public function home()
    {
        // Ambil 3 berita terbaru yang sudah published untuk ditampilkan di home
        $beritaTerbaru = mBerita::published()->limit(3)->get();

        // ... variabel lain yang sudah ada di HomeController Anda tetap dipertahankan

        return view('home', compact(
            'beritaTerbaru',
            // ... variabel lain yang sudah ada
        ));
    }


    public function inbox(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $inbox = mUser::find($loginUser->U_ID)->inbox;

        $viewData = [
            "inbox" => $inbox,
            "siswa" => mSiswa::getByUserId($loginUser->U_ID)
        ];

        if(isset($req["json"])) dd($viewData);
        return view("inbox", $viewData);
    }


    public function kartu(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $viewData = [
            "siswa" => mSiswa::getByUserId($loginUser->U_ID)
        ];

        if(isset($req["json"])) dd($viewData);
        return view("kartu", $viewData);
    }


    public function jurnal(Request $request){
        $loginUser = $request->loginUser; // login user
        $req = $request->all();

        $viewData = [
            //"siswa" => mSiswa::getByUserId($loginUser->U_ID)
        ];

        if(isset($req["json"])) dd($viewData);
        return view("jurnal", $viewData);
    }

    public function jurnalDatatable(Request $request)
    {
        $loginUser = $request->loginUser;
        // if (!in_array($loginUser->U_ROLE, ["ROLE_SUPERADMIN"])) {
        //     return compose("ERROR", "Anda tidak berhak mengakses");
        // }

        // ── Base query: ranking berdasarkan skor tertinggi ──────────────────
        $query = \DB::table('siswa')
            ->select(
                'SISWA_ID', 'SISWA_NAMA', 'SISWA_JALUR', 'SISWA_SKOR',
                'SISWA_STATUS', 'SISWA_NISN', 'SISWA_TGL_LAHIR',
                'SISWA_TES_CBT_AKADEMIK', 'SISWA_TES_CBT_PSIKO', 'SISWA_TES_QURAN',
                'SISWA_AFIRMASI', 'SISWA_PRESTASI_KEJUARAAN', 'SISWA_PRESTASI_KEAGAMAAN',
                'SISWA_NILAI_52_MTK', 'SISWA_NILAI_52_IPA', 'SISWA_NILAI_52_BIND', 'SISWA_NILAI_52_PAI',
                'SISWA_NILAI_61_MTK', 'SISWA_NILAI_61_IPA', 'SISWA_NILAI_61_BIND', 'SISWA_NILAI_61_PAI',
                \DB::raw('RANK() OVER (ORDER BY SISWA_SKOR DESC) AS ranking')
            )
            ;

        // ── Filter jalur ────────────────────────────────────────────────────
        if ($request->filled('jalur') && $request->jalur !== 'all') {
            $query->where('SISWA_JALUR', $request->jalur);
        }

        // ── Wrap subquery supaya RANK() bisa di-filter & di-count ───────────
        $wrapped = \DB::table(\DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query);

        $totalRecords = $wrapped->count();

        // ── Global search ───────────────────────────────────────────────────
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $wrapped->where(function ($q) use ($search) {
                $q->where('SISWA_NAMA', 'like', "%{$search}%")
                ->orWhere('SISWA_NISN', 'like', "%{$search}%");
            });
        }

        $filteredRecords = $wrapped->count();

        // ── Sorting (hanya ranking yang orderable di front-end) ─────────────
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $wrapped->orderBy('ranking', $orderDir);

        // ── Pagination ──────────────────────────────────────────────────────
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $rows   = $wrapped->offset($start)->limit($length)->get();

        // ── Label jalur ─────────────────────────────────────────────────────
        $jalurLabels = [
            'JALUR_REGULER'  => ['label' => 'Reguler',  'class' => 'bg-blue-100 text-blue-700'],
            'JALUR_PRESTASI' => ['label' => 'Prestasi', 'class' => 'bg-violet-100 text-violet-700'],
            'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'class' => 'bg-amber-100 text-amber-700'],
        ];

        $data = $rows->map(function ($row) use ($jalurLabels) {
            $jalur = $jalurLabels[$row->SISWA_JALUR]
                ?? ['label' => $row->SISWA_JALUR, 'class' => 'bg-gray-100 text-gray-500'];

            $avatarLetter = strtoupper(substr($row->SISWA_NAMA, 0, 1));

            $judulHtml = "
                <div class='flex items-center gap-3'>
                    
                    <div>
                        <div class='font-medium text-gray-800'>{$row->SISWA_NAMA}</div>
                        <span class='inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {$jalur['class']}'>
                            {$jalur['label']}
                        </span>
                    </div>
                </div>
            ";

            $skorHtml = "
                <span class='font-semibold text-gray-700'>{$row->SISWA_SKOR}</span>
            ";

            $rankHtml = "
                <span class='inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-600 font-bold text-xs'>
                    {$row->ranking}
                </span>
            ";

            $aksiHtml = "
                <button onclick='openDetail({$row->SISWA_ID})'
                    class='p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition' title='Detail'>
                    <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                            d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                            d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'/>
                    </svg>
                </button>
            ";

            // hitung komponen skor (sama persis logika hitungSkor())
            $nilai = [
                $row->SISWA_NILAI_52_MTK, $row->SISWA_NILAI_52_IPA,
                $row->SISWA_NILAI_52_BIND, $row->SISWA_NILAI_52_PAI,
                $row->SISWA_NILAI_61_MTK, $row->SISWA_NILAI_61_IPA,
                $row->SISWA_NILAI_61_BIND, $row->SISWA_NILAI_61_PAI,
            ];
            $A = array_sum($nilai) / count($nilai);
            $B = $row->SISWA_TES_CBT_AKADEMIK;
            $C = $row->SISWA_TES_CBT_PSIKO;
            $D = $row->SISWA_TES_QURAN;
            $E = skorKhusus($row->SISWA_AFIRMASI);
            $F = skorKhusus($row->SISWA_PRESTASI_KEJUARAAN);
            $G = skorKhusus($row->SISWA_PRESTASI_KEAGAMAAN);
            $H = \Carbon\Carbon::parse($row->SISWA_TGL_LAHIR)->age;

            return [
                'ranking' => $rankHtml,
                'judul'   => $judulHtml,
                'skor'    => $skorHtml,
                'aksi'    => $aksiHtml,
                '_raw' => [
                    'id'     => $row->SISWA_ID,
                    'nama'   => $row->SISWA_NAMA,
                    'jalur'  => $row->SISWA_JALUR,
                    'skor'   => $row->SISWA_SKOR,
                    'status' => $row->SISWA_STATUS,
                    'nisn'   => $row->SISWA_NISN,
                    'skor_detail' => [          // <-- tambahan
                        'A' => $A,
                        'B' => $B,
                        'C' => $C,
                        'D' => $D,
                        'E' => $E,
                        'F' => $F,
                        'G' => $G,
                        'H' => $H,
                    ],
                ],
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
    }


}
