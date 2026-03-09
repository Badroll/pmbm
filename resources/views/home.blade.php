@extends('layouts.app')

@section('title', 'PMBM')

@section('content')

<!-- Banner Utama -->
<section id="beranda"
    class="relative text-white py-20 px-4 bg-cover bg-center"
    style="background-image: url('{{ asset('images/banner.jpeg') }}');">

    <!-- Overlay biar teks tetap kebaca -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="relative max-w-7xl mx-auto text-center">
        <div class="mb-8">
            <!-- <i class="fas fa-school text-6xl mb-4"></i> -->
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Penerimaan Murid Baru Madrasah
        </h1>
        <p class="text-xl md:text-2xl mb-8">
            Tahun Ajaran 2026/2027
        </p>
        <p class="text-lg mb-8 max-w-3xl mx-auto">
            Religius, Berprestasi, Berakhlakul Karimah, Terampil dalam Teknologi, dan Berbudaya Lingkungan
        </p>
        <a href="#jalur">
            <button
                class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-blue-50 transition shadow-lg">
                Daftar Sekarang
            </button>
        </a>
    </div>
</section>


<!-- Alur Pendaftaran -->
<section id="alur" class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-800">Alur Pendaftaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                <i class="fas fa-user-alt text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Buat Akun</h3>
                <p class="text-gray-600 text-sm">Buat akun untuk mengakses website</p>
            </div>
            <!--  -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                <i class="fas fa-file-alt text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Daftar</h3>
                <p class="text-gray-600 text-sm">Isi formulir pendaftaran secara online melalui website</p>
            </div>
            <!-- Step 3 -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                <i class="fas fa-clipboard-check text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Verifikasi</h3>
                <p class="text-gray-600 text-sm">Verifikasi berkas pendaftaran di Madrasah</p>
            </div>
            <!-- Step 3 -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">4</div>
                <i class="fas fa-pencil text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Tes Seleksi</h3>
                <p class="text-gray-600 text-sm">Tes Seleksi penerimaan di Madrasah</p>
            </div>
            <!-- Step 4 -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">5</div>
                <i class="fas fa-newspaper text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Pengumuman</h3>
                <p class="text-gray-600 text-sm">Hasil tes seleksi diumumkan</p>
            </div>
            <!-- Step 2 -->
            <div class="text-center p-6 bg-blue-50 rounded-lg hover:shadow-lg transition">
                <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">6</div>
                <i class="fas fa-upload text-blue-600 text-3xl mb-3"></i>
                <h3 class="font-semibold text-lg mb-2">Daftar Ulang</h3>
                <p class="text-gray-600 text-sm">Daftar ulang dengan mengunggah berka</p>
            </div>
        </div>
    </div>
</section>

<!-- Syarat Pendaftaran -->
<section id="syarat" class="py-16 px-4 bg-gray-100">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-800">Syarat Pendaftaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">
                    <!-- <i class="fas fa-clipboard-list mr-2"></i> -->
                    Persyaratan Umum
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Beragama islam</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Mampu membaca Al-Qur'an dengan baik</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Berusia paling tinggi 15 tahun pada 1 Juli 2026</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Fotokopi rapor kelas 5 semester 2 dan kelas 6 semester 1</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Melampirkan SKL asli (jika sudah terbit)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Fotokopi NISN</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Fotokopi Kartu Keluarga</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Fotokopi akta kelahiran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Pas foto 3x4 sebanyak 2 lembar</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Fotokopi KIP/PIP/KKS/PKH atauu Surat Keterangan Tidak Mampu (SKTM) yang diterbitkan oleh pemerintah daerah (kabupaten/kota) bagi yang memiliki</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">
                    <!-- <i class="fas fa-clipboard-list mr-2"></i> -->
                    Persyaratan Khusus
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Bagi pendaftar jalur prestasi wajib melampirkan;
                            <ul>
                                <li>• fotokopi piagam kejuaraan bidang akademik maupun non-akademik minimal tingkat Kota/Kabupaten dan menunjuukan piagam asli, atau;</li>
                                <li>• sertifikat hafalan Al - Qur'an minimal 30 juz</li>
                            </ul>
                        </span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Bagi pendaftar jalur afirmasi wajib melampirkan fotokopi KIP/PIP/KKS/PKH atau Surat Keterangan Tidak Mampu (SKTM) yang diterbitkan oleh pemerintah daerah</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-angle-right text-green-500 mr-3 mt-1"></i>
                        <span>Seluruh pendaftar wajib mengikuti rangkaian seleksi CBT (Akademik dan Psikotest) dan baca Al Qur'an</span>
                    </li>
                </ul>
            </div>

            <!-- <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">
                    <i class="fas fa-folder-open mr-2"></i>Dokumen yang Diperlukan
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-file text-blue-500 mr-3 mt-1"></i>
                        <span>Fotokopi Ijazah/SKHUN yang dilegalisir</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-file text-blue-500 mr-3 mt-1"></i>
                        <span>Fotokopi Kartu Keluarga</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-file text-blue-500 mr-3 mt-1"></i>
                        <span>Fotokopi Akta Kelahiran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-file text-blue-500 mr-3 mt-1"></i>
                        <span>Pas foto 3x4 (4 lembar)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-file text-blue-500 mr-3 mt-1"></i>
                        <span>Surat keterangan sehat dari dokter</span>
                    </li>
                </ul>
            </div> -->

        </div>
    </div>
