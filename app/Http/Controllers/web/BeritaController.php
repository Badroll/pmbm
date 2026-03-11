<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /** Daftar semua berita published dengan filter kategori & pagination */
    public function index(Request $request)
    {
        $kategoriList = ['Pengumuman', 'Kegiatan', 'Prestasi', 'Lainnya'];
        $kategoriAktif = $request->get('kategori');

        $beritaQuery = Berita::published();

        if ($kategoriAktif && in_array($kategoriAktif, $kategoriList)) {
            $beritaQuery->kategori($kategoriAktif);
        }

        $beritaList = $beritaQuery->paginate(9)->withQueryString();

        return view('berita.index', compact('beritaList', 'kategoriList', 'kategoriAktif'));
    }

    /** Detail satu berita berdasarkan slug */
    public function show(string $slug)
    {
        $berita = Berita::published()
            ->where('BERITA_SLUG', $slug)
            ->firstOrFail();

        // Berita terkait: kategori sama, exclude berita ini, maks 3
        $beritaTerkait = Berita::published()
            ->kategori($berita->BERITA_KATEGORI)
            ->where('BERITA_ID', '!=', $berita->BERITA_ID)
            ->limit(3)
            ->get();

        return view('berita.show', compact('berita', 'beritaTerkait'));
    }
}