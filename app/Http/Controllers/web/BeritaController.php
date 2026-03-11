<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BeritaController extends Controller
{
    /** Daftar semua berita published dengan filter kategori & load more */
    public function index(Request $request)
    {
        $kategoriList  = Berita::kategoriList();
        $kategoriAktif = $request->get('kategori');

        $beritaQuery = Berita::published();

        if ($kategoriAktif && in_array($kategoriAktif, $kategoriList)) {
            $beritaQuery->kategori($kategoriAktif);
        }

        $beritaList = $beritaQuery->paginate(9)->withQueryString();

        // Kalau request dari AJAX (load more), return JSON berisi HTML card + info halaman
        if ($request->ajax()) {
            $html = view('berita._card', ['beritaList' => $beritaList])->render();
            return response()->json([
                'html'     => $html,
                'hasMore'  => $beritaList->hasMorePages(),
                'nextPage' => $beritaList->currentPage() + 1,
            ]);
        }

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

    private $allowedRoles = ['ROLE_SUPERADMIN', 'ROLE_ADMIN_PMBM'];

    // -------------------------------------------------------
    // GET /admin/berita — halaman tabel index
    // -------------------------------------------------------
    public function list(Request $request)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        return view('admin.berita.index');
    }

    // -------------------------------------------------------
    // GET /admin/berita/data — JSON untuk DataTables server-side
    // -------------------------------------------------------
    public function data(Request $request)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $draw   = $request->input('draw', 1);
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');

        $query = Berita::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('BERITA_JUDUL', 'like', "%{$search}%")
                  ->orWhere('BERITA_KATEGORI', 'like', "%{$search}%");
            });
        }

        $total    = Berita::count();
        $filtered = $query->count();

        $data = $query->orderBy('BERITA_WAKTU_BUAT', 'desc')
                      ->skip($start)
                      ->take($length)
                      ->get();

        $rows = $data->map(function ($b, $i) use ($start) {
            $badgeStatus = $b->BERITA_STATUS === 'published'
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Published</span>'
                : '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Draft</span>';

            $badgeKategori = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ' . $b->badge_color . '">' . $b->BERITA_KATEGORI . '</span>';

            $thumbnail = $b->BERITA_THUMBNAIL
                ? '<img src="' . asset('storage/' . $b->BERITA_THUMBNAIL) . '" class="w-12 h-12 object-cover rounded-lg">'
                : '<div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-image text-lg"></i></div>';

            $aksi = '
                <div class="flex items-center gap-2">
                    <button onclick="toggleStatus(' . $b->BERITA_ID . ', \'' . $b->BERITA_STATUS . '\')"
                        class="btn-toggle-status px-2 py-1 rounded text-xs font-semibold ' .
                        ($b->BERITA_STATUS === 'published'
                            ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                            : 'bg-green-100 text-green-700 hover:bg-green-200') . '
                        transition-colors" title="' . ($b->BERITA_STATUS === 'published' ? 'Tarik ke Draft' : 'Publish') . '">
                        <i class="fas ' . ($b->BERITA_STATUS === 'published' ? 'fa-eye-slash' : 'fa-eye') . '"></i>
                    </button>
                    <a href="' . route('admin.berita.edit', $b->BERITA_ID) . '"
                       class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                        <i class="fas fa-pencil"></i>
                    </a>
                    <button onclick="hapusBerita(' . $b->BERITA_ID . ')"
                        class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ';

            return [
                'no'        => $start + $i + 1,
                'thumbnail' => $thumbnail,
                'judul'     => '<span class="font-medium text-gray-800">' . e($b->BERITA_JUDUL) . '</span>',
                'kategori'  => $badgeKategori,
                'status'    => $badgeStatus,
                'tanggal'   => $b->tanggal_publish,
                'aksi'      => $aksi,
            ];
        });

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $rows,
        ]);
    }

    // -------------------------------------------------------
    // GET /admin/berita/create
    // -------------------------------------------------------
    public function create(Request $request)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            abort(403);
        }

        $kategoriList = Berita::kategoriList();
        return view('admin.berita.form', compact('kategoriList'));
    }

    // -------------------------------------------------------
    // POST /admin/berita — simpan berita baru
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $request->validate([
            'BERITA_JUDUL'    => 'required|string|max:255',
            'BERITA_KATEGORI' => 'required|in:'.implode(",", Berita::kategoriList()),
            'BERITA_ISI'      => 'required|string',
            'BERITA_STATUS'   => 'required|in:draft,published',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('berita/thumbnail', 'public');
            }

            $slug = Berita::generateSlug($request->BERITA_JUDUL);

            Berita::create([
                'BERITA_JUDUL'        => $request->BERITA_JUDUL,
                'BERITA_SLUG'         => $slug,
                'BERITA_THUMBNAIL'    => $thumbnailPath,
                'BERITA_KATEGORI'     => $request->BERITA_KATEGORI,
                'BERITA_ISI'          => $request->BERITA_ISI,
                'BERITA_STATUS'       => $request->BERITA_STATUS,
                'BERITA_PUBLISHED_AT' => $request->BERITA_STATUS === 'published' ? now() : null,
                'BERITA_WAKTU_BUAT'   => now(),
                'BERITA_WAKTU_UBAH'   => now(),
            ]);

            DB::commit();
            return compose("SUCCESS", "Berita berhasil disimpan");
        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // GET /admin/berita/{id}/edit
    // -------------------------------------------------------
    public function edit(Request $request, $id)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            abort(403);
        }

        $berita       = Berita::findOrFail($id);
        $kategoriList = Berita::kategoriList();
        return view('admin.berita.form', compact('berita', 'kategoriList'));
    }

    // -------------------------------------------------------
    // POST /admin/berita/{id} — update berita
    // -------------------------------------------------------
    public function update(Request $request, $id)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        $request->validate([
            'BERITA_JUDUL'    => 'required|string|max:255',
            'BERITA_KATEGORI' => 'required|in:Pengumuman,Kegiatan,Prestasi,Lainnya',
            'BERITA_ISI'      => 'required|string',
            'BERITA_STATUS'   => 'required|in:draft,published',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $berita = Berita::findOrFail($id);

            $thumbnailPath = $berita->BERITA_THUMBNAIL;

            // Hapus thumbnail lama jika ada file baru
            if ($request->hasFile('thumbnail')) {
                if ($thumbnailPath) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
                $thumbnailPath = $request->file('thumbnail')->store('berita/thumbnail', 'public');
            }

            // Hapus thumbnail jika user klik hapus
            if ($request->input('hapus_thumbnail') == '1') {
                if ($thumbnailPath) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
                $thumbnailPath = null;
            }

            // Regenerate slug hanya jika judul berubah
            $slug = $berita->BERITA_SLUG;
            if ($request->BERITA_JUDUL !== $berita->BERITA_JUDUL) {
                $slug = Berita::generateSlug($request->BERITA_JUDUL);
            }

            // Set published_at hanya saat pertama kali publish
            $publishedAt = $berita->BERITA_PUBLISHED_AT;
            if ($request->BERITA_STATUS === 'published' && !$publishedAt) {
                $publishedAt = now();
            } elseif ($request->BERITA_STATUS === 'draft') {
                $publishedAt = null;
            }

            $berita->update([
                'BERITA_JUDUL'        => $request->BERITA_JUDUL,
                'BERITA_SLUG'         => $slug,
                'BERITA_THUMBNAIL'    => $thumbnailPath,
                'BERITA_KATEGORI'     => $request->BERITA_KATEGORI,
                'BERITA_ISI'          => $request->BERITA_ISI,
                'BERITA_STATUS'       => $request->BERITA_STATUS,
                'BERITA_PUBLISHED_AT' => $publishedAt,
                'BERITA_WAKTU_UBAH'   => now(),
            ]);

            DB::commit();
            return compose("SUCCESS", "Berita berhasil diperbarui");
        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // DELETE /admin/berita/{id}
    // -------------------------------------------------------
    public function destroy(Request $request, $id)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        DB::beginTransaction();
        try {
            $berita = Berita::findOrFail($id);

            if ($berita->BERITA_THUMBNAIL) {
                Storage::disk('public')->delete($berita->BERITA_THUMBNAIL);
            }

            $berita->delete();

            DB::commit();
            return compose("SUCCESS", "Berita berhasil dihapus");
        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // POST /admin/berita/{id}/toggle-status
    // -------------------------------------------------------
    public function toggleStatus(Request $request, $id)
    {
        $loginUser = $request->loginUser;
        if (!in_array($loginUser->U_ROLE, $this->allowedRoles)) {
            return compose("ERROR", "Anda tidak berhak mengakses");
        }

        DB::beginTransaction();
        try {
            $berita = Berita::findOrFail($id);

            if ($berita->BERITA_STATUS === 'published') {
                $berita->update([
                    'BERITA_STATUS'       => 'draft',
                    'BERITA_PUBLISHED_AT' => null,
                    'BERITA_WAKTU_UBAH'   => now(),
                ]);
                $msg = "Berita ditarik ke draft";
            } else {
                $berita->update([
                    'BERITA_STATUS'       => 'published',
                    'BERITA_PUBLISHED_AT' => $berita->BERITA_PUBLISHED_AT ?? now(),
                    'BERITA_WAKTU_UBAH'   => now(),
                ]);
                $msg = "Berita berhasil dipublish";
            }

            DB::commit();
            return compose("SUCCESS", $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            return compose("ERROR", "Terjadi kesalahan internal", $e->getMessage());
        }
    }

}