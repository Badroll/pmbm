<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table      = 'berita';
    protected $primaryKey = 'BERITA_ID';
    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = [
    ];

    protected $casts = [
        'BERITA_PUBLISHED_AT' => 'datetime',
        'BERITA_WAKTU_BUAT'   => 'datetime',
        'BERITA_WAKTU_UBAH'   => 'datetime',
    ];


    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    /** Hanya berita yang sudah published */
    public function scopePublished($query)
    {
        return $query->where('BERITA_STATUS', 'published')
                     ->orderBy('BERITA_PUBLISHED_AT', 'desc');
    }

    /** Filter berdasarkan kategori */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('BERITA_KATEGORI', $kategori);
    }


    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    /** Excerpt dari isi (strip HTML, ambil 150 karakter) */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->BERITA_ISI), 150);
    }

    /** URL thumbnail atau placeholder */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->BERITA_THUMBNAIL) {
            return asset('storage/' . $this->BERITA_THUMBNAIL);
        }
        // Placeholder dengan warna berbeda per kategori
        $colors = [
            'Informasi' => '3B82F6',
            'Pengumuman'   => '10B981',
            'Peringatan'   => 'F59E0B',
            'Lainnya'    => '6B7280',
        ];
        $color = $colors[$this->BERITA_KATEGORI] ?? '6B7280';
        return "https://placehold.co/800x450/{$color}/ffffff?text=" . urlencode($this->BERITA_KATEGORI);
    }

    /** Warna badge kategori (Tailwind class) */
    public function getBadgeColorAttribute(): string
    {
        switch ($this->BERITA_KATEGORI) {
            case 'Informasi': return 'bg-blue-100 text-blue-700';
            case 'Pengumuman':   return 'bg-green-100 text-green-700';
            case 'Peringatan':   return 'bg-yellow-100 text-yellow-700';
            default:           return 'bg-gray-100 text-gray-700';
        }
    }

    /** Format tanggal publish untuk tampilan */
    public function getTanggalPublishAttribute(): string
    {
        if (!$this->BERITA_PUBLISHED_AT) return '-';
        //return $this->BERITA_PUBLISHED_AT->translatedFormat('d F Y');
        return substr(tanggal($this->BERITA_PUBLISHED_AT, "LONG"), 0, -3);
    }


    // -------------------------------------------------------
    // Static Helper
    // -------------------------------------------------------

    /** Generate slug unik dari judul */
    public static function generateSlug(string $judul, ?int $exceptId = null): string
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $i = 1;

        while (true) {
            $query = static::where('BERITA_SLUG', $slug);
            if ($exceptId) $query->where('BERITA_ID', '!=', $exceptId);
            if (!$query->exists()) break;
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    /** Daftar kategori yang tersedia */
    public static function kategoriList(): array
    {
        return ['Pengumuman', 'Informasi', 'Peringatan', 'Lainnya'];
    }

}