</section>

<!-- Timeline -->
<section id="timeline" class="py-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-800">Timeline Pendaftaran</h2>
        
        <div class="max-w-4xl mx-auto">
            <div class="space-y-8">

                <!-- pass -->
                <div class="flex gap-6">
                    <div class="w-40 flex-shrink-0 text-right">
                        <div class="text-sm font-semibold text-gray-400">Sekarang - 29 Maret 2026</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-gray-400"></div>
                        <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-semibold text-gray-400 mb-1">Pra Pendaftaran</h3>
                        <p class="text-sm text-gray-400">Persiapan PMBM</p>
                    </div>
                </div>

                <!-- aktif -->
                <div class="flex gap-6">
                    <div class="w-40 flex-shrink-0 text-right">
                        <div class="text-sm font-semibold text-green-600">30 Maret - 13 Mei 2026</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500 ring-4 ring-green-200"></div>
                        <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-semibold text-green-600 mb-1">Pendaftaran Murid</h3>
                        <p class="text-sm text-gray-700">Periode pendaftaran murid secara online melalui website</p>
                        <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Sedang Berjalan</span>
                    </div>
                </div>

                <!-- coming soon -->
                <div class="flex gap-6">
                    <div class="w-40 flex-shrink-0 text-right">
                        <div class="text-sm font-semibold text-blue-400">18 - 23 Mei 2026</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full border-2 border-blue-400 bg-white"></div>
                        <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-semibold text-blue-400 mb-1">Verifikasi Berkas dan Tes Seleksi</h3>
                        <p class="text-sm text-gray-500">Verifikasi berkas pendaftaran dan tes seleksi penerimaan di Madrasah</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-40 flex-shrink-0 text-right">
                        <div class="text-sm font-semibold text-blue-400">26 Mei 2026</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full border-2 border-blue-400 bg-white"></div>
                        <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-semibold text-blue-400 mb-1">Pengumuman</h3>
                        <p class="text-sm text-gray-500">Pengumuman hasil seleksi penerimaan</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-40 flex-shrink-0 text-right">
                        <div class="text-sm font-semibold text-blue-400">28 - 29 Mei 2026</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full border-2 border-blue-400 bg-white"></div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-blue-400 mb-1">Daftar Ulang</h3>
                        <p class="text-sm text-gray-500">Periode daftar ulang murid diterima di Madrasah</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- Jalur Pendaftaran -->
<section id="jalur" class="py-16 px-4 bg-gray-100">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-800">Jalur Pendaftaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Jalur Reguler -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition border-2 border-blue-600 flex flex-col">
                <div class="text-center mb-6">
                    <i class="fas fa-users text-blue-600 text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Jalur Reguler</h3>
                </div>
                <ul class="space-y-3 text-gray-700 mb-6 flex-grow">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Nilai Raport</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Tes Baca Al Qur'an</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>CBT (Tes Akademik & Psikotest)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Umur</span>
                    </li>
                </ul>
                <div class="text-center mt-auto">
                    <a href="{{ url('daftar') }}?jalur=reguler">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 w-full">
                            Daftar Sekarang
                        </button>
                    </a>
                </div>
            </div>

            <!-- Jalur Prestasi -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition border-2 border-blue-600 relative flex flex-col">
                <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Kuota Terbatas
                </span>
                <div class="text-center mb-6">
                    <i class="fas fa-trophy text-blue-600 text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Jalur Prestasi</h3>
                </div>
                <ul class="space-y-3 text-gray-700 mb-6 flex-grow">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Nilai Raport</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Kejuaraan Akademik atau non Akademik</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Tes Baca dan Hafalan Al Qur'an minimal 3 Juz</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>CBT (tes Akademik dan Psikotest)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Umur</span>
                    </li>
                </ul>
                <div class="text-center mt-auto">
                    <a href="{{ url('daftar') }}?jalur=prestasi">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 w-full">
                            Daftar Sekarang
                        </button>
                    </a>
                </div>
            </div>

            <!-- Jalur Afirmasi -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition border-2 border-blue-600 relative flex flex-col">
                <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Kuota Terbatas
                </span>
                <div class="text-center mb-6">
                    <i class="fas fa-hand-holding-heart text-blue-600 text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Jalur Afirmasi</h3>
                </div>
                <ul class="space-y-3 text-gray-700 mb-6 flex-grow">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Nilai Raport</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Tes Baca Al Qur'an</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>CBT (tes Akademik dan non Akademik)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Pemegang Kartu KIP/PIP/PKS/PKH</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Warga Wilayah Matsanda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Anak GTK dan Komite Matsanda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Anak Guru</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Anak ASN Kemenag</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Umur</span>
                    </li>
                </ul>
                <div class="text-center mt-auto">
                    <a href="{{ url('daftar') }}?jalur=afirmasi">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 w-full">
                            Daftar Sekarang
                        </button>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>

@push('scripts')
<script>
// showFlashMessage('success', 'Berhasil!', 'Data telah disimpan');
// showFlashMessage('error', 'Error!', 'Terjadi kesalahan');
// showFlashMessage('warning', 'Peringatan!', 'Harap lengkapi data');
// showFlashMessage('info', 'Informasi', 'Proses sedang berjalan');
</script>
@endpush
@endsection