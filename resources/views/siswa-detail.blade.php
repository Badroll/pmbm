@extends('layouts.app')
@section('title', 'Detail Pendaftar - ' . $siswa->SISWA_NAMA)
@section('content')

@php
    $jalurMap = [
        'JALUR_REGULER'  => ['label' => 'Reguler',  'class' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200'],
        'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'class' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200'],
        'JALUR_PRESTASI' => ['label' => 'Prestasi', 'class' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200'],
    ];
    $statusMap = [
        'STATUS_PENDING'       => ['label' => 'Pendaftaran Terkirim', 'class' => 'text-amber-700 bg-amber-100 ring-1 ring-amber-200', 'dot' => 'bg-amber-400'],
        'STATUS_TERVERIFIKASI' => ['label' => 'Terverifikasi',        'class' => 'text-blue-700 bg-blue-50 ring-1 ring-blue-200',   'dot' => 'bg-blue-400'],
        'STATUS_MENUNGGU'      => ['label' => 'Menunggu Hasil Tes',   'class' => 'text-yellow-800 bg-yellow-100 ring-1 ring-yellow-300', 'dot' => 'bg-yellow-500'],
    ];
    $jalur  = $jalurMap[$siswa->SISWA_JALUR]  ?? ['label' => $siswa->SISWA_JALUR,  'class' => 'bg-gray-100 text-gray-600'];
    $status = $statusMap[$siswa->SISWA_STATUS] ?? ['label' => $siswa->SISWA_STATUS, 'class' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'];

    // Format tanggal Indonesia manual (tidak butuh locale Carbon)
    $bulanId = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $hariId  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    $tglLahir  = \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR);
    $tglDaftar = \Carbon\Carbon::parse($siswa->SISWA_TGL_DAFTAR);

    $formatTglLahir  = $tglLahir->day . ' ' . $bulanId[$tglLahir->month] . ' ' . $tglLahir->year;
    $formatTglDaftar = $hariId[$tglDaftar->dayOfWeek] . ', '
                     . $tglDaftar->day . ' ' . $bulanId[$tglDaftar->month] . ' ' . $tglDaftar->year
                     . ' pukul ' . $tglDaftar->format('H:i');
@endphp

<style>
    .detail-card { background: #fff; border-radius: 20px; border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.03); overflow: hidden; }
    .section-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hero-gradient { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%); }
    .file-item { transition: all .15s ease; }
    .file-item:hover { transform: translateY(-1px); }
    .nilai-badge { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
    .pulse-dot::before { content: ''; position: absolute; inset: 0; border-radius: 50%; background: inherit; animation: ping 1.5s cubic-bezier(0,0,.2,1) infinite; opacity: .6; }
    @keyframes ping { 75%,100% { transform: scale(2); opacity: 0; } }
</style>

<div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8" style="background: #f7f7fb;">

    {{-- Back --}}
    <div class="mb-5">
        <a href="/siswa" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- ═══ HERO PROFILE CARD ═══ --}}
    <div class="detail-card mb-4 overflow-hidden">
        {{-- Top gradient strip --}}
        <div class="hero-gradient h-2"></div>

        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-5 items-start">

                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    @if($siswa->SISWA_FILE_FOTO)
                        <img src="{{ asset('storage/' . $siswa->SISWA_FILE_FOTO) }}"
                            alt="Foto {{ $siswa->SISWA_NAMA }}"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover ring-4 ring-indigo-100">
                    @else
                        <div class="hero-gradient w-20 h-20 sm:w-24 sm:h-24 rounded-2xl flex items-center justify-center ring-4 ring-indigo-100">
                            <span class="text-3xl font-black text-white">{{ strtoupper(substr($siswa->SISWA_NAMA, 0, 1)) }}</span>
                        </div>
                    @endif
                    {{-- Status pulse dot --}}
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full {{ $status['dot'] }} ring-2 ring-white relative pulse-dot"></span>
                </div>

                {{-- Main Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                            {{ $siswa->SISWA_NAMA }}
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="font-mono text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-md">
                            #{{ str_pad($siswa->SISWA_ID, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                            {{ $status['label'] }}
                        </span>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $jalur['class'] }}">
                            {{ $jalur['label'] }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $siswa->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $formatTglLahir }}
                            <span class="text-gray-400">({{ \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR)->age }} thn)</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $siswa->SISWA_WA }}
                        </span>
                    </div>
                </div>

                {{-- Total Skor --}}
                <div class="flex-shrink-0 text-center sm:text-right self-start sm:self-center">
                    <div class="inline-block nilai-badge rounded-2xl px-5 py-3">
                        <p class="text-xs font-semibold text-purple-500 mb-0.5 tracking-wider uppercase">Total Skor</p>
                        <p class="text-4xl font-black text-indigo-700 leading-none">{{ number_format($siswa->SISWA_SKOR, 1) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Status Form (SOON) --}}
        <!-- SOON -->
        <!-- <div class="mt-4 pt-4 border-t border-gray-50">
            ...
        </div> -->
    </div>

    <div class="space-y-4">

        {{-- ═══ 1. DATA PRIBADI ═══ --}}
        <div class="detail-card">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-indigo-100">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Data Pribadi</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @include('pendaftar._detail-item', ['label' => 'NISN', 'value' => $siswa->SISWA_NISN, 'mono' => true])
                    @include('pendaftar._detail-item', ['label' => 'Tempat Lahir', 'value' => $siswa->kotaTempatLahir->KOTA_JENIS . " " . $siswa->kotaTempatLahir->KOTA_NAMA])
                    @include('pendaftar._detail-item', ['label' => 'Tanggal Lahir', 'value' => $formatTglLahir])
                    @include('pendaftar._detail-item', ['label' => 'Jenis Kelamin', 'value' => $siswa->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'Laki-laki' : 'Perempuan'])
                    @include('pendaftar._detail-item', ['label' => 'Nama Ayah', 'value' => $siswa->SISWA_AYAH])
                    @include('pendaftar._detail-item', ['label' => 'Nama Ibu', 'value' => $siswa->SISWA_IBU])
                    @include('pendaftar._detail-item', ['label' => 'No. WhatsApp', 'value' => $siswa->SISWA_WA])
                </div>
            </div>
        </div>

        {{-- ═══ 2. ALAMAT ═══ --}}
        <div class="detail-card">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-emerald-100">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Alamat</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @include('pendaftar._detail-item', ['label' => 'Provinsi', 'value' => $siswa->provinsiAlamat->PROV_NAMA])
                    @include('pendaftar._detail-item', ['label' => 'Kota/Kabupaten', 'value' => $siswa->kotaAlamat->KOTA_JENIS . " " . $siswa->provinsiAlamat->KOTA_NAMA])
                    @include('pendaftar._detail-item', ['label' => 'Kecamatan', 'value' => $siswa->kecamatanAlamat->KEC_NAMA])
                    @include('pendaftar._detail-item', ['label' => 'Kelurahan', 'value' => $siswa->kelurahanAlamat->KEL_NAMA])
                </div>
                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alamat Lengkap</p>
                    <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">{{ $siswa->SISWA_ALAMAT }}</p>
                </div>
            </div>
        </div>

        {{-- ═══ 3. DATA SEKOLAH & NILAI ═══ --}}
        <div class="detail-card">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-amber-100">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Data Sekolah & Nilai</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">
                    @include('pendaftar._detail-item', ['label' => 'Asal Sekolah', 'value' => $siswa->SISWA_SEKOLAH])
                    @include('pendaftar._detail-item', ['label' => 'Tahun Lulus', 'value' => $siswa->SISWA_SEKOLAH_TAHUN_LULUS])
                    {{--
                    @include('pendaftar._detail-item', ['label' => 'Nilai Rata-rata', 'value' => number_format($siswa->SISWA_NILAI_RATA, 2)])
                    --}}
                </div>

                {{-- Tabel Nilai --}}
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm min-w-[340px]">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-left">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Sem. 5 (Kls V)</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Sem. 6 (Kls VI)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['label' => 'Matematika',     'k52' => $siswa->SISWA_NILAI_52_MTK,  'k61' => $siswa->SISWA_NILAI_61_MTK],
                                ['label' => 'IPA',            'k52' => $siswa->SISWA_NILAI_52_IPA,  'k61' => $siswa->SISWA_NILAI_61_IPA],
                                ['label' => 'Bhs. Indonesia', 'k52' => $siswa->SISWA_NILAI_52_BIND, 'k61' => $siswa->SISWA_NILAI_61_BIND],
                                ['label' => 'PAI',            'k52' => $siswa->SISWA_NILAI_52_PAI,  'k61' => $siswa->SISWA_NILAI_61_PAI],
                            ] as $i => $mapel)
                            <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50/50' }} border-t border-gray-50">
                                <td class="px-4 py-3 font-semibold text-gray-700 text-sm">{{ $mapel['label'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-0.5 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold">
                                        {{ number_format($mapel['k52'], 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-0.5 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold">
                                        {{ number_format($mapel['k61'], 2) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-100 border-t border-gray-50">
                                <td class="px-4 py-3 font-semibold text-gray-700 text-lg">Rata-rata</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-0.5 bg-indigo-50 text-indigo-700 rounded-lg text-lg font-bold">
                                        {{ number_format((
                                            $siswa->SISWA_NILAI_52_MTK
                                            + $siswa->SISWA_NILAI_52_IPA
                                            + $siswa->SISWA_NILAI_52_BIND
                                            + $siswa->SISWA_NILAI_52_PAI
                                        ) / 4, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-0.5 bg-indigo-50 text-indigo-700 rounded-lg text-lg font-bold">
                                        {{ number_format((
                                            $siswa->SISWA_NILAI_61_MTK
                                            + $siswa->SISWA_NILAI_61_IPA
                                            + $siswa->SISWA_NILAI_61_BIND
                                            + $siswa->SISWA_NILAI_61_PAI
                                        ) / 4, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══ 4. NILAI TES ═══ --}}
        <div class="detail-card">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-violet-100">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Nilai Tes</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- CBT Akademik -->
                    <div class="relative overflow-hidden rounded-2xl p-5 text-center" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe);">
                        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full opacity-20" style="background: #7c3aed;"></div>
                        <p class="text-xs font-bold text-violet-500 mb-1 uppercase tracking-wider">CBT Akademik</p>
                        <p class="text-4xl font-black text-violet-700 leading-none">{{ number_format($siswa->SISWA_TES_CBT_AKADEMIK, 1) }}</p>
                    </div>

                    <!-- CBT Psikotest -->
                    <div class="relative overflow-hidden rounded-2xl p-5 text-center" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full opacity-20" style="background: #2563eb;"></div>
                        <p class="text-xs font-bold text-blue-600 mb-1 uppercase tracking-wider">CBT Psikotest</p>
                        <p class="text-4xl font-black text-blue-700 leading-none">{{ number_format($siswa->SISWA_TES_CBT_PSIKO, 1) }}</p>
                    </div>

                    <!-- Tes Quran -->
                    <div class="relative overflow-hidden rounded-2xl p-5 text-center" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full opacity-20" style="background: #059669;"></div>
                        <p class="text-xs font-bold text-emerald-600 mb-1 uppercase tracking-wider">Tes baca Al-Qur'an</p>
                        <p class="text-4xl font-black text-emerald-700 leading-none">{{ number_format($siswa->SISWA_TES_QURAN, 1) }}</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- ═══ 5. AFIRMASI & PRESTASI ═══ --}}
        @if(in_array($siswa->SISWA_JALUR, ["JALUR_PRESTASI", "JALUR_AFIRMASI"]))
        <div class="rounded-2xl border border-sky-100 bg-white shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-sky-100">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Jalur Khusus</h2>
            </div>

            <div class="divide-y divide-gray-50">

                {{-- Afirmasi --}}
                @if($siswa->SISWA_JALUR == "JALUR_AFIRMASI")
                <div class="px-8 py-4 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <!-- <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div> -->
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 mb-1">Afirmasi</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $siswa->refAfirmasi->R_INFO }}</p>
                            @if($siswa->SISWA_AFIRMASI_FILE)
                            <a href="{{ asset('storage/' . $siswa->SISWA_AFIRMASI_FILE) }}" target="_blank"
                                class="inline-flex items-center gap-1 mt-1.5 text-[11px] font-semibold text-indigo-500 hover:text-indigo-700 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Lihat Berkas
                            </a>
                            @endif
                        </div>
                    </div>
                    {{-- POIN AFIRMASI --}}
                    <div class="shrink-0 text-right">
                        <div class="inline-flex flex-col items-center bg-amber-50 border border-amber-200 rounded-xl px-4 py-4">
                            <span class="text-lg font-black text-amber-600 leading-none">{{ skorKhusus($siswa->SISWA_AFIRMASI) }}</span>
                            <span class="text-[9px] font-bold text-amber-400 mt-0.5">skor</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Prestasi Kejuaraan --}}
                @if($siswa->SISWA_JALUR == "JALUR_PRESTASI")
                <div class="px-8 py-4 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <!-- <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div> -->
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 mb-1">Prestasi Kejuaraan</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $siswa->refPrestasiKejuaraan->R_INFO }}</p>
                            @if($siswa->SISWA_PRESTASI_KEJUARAAN_JUDUL)
                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $siswa->SISWA_PRESTASI_KEJUARAAN_JUDUL }}</p>
                            @endif
                            @if($siswa->SISWA_PRESTASI_KEJUARAAN_FILE)
                            <a href="{{ asset('storage/' . $siswa->SISWA_PRESTASI_KEJUARAAN_FILE) }}" target="_blank"
                                class="inline-flex items-center gap-1 mt-1.5 text-[11px] font-semibold text-indigo-500 hover:text-indigo-700 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Lihat Berkas
                            </a>
                            @endif
                        </div>
                    </div>
                    {{-- POIN KEJUARAAN --}}
                    <div class="shrink-0 text-right">
                        <div class="inline-flex flex-col items-center bg-amber-50 border border-amber-200 rounded-xl px-4 py-4">
                            <span class="text-lg font-black text-amber-600 leading-none">{{ skorKhusus($siswa->SISWA_PRESTASI_KEJUARAAN)  }}</span>
                            <span class="text-[9px] font-bold text-amber-400 mt-0.5">skor</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Prestasi Keagamaan --}}
                @if($siswa->SISWA_PRESTASI_KEAGAMAAN)
                <div class="px-8 py-4 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <!-- <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div> -->
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 mb-1">Hafalan Al-Qur'an</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $siswa->refPrestasiKeagamaan->R_INFO }}</p>
                            @if($siswa->SISWA_PRESTASI_KEAGAMAAN_FIILE)
                            <a href="{{ asset('storage/' . $siswa->SISWA_PRESTASI_KEAGAMAAN_FIILE) }}" target="_blank"
                                class="inline-flex items-center gap-1 mt-1.5 text-[11px] font-semibold text-indigo-500 hover:text-indigo-700 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Lihat Berkas
                            </a>
                            @endif
                        </div>
                    </div>
                    {{-- POIN KEAGAMAAN --}}
                    <div class="shrink-0 text-right">
                        <div class="inline-flex flex-col items-center bg-amber-50 border border-amber-200 rounded-xl px-4 py-4">
                            <span class="text-lg font-black text-amber-600 leading-none">{{ skorKhusus($siswa->SISWA_PRESTASI_KEAGAMAAN) }}</span>
                            <span class="text-[9px] font-bold text-amber-400 mt-0.5">skor</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif

        {{-- ═══ 6. BERKAS DOKUMEN ═══ --}}
        <div class="detail-card">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-3">
                <div class="section-icon bg-green-100">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-700 text-sm tracking-wide">Berkas Dokumen</h2>
            </div>
            <div class="p-5">
                @php
                $files = [
                    ['label' => 'SKL / Surat Keterangan Lulus', 'file' => $siswa->SISWA_FILE_SKL],
                    ['label' => 'FC Rapor Semester 5',           'file' => $siswa->SISWA_FILE_RAPOR_52],
                    ['label' => 'FC Rapor Semester 6',           'file' => $siswa->SISWA_FILE_RAPOR_61],
                    ['label' => 'FC Kartu NISN',                 'file' => $siswa->SISWA_FILE_NISN],
                    ['label' => 'FC Kartu Keluarga',             'file' => $siswa->SISWA_FILE_KK],
                    ['label' => 'FC Akta Kelahiran',             'file' => $siswa->SISWA_FILE_AKTA],
                    ['label' => 'Pas Foto',                      'file' => $siswa->SISWA_FILE_FOTO],
                ];
                $uploaded = collect($files)->where('file', '!=', null)->count();
                $total    = count($files);
                @endphp

                {{-- Progress bar --}}
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all"
                            style="width: {{ ($uploaded / $total) * 100 }}%; background: linear-gradient(90deg, #6366f1, #8b5cf6);"></div>
                    </div>
                    <span class="text-xs font-bold text-gray-500 whitespace-nowrap">{{ $uploaded }}/{{ $total }} berkas</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($files as $doc)
                    <div class="file-item flex items-center justify-between p-3.5 rounded-xl border
                        {{ $doc['file'] ? 'border-green-100 bg-green-50/60' : 'border-dashed border-gray-200 bg-gray-50/40' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($doc['file'])
                                <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-green-500 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @else
                                <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="text-xs font-semibold truncate {{ $doc['file'] ? 'text-gray-700' : 'text-gray-400' }}">
                                {{ $doc['label'] }}
                            </span>
                        </div>
                        @if($doc['file'])
                        <a href="{{ asset('storage/' . $doc['file']) }}" target="_blank"
                            class="ml-2 flex-shrink-0 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors px-2 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                            Lihat
                        </a>
                        @else
                        <span class="ml-2 text-xs text-gray-300 font-medium">—</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Footer timestamp --}}
        <p class="text-center text-xs text-gray-400 pb-6 pt-1">
            Mendaftar pada {{ $formatTglDaftar }}
        </p>

    </div>
</div>

@push('scripts')
<script></script>
@endpush
@endsection