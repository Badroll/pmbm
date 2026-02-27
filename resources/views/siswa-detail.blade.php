@extends('layouts.app')
@section('title', 'Detail Pendaftar - ' . $siswa->SISWA_NAMA)
@section('content')

@php
    $jalurMap = [
        'JALUR_REGULER'  => ['label' => 'Reguler',  'class' => 'bg-blue-100 text-blue-700'],
        'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'class' => 'bg-orange-100 text-orange-700'],
        'JALUR_PRESTASI' => ['label' => 'Prestasi', 'class' => 'bg-purple-100 text-purple-700'],
    ];
    $statusMap = [
        'STATUS_PENDING'    => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700', 'dot' => 'bg-yellow-400', 'border' => 'border-yellow-200'],
        'STATUS_VERIFIKASI' => ['label' => 'Verifikasi', 'class' => 'bg-blue-100 text-blue-700',    'dot' => 'bg-blue-400',   'border' => 'border-blue-200'],
        'STATUS_DITERIMA'   => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-700',  'dot' => 'bg-green-400',  'border' => 'border-green-200'],
        'STATUS_DITOLAK'    => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-700',      'dot' => 'bg-red-400',    'border' => 'border-red-200'],
    ];
    $jalur  = $jalurMap[$siswa->SISWA_JALUR]   ?? ['label' => $siswa->SISWA_JALUR,  'class' => 'bg-gray-100 text-gray-600'];
    $status = $statusMap[$siswa->SISWA_STATUS]  ?? ['label' => $siswa->SISWA_STATUS, 'class' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400', 'border' => 'border-gray-200'];
@endphp

<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">

    {{-- Back + Header --}}
    <div class="mb-6">
        <a href="#"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Foto / Avatar --}}
                <div class="flex-shrink-0">
                    @if($siswa->SISWA_FILE_FOTO)
                        <img src="{{ asset('storage/' . $siswa->SISWA_FILE_FOTO) }}"
                            alt="Foto {{ $siswa->SISWA_NAMA }}"
                            class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-100">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-indigo-100 flex items-center justify-center">
                            <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($siswa->SISWA_NAMA, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Info Utama --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-start gap-2 mb-1">
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $siswa->SISWA_NAMA }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                            {{ $status['label'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 font-mono mb-3">{{ str_pad($siswa->SISWA_ID, 4, '0', STR_PAD_LEFT) }}</p>

                    <div class="flex flex-wrap gap-x-5 gap-y-1.5 text-sm text-gray-600">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $siswa->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR)->translatedFormat('d F Y') }}
                            ({{ \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR)->age }} tahun)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $siswa->SISWA_WA }}
                        </span>
                    </div>
                </div>

                {{-- Skor & Jalur --}}
                <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2">
                    <div class="text-center sm:text-right">
                        <p class="text-xs text-gray-400 mb-0.5">Total Skor</p>
                        <p class="text-3xl font-black text-indigo-600">{{ number_format($siswa->SISWA_SKOR, 1) }}</p>
                    </div>
                    <span class="inline-flex px-3 py-1 rounded-xl text-xs font-semibold {{ $jalur['class'] }}">
                        {{ $jalur['label'] }}
                    </span>
                </div>
            </div>

            {{-- Update Status Form --}}
            <div class="mt-4 pt-4 border-t border-gray-50">
                <form method="POST" action="#"
                    class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    @csrf
                    @method('PATCH')
                    <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Ubah Status:</label>
                    <select name="status"
                        class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition bg-white text-gray-700 flex-1 sm:flex-none sm:w-48">
                        <option value="STATUS_PENDING"    {{ $siswa->SISWA_STATUS == 'STATUS_PENDING'    ? 'selected' : '' }}>Pending</option>
                        <option value="STATUS_VERIFIKASI" {{ $siswa->SISWA_STATUS == 'STATUS_VERIFIKASI' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="STATUS_DITERIMA"   {{ $siswa->SISWA_STATUS == 'STATUS_DITERIMA'   ? 'selected' : '' }}>Diterima</option>
                        <option value="STATUS_DITOLAK"    {{ $siswa->SISWA_STATUS == 'STATUS_DITOLAK'    ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                        Simpan Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sections --}}
    <div class="space-y-4">

        {{-- 1. Data Pribadi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Data Pribadi</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @include('pendaftar._detail-item', ['label' => 'NISN', 'value' => $siswa->SISWA_NISN, 'mono' => true])
                    @include('pendaftar._detail-item', ['label' => 'Tempat Lahir', 'value' => $siswa->SISWA_TEMPAT_LAHIR])
                    @include('pendaftar._detail-item', ['label' => 'Tanggal Lahir', 'value' => \Carbon\Carbon::parse($siswa->SISWA_TGL_LAHIR)->translatedFormat('d F Y')])
                    @include('pendaftar._detail-item', ['label' => 'Jenis Kelamin', 'value' => $siswa->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'Laki-laki' : 'Perempuan'])
                    @include('pendaftar._detail-item', ['label' => 'Tinggi Badan', 'value' => $siswa->SISWA_TB . ' cm'])
                    @include('pendaftar._detail-item', ['label' => 'Berat Badan', 'value' => $siswa->SISWA_BB . ' kg'])
                    @include('pendaftar._detail-item', ['label' => 'No. WhatsApp', 'value' => $siswa->SISWA_WA])
                    @include('pendaftar._detail-item', ['label' => 'Nama Ayah', 'value' => $siswa->SISWA_AYAH])
                    @include('pendaftar._detail-item', ['label' => 'Nama Ibu', 'value' => $siswa->SISWA_IBU])
                </div>
            </div>
        </div>

        {{-- 2. Alamat --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Alamat</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @include('pendaftar._detail-item', ['label' => 'Provinsi', 'value' => $siswa->SISWA_ALAMAT_PROVINSI])
                    @include('pendaftar._detail-item', ['label' => 'Kota/Kabupaten', 'value' => $siswa->SISWA_ALAMAT_KOTA])
                    @include('pendaftar._detail-item', ['label' => 'Kecamatan', 'value' => $siswa->SISWA_ALAMAT_KECAMATAN])
                    @include('pendaftar._detail-item', ['label' => 'Kelurahan', 'value' => $siswa->SISWA_ALAMAT_KELURAHAN])
                </div>
                <div class="mt-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-xl px-4 py-3">{{ $siswa->SISWA_ALAMAT }}</p>
                </div>
            </div>
        </div>

        {{-- 3. Data Sekolah & Nilai --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Data Sekolah & Nilai</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    @include('pendaftar._detail-item', ['label' => 'Asal Sekolah', 'value' => $siswa->SISWA_SEKOLAH])
                    @include('pendaftar._detail-item', ['label' => 'Tahun Lulus', 'value' => $siswa->SISWA_SEKOLAH_TAHUN_LULUS])
                    @include('pendaftar._detail-item', ['label' => 'Nilai Rata-rata', 'value' => number_format($siswa->SISWA_NILAI_RATA, 2)])
                </div>

                {{-- Tabel Nilai --}}
                <div class="overflow-x-auto -mx-5 px-5">
                    <table class="w-full text-sm text-center border-collapse min-w-[360px]">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase text-left rounded-tl-xl">Mata Pelajaran</th>
                                <th class="px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase">Sem. 5 (Kls V)</th>
                                <th class="px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase rounded-tr-xl">Sem. 6 (Kls VI)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach([
                                ['label' => 'Matematika',       'k52' => $siswa->SISWA_NILAI_52_MTK,  'k61' => $siswa->SISWA_NILAI_61_MTK],
                                ['label' => 'IPA',              'k52' => $siswa->SISWA_NILAI_52_IPA,  'k61' => $siswa->SISWA_NILAI_61_IPA],
                                ['label' => 'Bhs. Indonesia',   'k52' => $siswa->SISWA_NILAI_52_BIND, 'k61' => $siswa->SISWA_NILAI_61_BIND],
                                ['label' => 'PAI',              'k52' => $siswa->SISWA_NILAI_52_PAI,  'k61' => $siswa->SISWA_NILAI_61_PAI],
                            ] as $mapel)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-3 py-2.5 text-left font-medium text-gray-700">{{ $mapel['label'] }}</td>
                                <td class="px-3 py-2.5 text-gray-600">{{ number_format($mapel['k52'], 2) }}</td>
                                <td class="px-3 py-2.5 text-gray-600">{{ number_format($mapel['k61'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 4. Nilai Tes --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Nilai Tes</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-violet-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-violet-500 font-medium mb-1">Tes CBT</p>
                        <p class="text-3xl font-black text-violet-700">{{ number_format($siswa->SISWA_TES_CBT_NILAI, 1) }}</p>
                    </div>
                    <div class="bg-emerald-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-emerald-500 font-medium mb-1">Tes Qur'an</p>
                        <p class="text-3xl font-black text-emerald-700">{{ number_format($siswa->SISWA_TES_QURAN_NILAI, 1) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Afirmasi & Prestasi --}}
        @if($siswa->SISWA_AFIRMASI || $siswa->SISWA_PRESTASI_KEJUARAAN || $siswa->SISWA_PRESTASI_KEAGAMAAN)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Afirmasi & Prestasi</h2>
            </div>
            <div class="p-5 space-y-4">
                @if($siswa->SISWA_AFIRMASI)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Afirmasi</p>
                    <div class="flex flex-wrap gap-3 items-center">
                        <span class="inline-flex px-3 py-1.5 bg-orange-100 text-orange-700 rounded-xl text-sm font-semibold">
                            {{ $siswa->SISWA_AFIRMASI }}
                        </span>
                        @if($siswa->SISWA_AFIRMASI_FILE)
                        <a href="{{ asset('storage/' . $siswa->SISWA_AFIRMASI_FILE) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Lihat Berkas
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($siswa->SISWA_PRESTASI_KEJUARAAN)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Prestasi Kejuaraan</p>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-sm font-semibold text-gray-700">{{ $siswa->SISWA_PRESTASI_KEJUARAAN }}</p>
                        @if($siswa->SISWA_PRESTASI_KEJUARAAN_JUDUL)
                            <p class="text-xs text-gray-500 mt-1">{{ $siswa->SISWA_PRESTASI_KEJUARAAN_JUDUL }}</p>
                        @endif
                        @if($siswa->SISWA_PRESTASI_KEJUARAAN_FILE)
                        <a href="{{ asset('storage/' . $siswa->SISWA_PRESTASI_KEJUARAAN_FILE) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:underline mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Lihat Berkas
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($siswa->SISWA_PRESTASI_KEAGAMAAN)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Prestasi Keagamaan</p>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-sm font-semibold text-gray-700">{{ $siswa->SISWA_PRESTASI_KEAGAMAAN }}</p>
                        @if($siswa->SISWA_PRESTASI_KEAGAMAAN_FIILE)
                        <a href="{{ asset('storage/' . $siswa->SISWA_PRESTASI_KEAGAMAAN_FIILE) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:underline mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Lihat Berkas
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- 6. Berkas/Dokumen --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-sky-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm">Berkas Dokumen</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                    $files = [
                        ['label' => 'SKL / Surat Keterangan Lulus', 'file' => $siswa->SISWA_FILE_SKL],
                        ['label' => 'FC Rapor Semester 5',           'file' => $siswa->SISWA_FILE_FC_RAPORT_52],
                        ['label' => 'FC Rapor Semester 6',           'file' => $siswa->SISWA_FILE_FC_RAPORT_61],
                        ['label' => 'FC Kartu NISN',                 'file' => $siswa->SISWA_FILE_FC_NISN],
                        ['label' => 'FC Kartu Keluarga',             'file' => $siswa->SISWA_FILE_FC_KK],
                        ['label' => 'FC Akta Kelahiran',             'file' => $siswa->SISWA_FILE_FC_AKTA],
                        ['label' => 'Pas Foto',                      'file' => $siswa->SISWA_FILE_FOTO],
                    ];
                    @endphp
                    @foreach($files as $doc)
                    <div class="flex items-center justify-between p-3 rounded-xl border {{ $doc['file'] ? 'border-green-100 bg-green-50' : 'border-gray-100 bg-gray-50' }}">
                        <div class="flex items-center gap-2.5">
                            @if($doc['file'])
                            <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-7 h-7 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @endif
                            <span class="text-xs font-medium {{ $doc['file'] ? 'text-green-700' : 'text-gray-400' }}">
                                {{ $doc['label'] }}
                            </span>
                        </div>
                        @if($doc['file'])
                        <a href="{{ asset('storage/' . $doc['file']) }}" target="_blank"
                            class="text-xs text-indigo-600 hover:underline whitespace-nowrap ml-2">Lihat</a>
                        @else
                        <span class="text-xs text-gray-400">Belum</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tanggal Daftar Info --}}
        <p class="text-center text-xs text-gray-400 pb-4">
            Mendaftar pada {{ \Carbon\Carbon::parse($siswa->SISWA_TGL_DAFTAR)->translatedFormat('l, d F Y \p\u\k\u\l H:i') }}
        </p>

    </div>
</div>
@push('scripts')
<script>
</script>
@endpush
@endsection