@extends('layouts.app')
@section('title', 'Form Data Pendaftaran - PPDB')
@section('content')

@php

    $dis = function(string $section) : string {
        return '';
    };

@endphp
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="bg-gradient-to-r bg-indigo-600 rounded-t-lg p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Formulir Daftar Ulang</h1>
            <p class="text-indigo-100">Tahun Ajaran 2026/2027</p>
        </div>

        <form id="registration-form"
            action="{{ $formAction }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white shadow-lg rounded-b-lg">
            @csrf
            @if($isEdit)
                @method('POST')
                <input type="hidden" name="sdId" value="{{ $sd->SD_ID }}">
            @endif

            {{-- Overlay locked --}}
            @if($isLocked)
            <div class="relative">
                <div class="absolute inset-0 z-10 cursor-not-allowed" title="Form sudah terkunci"></div>
                <div class="opacity-60 pointer-events-none select-none">
            @endif

            {{-- ================================================================ --}}
            {{-- SECTION 1: DATA SISWA                                            --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">1</div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Siswa</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nomor Pendaftaran -->
                    <input type="hidden"
                            id="sd_nomor_pendaftaran"
                            name="sd_nomor_pendaftaran"
                            value="{{ $siswa->SISWA_NO }}"
                            >

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="sd_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_nama_lengkap"
                            name="sd_nama_lengkap"
                            value="{{ old('sd_nama_lengkap', $isEdit ? $sd->SD_NAMA_LENGKAP : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama lengkap sesuai ijazah"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas Yang Diinginkan -->
                    <div>
    <label for="sd_kelas_diinginkan" class="block text-sm font-semibold text-gray-700 mb-2">
        Kelas Yang Diinginkan <span class="text-red-500"></span>
    </label>
    <select id="sd_kelas_diinginkan"
        name="sd_kelas_diinginkan"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
        {{ $dis('section_siswa') }}>
        <option value="">-- Pilih Kelas --</option>
        @php
            $kelasOptions = [
                'Kelas Digital Tahfidz',
                'Kelas Digital Sains',
                'Kelas Digital ICT',
                'Kelas Digital Bilingual (Bahasa Arab-Bahasa Inggris)',
                'Kelas Digital Kewirausahaan',
            ];
            $selectedKelas = old('sd_kelas_diinginkan', $isEdit ? $sd->SD_KELAS_DIINGINKAN : '');
        @endphp
        @foreach ($kelasOptions as $kelas)
            <option value="{{ $kelas }}" {{ $selectedKelas == $kelas ? 'selected' : '' }}>
                {{ $kelas }}
            </option>
        @endforeach
    </select>
    @error('sd_kelas_diinginkan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                    <!-- NISN -->
                    <div>
                        <label for="sd_nisn" class="block text-sm font-semibold text-gray-700 mb-2">
                            NISN <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_nisn"
                            name="sd_nisn"
                            value="{{ old('sd_nisn', $isEdit ? $sd->SD_NISN : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="10 digit NISN"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="sd_nik" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIK <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_nik"
                            name="sd_nik"
                            value="{{ old('sd_nik', $isEdit ? $sd->SD_NIK : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="16 digit NIK"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Sekolah -->
                    <div>
                        <label for="sd_asal_sekolah" class="block text-sm font-semibold text-gray-700 mb-2">
                            Asal Sekolah/Madrasah <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_asal_sekolah"
                            name="sd_asal_sekolah"
                            value="{{ old('sd_asal_sekolah', $isEdit ? $sd->SD_ASAL_SEKOLAH : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama sekolah asal"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_asal_sekolah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NPSN Sekolah Asal -->
                    <div>
                        <label for="sd_npsn_asal" class="block text-sm font-semibold text-gray-700 mb-2">
                            NPSN Sekolah/Madrasah Asal
                        </label>
                        <input type="text"
                            id="sd_npsn_asal"
                            name="sd_npsn_asal"
                            value="{{ old('sd_npsn_asal', $isEdit ? $sd->SD_NPSN_ASAL : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="8 digit NPSN"
                            maxlength="8"
                            {{ $dis('section_siswa') }}>
                        @error('sd_npsn_asal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="sd_tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_tempat_lahir"
                            name="sd_tempat_lahir"
                            value="{{ old('sd_tempat_lahir', $isEdit ? $sd->SD_TEMPAT_LAHIR : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Kota tempat lahir"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="sd_tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500"></span>
                        </label>
                        <input type="date"
                            id="sd_tanggal_lahir"
                            name="sd_tanggal_lahir"
                            value="{{ old('sd_tanggal_lahir', $isEdit ? optional($sd->SD_TANGGAL_LAHIR)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="sd_jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500"></span>
                        </label>
                        <select id="sd_jenis_kelamin"
                                name="sd_jenis_kelamin"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_siswa') }}
                                >
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('sd_jenis_kelamin', $isEdit ? $sd->SD_JENIS_KELAMIN : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('sd_jenis_kelamin', $isEdit ? $sd->SD_JENIS_KELAMIN : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('sd_jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Saudara -->
                    <div>
                        <label for="sd_jumlah_saudara" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Saudara
                        </label>
                        <input type="number"
                            id="sd_jumlah_saudara"
                            name="sd_jumlah_saudara"
                            value="{{ old('sd_jumlah_saudara', $isEdit ? $sd->SD_JUMLAH_SAUDARA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="0"
                            min="0"
                            {{ $dis('section_siswa') }}>
                        @error('sd_jumlah_saudara')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Anak Ke -->
                    <div>
                        <label for="sd_anak_ke" class="block text-sm font-semibold text-gray-700 mb-2">
                            Anak Ke
                        </label>
                        <input type="number"
                            id="sd_anak_ke"
                            name="sd_anak_ke"
                            value="{{ old('sd_anak_ke', $isEdit ? $sd->SD_ANAK_KE : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="1"
                            min="1"
                            {{ $dis('section_siswa') }}>
                        @error('sd_anak_ke')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="sd_agama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Agama <span class="text-red-500"></span>
                        </label>
                        <select id="sd_agama"
                                name="sd_agama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_siswa') }}
                                >
                            <option value="">-- Pilih Agama --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('sd_agama', $isEdit ? $sd->SD_AGAMA : '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                        @error('sd_agama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cita-Cita -->
                    <div>
                        <label for="sd_cita_cita" class="block text-sm font-semibold text-gray-700 mb-2">
                            Cita-Cita
                        </label>
                        <input type="text"
                            id="sd_cita_cita"
                            name="sd_cita_cita"
                            value="{{ old('sd_cita_cita', $isEdit ? $sd->SD_CITA_CITA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Cita-cita siswa"
                            {{ $dis('section_siswa') }}>
                        @error('sd_cita_cita')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="sd_no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                            No. HP <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_no_hp"
                            name="sd_no_hp"
                            value="{{ old('sd_no_hp', $isEdit ? $sd->SD_NO_HP : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="08xxxxxxxxxx"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="sd_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Email Siswa
                        </label>
                        <input type="email"
                            id="sd_email"
                            name="sd_email"
                            value="{{ old('sd_email', $isEdit ? $sd->SD_EMAIL : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="email@contoh.com"
                            {{ $dis('section_siswa') }}>
                        @error('sd_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hobby -->
                    <div>
                        <label for="sd_hobby" class="block text-sm font-semibold text-gray-700 mb-2">
                            Hobby
                        </label>
                        <input type="text"
                            id="sd_hobby"
                            name="sd_hobby"
                            value="{{ old('sd_hobby', $isEdit ? $sd->SD_HOBBY : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Hobi siswa"
                            {{ $dis('section_siswa') }}>
                        @error('sd_hobby')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Yang Membiayai -->
                    <div>
                        <label for="sd_pembiaya" class="block text-sm font-semibold text-gray-700 mb-2">
                            Yang Membiayai <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_pembiaya"
                            name="sd_pembiaya"
                            value="{{ old('sd_pembiaya', $isEdit ? $sd->SD_PEMBIAYA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder=""
                            {{ $dis('section_siswa') }}>
                        @error('sd_pembiaya')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('sd_pembiaya')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor KIP -->
                    <div>
                        <label for="sd_nomor_kip" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor KIP
                        </label>
                        <input type="text"
                            id="sd_nomor_kip"
                            name="sd_nomor_kip"
                            value="{{ old('sd_nomor_kip', $isEdit ? $sd->SD_NOMOR_KIP : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nomor KIP (jika ada)"
                            {{ $dis('section_siswa') }}>
                        @error('sd_nomor_kip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor KK -->
                    <div>
                        <label for="sd_nomor_kk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor KK <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_nomor_kk"
                            name="sd_nomor_kk"
                            value="{{ old('sd_nomor_kk', $isEdit ? $sd->SD_NOMOR_KK : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="16 digit nomor KK"
                            maxlength="16"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_nomor_kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Kepala Keluarga -->
                    <div>
                        <label for="sd_nama_kepala_keluarga" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Kepala Keluarga <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_nama_kepala_keluarga"
                            name="sd_nama_kepala_keluarga"
                            value="{{ old('sd_nama_kepala_keluarga', $isEdit ? $sd->SD_NAMA_KEPALA_KELUARGA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama kepala keluarga"
                            {{ $dis('section_siswa') }}
                            >
                        @error('sd_nama_kepala_keluarga')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 2: DATA AYAH KANDUNG                                    --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">2</div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Ayah Kandung</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama Ayah -->
                    <div class="md:col-span-2">
                        <label for="sd_ayah_nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap Ayah Kandung <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_ayah_nama"
                            name="sd_ayah_nama"
                            value="{{ old('sd_ayah_nama', $isEdit ? $sd->SD_AYAH_NAMA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama lengkap ayah"
                            {{ $dis('section_ayah') }}
                            >
                        @error('sd_ayah_nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Ayah -->
                    <div>
                        <label for="sd_ayah_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500"></span>
                        </label>
                        <select id="sd_ayah_status"
                                name="sd_ayah_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ayah') }}
                                >
                            <option value="">-- Pilih Status --</option>
                            <option value="Hidup" {{ old('sd_ayah_status', $isEdit ? $sd->SD_AYAH_STATUS : '') == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                            <option value="Meninggal" {{ old('sd_ayah_status', $isEdit ? $sd->SD_AYAH_STATUS : '') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                        @error('sd_ayah_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kewarganegaraan Ayah -->
                    <div>
                        <label for="sd_ayah_kewarganegaraan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kewarganegaraan
                        </label>
                        <select id="sd_ayah_kewarganegaraan"
                                name="sd_ayah_kewarganegaraan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ayah') }}>
                            <option value="">-- Pilih --</option>
                            <option value="WNI" {{ old('sd_ayah_kewarganegaraan', $isEdit ? $sd->SD_AYAH_KEWARGANEGARAAN : '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('sd_ayah_kewarganegaraan', $isEdit ? $sd->SD_AYAH_KEWARGANEGARAAN : '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                        @error('sd_ayah_kewarganegaraan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK Ayah -->
                    <div>
                        <label for="sd_ayah_nik" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIK Ayah
                        </label>
                        <input type="text"
                            id="sd_ayah_nik"
                            name="sd_ayah_nik"
                            value="{{ old('sd_ayah_nik', $isEdit ? $sd->SD_AYAH_NIK : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="16 digit NIK ayah"
                            maxlength="16"
                            {{ $dis('section_ayah') }}>
                        @error('sd_ayah_nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir Ayah -->
                    <div>
                        <label for="sd_ayah_tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir Ayah
                        </label>
                        <input type="text"
                            id="sd_ayah_tempat_lahir"
                            name="sd_ayah_tempat_lahir"
                            value="{{ old('sd_ayah_tempat_lahir', $isEdit ? $sd->SD_AYAH_TEMPAT_LAHIR : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Kota tempat lahir ayah"
                            {{ $dis('section_ayah') }}>
                        @error('sd_ayah_tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir Ayah -->
                    <div>
                        <label for="sd_ayah_tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir Ayah
                        </label>
                        <input type="date"
                            id="sd_ayah_tanggal_lahir"
                            name="sd_ayah_tanggal_lahir"
                            value="{{ old('sd_ayah_tanggal_lahir', $isEdit ? optional($sd->SD_AYAH_TANGGAL_LAHIR)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            {{ $dis('section_ayah') }}>
                        @error('sd_ayah_tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pendidikan Ayah -->
                    <div>
                        <label for="sd_ayah_pendidikan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pendidikan Terakhir Ayah
                        </label>
                        <select id="sd_ayah_pendidikan"
                                name="sd_ayah_pendidikan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ayah') }}>
                            <option value="">-- Pilih Pendidikan --</option>
                            @foreach(['Tidak Sekolah','SD/MI','SMP/MTs','SMA/MA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                                <option value="{{ $pend }}" {{ old('sd_ayah_pendidikan', $isEdit ? $sd->SD_AYAH_PENDIDIKAN : '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                        @error('sd_ayah_pendidikan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ayah -->
                    <div>
                        <label for="sd_ayah_pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pekerjaan Utama Ayah
                        </label>
                        <input type="text"
                            id="sd_ayah_pekerjaan"
                            name="sd_ayah_pekerjaan"
                            value="{{ old('sd_ayah_pekerjaan', $isEdit ? $sd->SD_AYAH_PEKERJAAN : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Pekerjaan utama ayah"
                            {{ $dis('section_ayah') }}>
                        @error('sd_ayah_pekerjaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 3: DATA IBU KANDUNG                                     --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">3</div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Ibu Kandung</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama Ibu -->
                    <div class="md:col-span-2">
                        <label for="sd_ibu_nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap Ibu Kandung <span class="text-red-500"></span>
                        </label>
                        <input type="text"
                            id="sd_ibu_nama"
                            name="sd_ibu_nama"
                            value="{{ old('sd_ibu_nama', $isEdit ? $sd->SD_IBU_NAMA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama lengkap ibu"
                            {{ $dis('section_ibu') }}
                            >
                        @error('sd_ibu_nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Ibu -->
                    <div>
                        <label for="sd_ibu_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500"></span>
                        </label>
                        <select id="sd_ibu_status"
                                name="sd_ibu_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ibu') }}
                                >
                            <option value="">-- Pilih Status --</option>
                            <option value="Hidup" {{ old('sd_ibu_status', $isEdit ? $sd->SD_IBU_STATUS : '') == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                            <option value="Meninggal" {{ old('sd_ibu_status', $isEdit ? $sd->SD_IBU_STATUS : '') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                        @error('sd_ibu_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kewarganegaraan Ibu -->
                    <div>
                        <label for="sd_ibu_kewarganegaraan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kewarganegaraan
                        </label>
                        <select id="sd_ibu_kewarganegaraan"
                                name="sd_ibu_kewarganegaraan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ibu') }}>
                            <option value="">-- Pilih --</option>
                            <option value="WNI" {{ old('sd_ibu_kewarganegaraan', $isEdit ? $sd->SD_IBU_KEWARGANEGARAAN : '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('sd_ibu_kewarganegaraan', $isEdit ? $sd->SD_IBU_KEWARGANEGARAAN : '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                        @error('sd_ibu_kewarganegaraan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK Ibu -->
                    <div>
                        <label for="sd_ibu_nik" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIK Ibu
                        </label>
                        <input type="text"
                            id="sd_ibu_nik"
                            name="sd_ibu_nik"
                            value="{{ old('sd_ibu_nik', $isEdit ? $sd->SD_IBU_NIK : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="16 digit NIK ibu"
                            maxlength="16"
                            {{ $dis('section_ibu') }}>
                        @error('sd_ibu_nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir Ibu -->
                    <div>
                        <label for="sd_ibu_tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir Ibu
                        </label>
                        <input type="text"
                            id="sd_ibu_tempat_lahir"
                            name="sd_ibu_tempat_lahir"
                            value="{{ old('sd_ibu_tempat_lahir', $isEdit ? $sd->SD_IBU_TEMPAT_LAHIR : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Kota tempat lahir ibu"
                            {{ $dis('section_ibu') }}>
                        @error('sd_ibu_tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir Ibu -->
                    <div>
                        <label for="sd_ibu_tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir Ibu
                        </label>
                        <input type="date"
                            id="sd_ibu_tanggal_lahir"
                            name="sd_ibu_tanggal_lahir"
                            value="{{ old('sd_ibu_tanggal_lahir', $isEdit ? optional($sd->SD_IBU_TANGGAL_LAHIR)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            {{ $dis('section_ibu') }}>
                        @error('sd_ibu_tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pendidikan Ibu -->
                    <div>
                        <label for="sd_ibu_pendidikan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pendidikan Terakhir Ibu
                        </label>
                        <select id="sd_ibu_pendidikan"
                                name="sd_ibu_pendidikan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_ibu') }}>
                            <option value="">-- Pilih Pendidikan --</option>
                            @foreach(['Tidak Sekolah','SD/MI','SMP/MTs','SMA/MA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                                <option value="{{ $pend }}" {{ old('sd_ibu_pendidikan', $isEdit ? $sd->SD_IBU_PENDIDIKAN : '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                        @error('sd_ibu_pendidikan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ibu -->
                    <div>
                        <label for="sd_ibu_pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pekerjaan Utama Ibu
                        </label>
                        <input type="text"
                            id="sd_ibu_pekerjaan"
                            name="sd_ibu_pekerjaan"
                            value="{{ old('sd_ibu_pekerjaan', $isEdit ? $sd->SD_IBU_PEKERJAAN : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Pekerjaan utama ibu"
                            {{ $dis('section_ibu') }}>
                        @error('sd_ibu_pekerjaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 4: DATA WALI                                             --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">4</div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Wali</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <svg class="inline w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Isi bagian ini jika wali <strong>bukan</strong> ayah atau ibu kandung.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama Wali -->
                    <div class="md:col-span-2">
                        <label for="sd_wali_nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap Wali
                        </label>
                        <input type="text"
                            id="sd_wali_nama"
                            name="sd_wali_nama"
                            value="{{ old('sd_wali_nama', $isEdit ? $sd->SD_WALI_NAMA : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama lengkap wali"
                            {{ $dis('section_wali') }}>
                        @error('sd_wali_nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kewarganegaraan Wali -->
                    <div>
                        <label for="sd_wali_kewarganegaraan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kewarganegaraan
                        </label>
                        <select id="sd_wali_kewarganegaraan"
                                name="sd_wali_kewarganegaraan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_wali') }}>
                            <option value="">-- Pilih --</option>
                            <option value="WNI" {{ old('sd_wali_kewarganegaraan', $isEdit ? $sd->SD_WALI_KEWARGANEGARAAN : '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('sd_wali_kewarganegaraan', $isEdit ? $sd->SD_WALI_KEWARGANEGARAAN : '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                        @error('sd_wali_kewarganegaraan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK Wali -->
                    <div>
                        <label for="sd_wali_nik" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIK Wali
                        </label>
                        <input type="text"
                            id="sd_wali_nik"
                            name="sd_wali_nik"
                            value="{{ old('sd_wali_nik', $isEdit ? $sd->SD_WALI_NIK : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="16 digit NIK wali"
                            maxlength="16"
                            {{ $dis('section_wali') }}>
                        @error('sd_wali_nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir Wali -->
                    <div>
                        <label for="sd_wali_tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir Wali
                        </label>
                        <input type="text"
                            id="sd_wali_tempat_lahir"
                            name="sd_wali_tempat_lahir"
                            value="{{ old('sd_wali_tempat_lahir', $isEdit ? $sd->SD_WALI_TEMPAT_LAHIR : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Kota tempat lahir wali"
                            {{ $dis('section_wali') }}>
                        @error('sd_wali_tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir Wali -->
                    <div>
                        <label for="sd_wali_tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir Wali
                        </label>
                        <input type="date"
                            id="sd_wali_tanggal_lahir"
                            name="sd_wali_tanggal_lahir"
                            value="{{ old('sd_wali_tanggal_lahir', $isEdit ? optional($sd->SD_WALI_TANGGAL_LAHIR)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            {{ $dis('section_wali') }}>
                        @error('sd_wali_tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pendidikan Wali -->
                    <div>
                        <label for="sd_wali_pendidikan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pendidikan Terakhir Wali
                        </label>
                        <select id="sd_wali_pendidikan"
                                name="sd_wali_pendidikan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_wali') }}>
                            <option value="">-- Pilih Pendidikan --</option>
                            @foreach(['Tidak Sekolah','SD/MI','SMP/MTs','SMA/MA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                                <option value="{{ $pend }}" {{ old('sd_wali_pendidikan', $isEdit ? $sd->SD_WALI_PENDIDIKAN : '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                        @error('sd_wali_pendidikan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Wali -->
                    <div>
                        <label for="sd_wali_pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pekerjaan Utama Wali
                        </label>
                        <input type="text"
                            id="sd_wali_pekerjaan"
                            name="sd_wali_pekerjaan"
                            value="{{ old('sd_wali_pekerjaan', $isEdit ? $sd->SD_WALI_PEKERJAAN : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Pekerjaan utama wali"
                            {{ $dis('section_wali') }}>
                        @error('sd_wali_pekerjaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penghasilan Ortu/Wali -->
                    <div class="md:col-span-2">
                        <label for="sd_penghasilan_ortu_wali" class="block text-sm font-semibold text-gray-700 mb-2">
                            Penghasilan Orang Tua/Wali
                        </label>
                        <input type="text"
                            id="sd_penghasilan_ortu_wali"
                            name="sd_penghasilan_ortu_wali"
                            value="{{ old('sd_penghasilan_ortu_wali', $isEdit ? $sd->SD_PENGHASILAN_ORTU_WALI : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Rp xxx"
                            {{ $dis('section_wali') }}>
                        @error('sd_penghasilan_ortu_wali')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 5: ALAMAT AYAH                                           --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">5</div>
                    <h2 class="text-2xl font-bold text-gray-800">Alamat Ayah</h2>
                </div>

                <!-- Jika Di Luar Negeri -->
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-600 mb-3 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Luar Negeri
                    </h3>
                    <div>
                        <label for="sd_ayah_ln_alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Luar Negeri
                        </label>
                        <textarea id="sd_ayah_ln_alamat"
                            name="sd_ayah_ln_alamat"
                            rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Alamat lengkap di luar negeri"
                            {{ $dis('section_alamat_ayah') }}>{{ old('sd_ayah_ln_alamat', $isEdit ? $sd->SD_AYAH_LN_ALAMAT : '') }}</textarea>
                        @error('sd_ayah_ln_alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jika Di Dalam Negeri -->
                <div>
                    <h3 class="text-base font-semibold text-gray-600 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Dalam Negeri
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Status Kepemilikan Rumah -->
                        <div class="md:col-span-2">
                            <label for="sd_ayah_status_rumah" class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Kepemilikan Rumah
                            </label>
                            <select id="sd_ayah_status_rumah"
                                    name="sd_ayah_status_rumah"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    {{ $dis('section_alamat_ayah') }}>
                                <option value="">-- Pilih Status --</option>
                                @foreach(['Milik Sendiri','Milik Ortu','Kontrak'] as $s)
                                    <option value="{{ $s }}" {{ old('sd_ayah_status_rumah', $isEdit ? $sd->SD_AYAH_STATUS_RUMAH : '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('sd_ayah_status_rumah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @include('partials.alamat-fields', [
                            'prefix'   => 'sd_ayah',
                            'section'  => 'section_alamat_ayah',
                            'data'     => $isEdit ? $sd : null,
                            'fields'   => ['provinsi','kabupaten','kecamatan','kelurahan','rt_rw','alamat','kode_pos'],
                        ])

                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 6: ALAMAT IBU                                            --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">6</div>
                    <h2 class="text-2xl font-bold text-gray-800">Alamat Ibu Kandung</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6 bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                    <svg class="inline w-4 h-4 text-indigo-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Jika alamat ibu sama dengan ayah, tidak perlu diisi.
                </p>

                <!-- Jika Di Luar Negeri -->
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-600 mb-3 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Luar Negeri
                    </h3>
                    <div>
                        <label for="sd_ibu_ln_alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Luar Negeri
                        </label>
                        <textarea id="sd_ibu_ln_alamat"
                            name="sd_ibu_ln_alamat"
                            rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Alamat lengkap di luar negeri"
                            {{ $dis('section_alamat_ibu') }}>{{ old('sd_ibu_ln_alamat', $isEdit ? $sd->SD_IBU_LN_ALAMAT : '') }}</textarea>
                        @error('sd_ibu_ln_alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jika Di Dalam Negeri -->
                <div>
                    <h3 class="text-base font-semibold text-gray-600 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Dalam Negeri
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2">
                            <label for="sd_ibu_status_rumah" class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Kepemilikan Rumah
                            </label>
                            <select id="sd_ibu_status_rumah"
                                    name="sd_ibu_status_rumah"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    {{ $dis('section_alamat_ibu') }}>
                                <option value="">-- Pilih Status --</option>
                                @foreach(['Milik Sendiri','Milik Ortu','Kontrak'] as $s)
                                    <option value="{{ $s }}" {{ old('sd_ibu_status_rumah', $isEdit ? $sd->SD_IBU_STATUS_RUMAH : '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('sd_ibu_status_rumah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @include('partials.alamat-fields', [
                            'prefix'  => 'sd_ibu',
                            'section' => 'section_alamat_ibu',
                            'data'    => $isEdit ? $sd : null,
                            'fields'  => ['provinsi','kabupaten','kecamatan','kelurahan','rt_rw','alamat','kode_pos'],
                        ])

                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 7: ALAMAT WALI                                           --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">7</div>
                    <h2 class="text-2xl font-bold text-gray-800">Alamat Wali</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <svg class="inline w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Isi bagian ini jika wali <strong>bukan</strong> ayah atau ibu kandung.
                </p>

                <!-- Jika Di Luar Negeri -->
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-600 mb-3 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Luar Negeri
                    </h3>
                    <div>
                        <label for="sd_wali_ln_alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Luar Negeri
                        </label>
                        <textarea id="sd_wali_ln_alamat"
                            name="sd_wali_ln_alamat"
                            rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Alamat lengkap di luar negeri"
                            {{ $dis('section_alamat_wali') }}>{{ old('sd_wali_ln_alamat', $isEdit ? $sd->SD_WALI_LN_ALAMAT : '') }}</textarea>
                        @error('sd_wali_ln_alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jika Di Dalam Negeri -->
                <div>
                    <h3 class="text-base font-semibold text-gray-600 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                        Jika Di Dalam Negeri
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2">
                            <label for="sd_wali_status_rumah" class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Kepemilikan Rumah
                            </label>
                            <select id="sd_wali_status_rumah"
                                    name="sd_wali_status_rumah"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    {{ $dis('section_alamat_wali') }}>
                                <option value="">-- Pilih Status --</option>
                                @foreach(['Milik Sendiri','Milik Ortu','Kontrak'] as $s)
                                    <option value="{{ $s }}" {{ old('sd_wali_status_rumah', $isEdit ? $sd->SD_WALI_STATUS_RUMAH : '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('sd_wali_status_rumah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @include('partials.alamat-fields', [
                            'prefix'  => 'sd_wali',
                            'section' => 'section_alamat_wali',
                            'data'    => $isEdit ? $sd : null,
                            'fields'  => ['provinsi','kabupaten','kecamatan','kelurahan','rt_rw','alamat','kode_pos'],
                        ])

                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 8: ALAMAT SISWA                                          --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">8</div>
                    <h2 class="text-2xl font-bold text-gray-800">Alamat Siswa</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- ============================================ --}}
    {{-- SECTION: Jika di Yayasan/Pondok/Panti --}}
    {{-- ============================================ --}}
    <div class="md:col-span-2 mt-4">
        <div class="flex items-center gap-3 pb-2 border-b-2 border-indigo-200">
            <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-800">Jika di Asrama/Pondok/Panti</h4>
                <p class="text-xs text-gray-500">Isi bagian ini jika siswa tinggal di asrama, pondok pesantren, atau panti asuhan</p>
            </div>
        </div>
    </div>

    <!-- Nama Yayasan -->
    <div class="md:col-span-2">
        <label for="sd_nama_yayasan" class="block text-sm font-semibold text-gray-700 mb-2">
            Nama Asrama/Pondok/Panti
        </label>
        <input type="text"
            id="sd_nama_yayasan"
            name="sd_nama_yayasan"
            value="{{ old('sd_nama_yayasan', $isEdit ? $sd->SD_NAMA_YAYASAN : '') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
            placeholder="Nama asrama/pondok/panti (jika ada)"
            {{ $dis('section_alamat_siswa') }}>
        @error('sd_nama_yayasan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- ============================================ --}}
    {{-- SECTION: Jika Lainnya (Alamat Detail) --}}
    {{-- ============================================ --}}
    <div class="md:col-span-2 mt-6">
        <div class="flex items-center gap-3 pb-2 border-b-2 border-amber-200">
            <div class="flex items-center justify-center w-8 h-8 bg-amber-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
</svg>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-800">Jika Lainnya</h4>
                <p class="text-xs text-gray-500">Isi data alamat lengkap siswa di bawah ini</p>
            </div>
        </div>
    </div>

    @include('partials.alamat-fields', [
        'prefix' => 'sd',
        'section' => 'section_alamat_siswa',
        'data' => $isEdit ? $sd : null,
        'fields' => ['provinsi','kabupaten','kecamatan','kelurahan','rt_rw','alamat','kode_pos'],
    ])

</div>

            </div>

            {{-- ================================================================ --}}
            {{-- SECTION 9: TRANSPORTASI                                          --}}
            {{-- ================================================================ --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">9</div>
                    <h2 class="text-2xl font-bold text-gray-800">Transportasi ke Sekolah</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Waktu Tempuh -->
                    <div>
                        <label for="sd_waktu_tempuh" class="block text-sm font-semibold text-gray-700 mb-2">
                            Waktu Tempuh ke Sekolah
                        </label>
                        <input type="text"
                            id="sd_waktu_tempuh"
                            name="sd_waktu_tempuh"
                            value="{{ old('sd_waktu_tempuh', $isEdit ? $sd->SD_WAKTU_TEMPUH : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Contoh: 15 menit"
                            {{ $dis('section_transportasi') }}>
                        @error('sd_waktu_tempuh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jarak (Km) -->
                    <div>
                        <label for="sd_jarak_km" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jarak ke Sekolah (Km)
                        </label>
                        <div class="relative">
                            <input type="number"
                                id="sd_jarak_km"
                                name="sd_jarak_km"
                                value="{{ old('sd_jarak_km', $isEdit ? $sd->SD_JARAK_KM : '') }}"
                                class="w-full px-4 py-3 pr-14 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                {{ $dis('section_transportasi') }}>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-sm">Km</span>
                        </div>
                        @error('sd_jarak_km')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transportasi -->
                    <div class="md:col-span-2">
                        <label for="sd_transportasi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Moda Transportasi <span class="text-red-500"></span>
                        </label>
                        <select id="sd_transportasi"
                                name="sd_transportasi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                {{ $dis('section_transportasi') }}
                                >
                            <option value="">-- Pilih Transportasi --</option>
                            @foreach(['Jalan Kaki','Sepeda','Motor','Mobil Pribadi','Ojek','Antar Jemput','Angkutan Umum'] as $tr)
                                <option value="{{ $tr }}" {{ old('sd_transportasi', $isEdit ? $sd->SD_TRANSPORTASI : '') == $tr ? 'selected' : '' }}>{{ $tr }}</option>
                            @endforeach
                        </select>
                        @error('sd_transportasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- CLOSE LOCKED OVERLAY                                             --}}
            {{-- ================================================================ --}}
            @if($isLocked)
                    </div>
                </div>
            @endif

            {{-- ================================================================ --}}
            {{-- SUBMIT BUTTON                                                    --}}
            {{-- ================================================================ --}}
            @if(!$isLocked)
            <div class="p-8 flex flex-col sm:flex-row gap-4 justify-end">
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md">
                    Simpan & Download
                </button>
            </div>
            @else
            <div class="p-8">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center text-gray-600 font-medium">
                    <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    Form sudah terkunci dan tidak dapat diubah.
                </div>
            </div>
            @endif

        </form>
    </div>
</div>
@endsection