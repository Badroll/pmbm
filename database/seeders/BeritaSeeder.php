<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'judul'    => 'Pendaftaran PPDB MTs Tahun Ajaran 2025/2026 Resmi Dibuka',
                'kategori' => 'Pengumuman',
                'isi'      => '<p>Dengan penuh rasa syukur, kami dengan bangga mengumumkan bahwa <strong>Penerimaan Peserta Didik Baru (PPDB) MTs</strong> untuk Tahun Ajaran 2025/2026 resmi dibuka mulai tanggal 1 Februari 2025.</p><p>Calon peserta didik dapat mendaftarkan diri secara online melalui website ini maupun datang langsung ke sekretariat sekolah pada hari dan jam kerja.</p><h2>Persyaratan Umum</h2><ul><li>Ijazah atau Surat Keterangan Lulus SD/MI</li><li>Akta Kelahiran (fotokopi)</li><li>Kartu Keluarga (fotokopi)</li><li>Pas foto 3x4 sebanyak 4 lembar</li></ul><p>Untuk informasi lebih lanjut, silakan hubungi panitia PPDB melalui nomor yang tertera di halaman kontak kami.</p>',
                'status'   => 'published',
            ],
            [
                'judul'    => 'MTs Raih Juara 1 Olimpiade Sains Tingkat Kabupaten 2024',
                'kategori' => 'Prestasi',
                'isi'      => '<p>Alhamdulillah, siswa-siswi MTs kembali mengharumkan nama sekolah di ajang <strong>Olimpiade Sains Kabupaten (OSK) 2024</strong>. Tiga siswa terbaik kami berhasil meraih podium tertinggi dalam bidang Matematika, IPA, dan IPS.</p><p>Pencapaian gemilang ini merupakan hasil dari kerja keras para siswa dan bimbingan intensif dari guru-guru berdedikasi kami selama beberapa bulan persiapan.</p><p>"Prestasi ini membuktikan bahwa kualitas pendidikan di MTs terus meningkat dari tahun ke tahun," ujar Kepala Sekolah dalam sambutannya.</p><p>Kami berharap prestasi ini dapat menjadi motivasi bagi seluruh warga sekolah untuk terus berprestasi dan mengangkat nama baik madrasah.</p>',
                'status'   => 'published',
            ],
            [
                'judul'    => 'Kegiatan Pesantren Kilat Ramadan 1446 H Berjalan Khidmat',
                'kategori' => 'Kegiatan',
                'isi'      => '<p>Dalam rangka menyambut bulan suci Ramadan 1446 H, MTs menyelenggarakan <strong>Pesantren Kilat</strong> yang berlangsung selama 5 hari, mulai dari tanggal 10 hingga 14 Maret 2025.</p><p>Kegiatan ini diikuti oleh seluruh siswa kelas VII, VIII, dan IX dengan penuh antusias. Rangkaian acara meliputi tadarus Al-Qur\'an bersama, ceramah agama, shalat berjamaah, serta lomba-lomba keislaman antar kelas.</p><p>Tujuan utama pesantren kilat ini adalah untuk mempererat tali silaturahmi, meningkatkan keimanan dan ketakwaan, serta membekali siswa dengan nilai-nilai islami yang kuat di bulan yang penuh berkah ini.</p>',
                'status'   => 'published',
            ],
            [
                'judul'    => 'Pengumuman Jadwal Seleksi dan Tes Masuk PPDB 2025',
                'kategori' => 'Pengumuman',
                'isi'      => '<p>Kepada seluruh calon peserta didik baru yang telah mendaftarkan diri, berikut kami umumkan jadwal resmi seleksi dan tes masuk PPDB MTs Tahun Ajaran 2025/2026.</p><h2>Jadwal Seleksi</h2><ul><li><strong>Tes Akademik:</strong> 15 Maret 2025, pukul 08.00 - 11.00 WIB</li><li><strong>Tes Baca Al-Qur\'an:</strong> 15 Maret 2025, pukul 13.00 - 16.00 WIB</li><li><strong>Pengumuman Hasil:</strong> 20 Maret 2025</li><li><strong>Daftar Ulang:</strong> 21 - 28 Maret 2025</li></ul><p>Peserta diwajibkan hadir tepat waktu dan membawa kartu peserta yang telah dicetak dari website. Informasi lebih lanjut akan disampaikan melalui website dan grup WhatsApp resmi PPDB.</p>',
                'status'   => 'published',
            ],
            [
                'judul'    => 'Kunjungan Edukatif Siswa Kelas VIII ke Museum Nasional Jakarta',
                'kategori' => 'Kegiatan',
                'isi'      => '<p>Sebagai bagian dari program pembelajaran kontekstual, siswa-siswi kelas VIII MTs melaksanakan kunjungan edukatif ke <strong>Museum Nasional Indonesia</strong> di Jakarta pada Sabtu, 8 Februari 2025.</p><p>Sebanyak 120 siswa yang didampingi 8 guru pembimbing berangkat dengan penuh semangat. Di museum, para siswa belajar langsung tentang sejarah dan kebudayaan Indonesia melalui koleksi artefak bersejarah yang tersimpan di sana.</p><p>Kegiatan ini bertujuan untuk menumbuhkan rasa cinta tanah air, memperluas wawasan, dan melengkapi materi pelajaran IPS dan Sejarah yang telah dipelajari di kelas.</p>',
                'status'   => 'published',
            ],
            [
                'judul'    => 'Draft: Persiapan Acara Perpisahan Kelas IX 2025',
                'kategori' => 'Kegiatan',
                'isi'      => '<p>Ini adalah draft berita untuk acara perpisahan. Belum dipublikasikan.</p>',
                'status'   => 'draft',
            ],
        ];

        foreach ($data as $item) {
            $slug = Str::slug($item['judul']);
            DB::table('berita')->insert([
                'BERITA_JUDUL'        => $item['judul'],
                'BERITA_SLUG'         => $slug,
                'BERITA_THUMBNAIL'    => null,
                'BERITA_KATEGORI'     => $item['kategori'],
                'BERITA_ISI'          => $item['isi'],
                'BERITA_STATUS'       => $item['status'],
                'BERITA_PUBLISHED_AT' => $item['status'] === 'published'
                    ? now()->subDays(rand(1, 30))
                    : null,
                'BERITA_WAKTU_BUAT'   => now(),
                'BERITA_WAKTU_UBAH'   => now(),
            ]);
        }
    }
}