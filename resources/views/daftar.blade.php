@extends('layouts.app')
@section('title', 'Form Pendaftaran - PMBM')
@section('content')

@php
    $isEdit = isset($siswa);
    $formAction = url('daftar');
    $siswaStatus = $isEdit ? $siswa->SISWA_STATUS : null;
    $isLocked = in_array($siswaStatus, ['STATUS_TERVERIFIKASI', 'STATUS_MENUNGGU', 'STATUS_LOLOS', 'STATUS_DITOLAK', 'STATUS_CADANGAN', 'STATUS_DITERIMA', 'STATUS_MENGUNDURKAN', 'STATUS_TERDAFTAR']);

    $statusConfig = [
        'STATUS_PENDING'        => ['label' => 'Pendaftaran Terkirim',  'color' => 'blue',      'icon' => 'fa-clock'],
        'STATUS_TERVERIFIKASI'  => ['label' => 'Terverifikasi',         'color' => 'green',     'icon' => 'fa-check-circle'],
        'STATUS_MENUNGGU'       => ['label' => 'Menunggu Hasil Tes',    'color' => 'yellow',    'icon' => 'fa-clock'],
    ];

    $currentStatus = $siswaStatus ? ($statusConfig[$siswaStatus] ?? ['label' => $siswaStatus, 'color' => 'gray', 'icon' => 'fa-info-circle']) : null;
@endphp

<!-- Progress Bar — sembunyikan jika locked -->
@if(!$isLocked)
<div class="bg-white shadow-sm sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Progress Pengisian</span>
            <span class="text-sm font-semibold text-blue-600"><span id="progress-percentage">0</span>%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>
</div>
@endif

<!-- Form Container -->
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-lg p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Formulir Pendaftaran Murid Baru</h1>
            <p class="text-blue-100">Tahun Ajaran 2026/2027</p>
        </div>

        {{-- ── STATUS BANNER ── --}}
        @if($isEdit && $currentStatus)
            @php
                $c = $currentStatus['color'];
                $bgClass    = "bg-{$c}-50";
                $borderClass= "border-{$c}-300";
                $iconClass  = "text-{$c}-500";
                $textClass  = "text-{$c}-800";
                $badgeClass = "bg-{$c}-100 text-{$c}-700";
            @endphp
            <div class="border {{ $borderClass }} {{ $bgClass }} px-6 py-4 flex items-start gap-4">
                <i class="fas {{ $currentStatus['icon'] }} text-2xl {{ $iconClass }} mt-0.5 flex-shrink-0"></i>
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-semibold {{ $textClass }} text-base">Status:</span>
                        <span class="text-sm font-bold px-3 py-1 rounded-full {{ $badgeClass }}">
                            {{ $currentStatus['label'] }}
                        </span>
                    </div>
                    @if($isLocked)
                        <p class="mt-1 text-sm {{ $textClass }} opacity-80">
                            Data pendaftaran Anda sudah <strong>{{ $currentStatus['label'] }}</strong> dan tidak dapat diubah lagi.
                            Hubungi panitia jika ada pertanyaan.
                        </p>
                    @else
                        <p class="mt-1 text-sm {{ $textClass }} opacity-80">
                            Anda masih dapat mengubah data pendaftaran selama status belum terverifikasi.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <form id="registration-form" 
            action="{{ $formAction }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="bg-white shadow-lg rounded-b-lg">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            {{-- Overlay locked — pointer-events & visual --}}
            @if($isLocked)
            <div class="relative">
                {{-- invisible overlay to block all inputs --}}
                <div class="absolute inset-0 z-10 cursor-not-allowed" title="Form sudah terkunci"></div>
                <div class="opacity-60 pointer-events-none select-none">
            @endif
            
            <!-- Section 1: Data Pribadi -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">1</div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Pribadi</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_lengkap" 
                               name="nama_lengkap" 
                               value="{{ old('nama_lengkap', $isEdit ? $siswa->SISWA_NAMA : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Nama lengkap sesuai ijazah"
                               required>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-sm font-semibold text-gray-700 mb-2">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nisn" 
                               name="nisn" 
                               value="{{ old('nisn', $isEdit ? $siswa->SISWA_NISN : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="NISN"
                               maxlength="10"
                               pattern="[0-9]{10}"
                               required>
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="JENIS_KELAMIN_L" {{ old('jenis_kelamin', $isEdit ? $siswa->SISWA_JENIS_KELAMIN : '') == 'JENIS_KELAMIN_L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="JENIS_KELAMIN_P" {{ old('jenis_kelamin', $isEdit ? $siswa->SISWA_JENIS_KELAMIN : '') == 'JENIS_KELAMIN_P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Nama Ayah -->
                    <div>
                        <label for="nama_ayah" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Ayah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_ayah" 
                               name="nama_ayah" 
                               value="{{ old('nama_ayah', $isEdit ? $siswa->SISWA_AYAH : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Nama Ayah"
                               required>
                        @error('nama_ayah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Nama Ibu -->
                    <div>
                        <label for="nama_ibu" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Ibu <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_ibu" 
                               name="nama_ibu" 
                               value="{{ old('nama_ibu', $isEdit ? $siswa->SISWA_IBU : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Nama Rumisih"
                               required>
                        @error('nama_ibu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-select-searchable
                        label="Tempat Kelahiran"
                        name="tempat_lahir"
                        id="tempat_lahir"
                        :options="$refKota->map(function ($o) {
                            return [
                                'value' => $o->KOTA_ID,
                                'label' => $o->KOTA_JENIS . ' ' . $o->KOTA_NAMA,
                            ];
                        })->values()"
                        placeholder="-- Pilih Kota --"
                    />

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_lahir" 
                               name="tanggal_lahir" 
                               value="{{ old('tanggal_lahir', $isEdit ? $siswa->SISWA_TGL_LAHIR : '') }}"
                               min="2011-07-10"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-2">
                            No. WhatsApp aktif <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="no_wa" 
                               name="no_wa" 
                               value="{{ old('no_wa', $isEdit ? $siswa->SISWA_WA : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]{10,13}"
                               required>
                        @error('no_wa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">Alamat</span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <x-select-searchable
                        label="Provinsi"
                        name="provinsi"
                        id="provinsi"
                        :options="$refProvinsi->map(function ($o) {
                            return [
                                'value' => $o->PROV_ID,
                                'label' => $o->PROV_NAMA,
                            ];
                        })->values()"
                        placeholder="-- Pilih Provinsi --"
                    />

                    <x-select-searchable
                        label="Kota/Kabupaten"
                        name="kota"
                        id="kota"
                        :options="collect()"
                        placeholder="-- Pilih Kota/Kabupaten --"
                    />

                    <x-select-searchable
                        label="Kecamatan"
                        name="kecamatan"
                        id="kecamatan"
                        :options="collect()"
                        placeholder="-- Pilih Kecamatan --"
                    />

                    <x-select-searchable
                        label="Kelurahan"
                        name="kelurahan"
                        id="kelurahan"
                        :options="collect()"
                        placeholder="-- Pilih Kelurahan/Desa --"
                    />

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" 
                                  name="alamat" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                  placeholder="Alamat lengkap"
                                  required>{{ old('alamat', $isEdit ? $siswa->SISWA_ALAMAT : '') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Sekolah Asal -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">2</div>
                    <h2 class="text-2xl font-bold text-gray-800">Asal Sekolah</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Sekolah -->
                    <div class="md:col-span-2">
                        <label for="asal_sekolah" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="asal_sekolah" 
                               name="asal_sekolah" 
                               value="{{ old('asal_sekolah', $isEdit ? $siswa->SISWA_SEKOLAH : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Nama Asal Sekolah"
                               required>
                        @error('asal_sekolah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Lulus -->
                    <div>
                        <label for="tahun_lulus" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Lulus <span class="text-red-500">*</span>
                        </label>
                        <select id="tahun_lulus" 
                                name="tahun_lulus" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih Tahun --</option>
                            @foreach(['2026','2025','2024','2023','2022','2021'] as $yr)
                                <option value="{{ $yr }}" 
                                    {{ old('tahun_lulus', $isEdit ? $siswa->SISWA_SEKOLAH_TAHUN_LULUS : '') == $yr ? 'selected' : '' }}>
                                    {{ $yr }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_lulus')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{--
                    <!-- Nilai Rata-rata -->
                    <div>
                        <label for="nilai_rata" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nilai Rata-rata Ijazah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_rata" 
                               name="nilai_rata" 
                               value="{{ old('nilai_rata', $isEdit ? $siswa->SISWA_NILAI_RATA : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01" min="0" max="100"
                               required>
                        @error('nilai_rata')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    --}}

                    <!-- Nilai Kelas 5 Sem 2 -->
                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">Nilai rapor kelas 5 semester 2</span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <div>
                        <label for="nilai_52_mtk" class="block text-sm font-semibold text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_52_mtk" name="nilai_52_mtk" value="{{ old('nilai_52_mtk', $isEdit ? $siswa->SISWA_NILAI_52_MTK : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_52_mtk')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_52_ipa" class="block text-sm font-semibold text-gray-700 mb-2">IPA <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_52_ipa" name="nilai_52_ipa" value="{{ old('nilai_52_ipa', $isEdit ? $siswa->SISWA_NILAI_52_IPA : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_52_ipa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_52_bind" class="block text-sm font-semibold text-gray-700 mb-2">Bahasa Indonesia <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_52_bind" name="nilai_52_bind" value="{{ old('nilai_52_bind', $isEdit ? $siswa->SISWA_NILAI_52_BIND : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_52_bind')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_52_pai" class="block text-sm font-semibold text-gray-700 mb-2">PAI / Aqidah Akhlak <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_52_pai" name="nilai_52_pai" value="{{ old('nilai_52_pai', $isEdit ? $siswa->SISWA_NILAI_52_PAI : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_52_pai')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nilai Kelas 6 Sem 1 -->
                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">Nilai rapor kelas 6 semester 1</span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <div>
                        <label for="nilai_61_mtk" class="block text-sm font-semibold text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_61_mtk" name="nilai_61_mtk" value="{{ old('nilai_61_mtk', $isEdit ? $siswa->SISWA_NILAI_61_MTK : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_61_mtk')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_61_ipa" class="block text-sm font-semibold text-gray-700 mb-2">IPA <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_61_ipa" name="nilai_61_ipa" value="{{ old('nilai_61_ipa', $isEdit ? $siswa->SISWA_NILAI_61_IPA : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_61_ipa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_61_bind" class="block text-sm font-semibold text-gray-700 mb-2">Bahasa Indonesia <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_61_bind" name="nilai_61_bind" value="{{ old('nilai_61_bind', $isEdit ? $siswa->SISWA_NILAI_61_BIND : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_61_bind')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_61_pai" class="block text-sm font-semibold text-gray-700 mb-2">PAI / Aqidah Akhlak <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_61_pai" name="nilai_61_pai" value="{{ old('nilai_61_pai', $isEdit ? $siswa->SISWA_NILAI_61_PAI : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 85.50" step="0.01" min="0" max="100" required>
                        @error('nilai_61_pai')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Pilihan Jalur Pendaftaran -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">3</div>
                    <h2 class="text-2xl font-bold text-gray-800">Jalur Pendaftaran</h2>
                </div>
                
                <div class="mb-4">
                    @php $jalurSelected = old('jalur_pendaftaran', $isEdit ? $siswa->SISWA_JALUR : ''); @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Jalur Reguler -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="JALUR_REGULER" {{ $jalurSelected === 'JALUR_REGULER' ? 'checked' : '' }} class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Reguler</h3>
                                </div>
                                <div class="checkmark-wrapper flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Jalur Prestasi -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="JALUR_PRESTASI" {{ $jalurSelected === 'JALUR_PRESTASI' ? 'checked' : '' }} class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-yellow-100 text-yellow-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Prestasi</h3>
                                </div>
                                <div class="checkmark-wrapper flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Jalur Afirmasi -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="JALUR_AFIRMASI" {{ $jalurSelected === 'JALUR_AFIRMASI' ? 'checked' : '' }} class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Afirmasi</h3>
                                </div>
                                <div class="checkmark-wrapper flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    @error('jalur_pendaftaran')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===================== SUB-FORM AFIRMASI ===================== -->
                <div id="sub-afirmasi" class="sub-jalur-form mt-6 hidden">
                    <div class="border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <!-- <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div> -->
                            <h3 class="text-base font-semibold text-blue-800">Detail Jalur Afirmasi</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilihan Afirmasi <span class="text-red-500">*</span></label> 
                            <select id="pilihan_afirmasi" 
                                name="pilihan_afirmasi" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                >
                                <option value="">-- Pilih --</option>
                                @foreach(getReferences("AFIRMASI") as $i => $v)
                                    <option value="{{ $v->R_ID }}" {{ old('pilihan_afirmasi', $isEdit ? ($siswa->SISWA_AFIRMASI ?? '') : '') === $v->R_ID ? 'selected' : '' }}>{{ $v->R_INFO }}</option>
                                @endforeach
                            </select>
                            @error('pilihan_afirmasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Afirmasi -->
                        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg px-4 py-3 space-y-1">
                            <p class="text-sm text-blue-800 flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                                Dokumen asli bukti Afirmasi dibawa saat verifikasi berkas
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ===================== SUB-FORM PRESTASI ===================== -->
                <div id="sub-prestasi" class="sub-jalur-form mt-6 hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 space-y-6">
                        <div class="flex items-center mb-2">
                            <!-- <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div> -->
                            <h3 class="text-base font-semibold text-blue-800">Detail Jalur Prestasi</h3>
                            <p class="text-sm text-blue-800 ml-2"> (pilih minimal 1 jenis prestasi/hafalan)<p>
                        </div>

                        <!-- Sub-form 1: Kejuaraan -->
                        <div class="bg-white border border-blue-200 rounded-xl p-5">
                            <h4 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <!-- <span class="bg-blue-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs">1</span> -->
                                Prestasi Kejuaraan
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tingkat Juara -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kejuaraan</label>
                                    <select id="tingkat_juara" 
                                            name="tingkat_juara" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                            >
                                        <option value="">-- Pilih --</option>
                                        @foreach(getReferences("PRESTASI_KEJUARAAN") as $i => $v)
                                            <option value="{{ $v->R_ID }}" {{ old('tingkat_juara', $isEdit ? ($siswa->SISWA_PRESTASI_KEJUARAAN ?? '') : '') === $v->R_ID ? 'selected' : '' }}>{{ $v->R_INFO }}</option>
                                        @endforeach
                                    </select>
                                    @error('tingkat_juara')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Penyelenggara -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Penyelenggara</label>
                                    <input type="text" name="penyelenggara_kejuaraan" id="penyelenggara_kejuaraan"
                                        value="{{ old('penyelenggara_kejuaraan', $isEdit ? ($siswa->SISWA_PRESTASI_KEJUARAAN_JUDUL ?? '') : '') }}"
                                        placeholder=""
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
                                        
                                        >
                                    @error('penyelenggara_kejuaraan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Catatan Kejuaraan -->
                            <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg px-4 py-3 space-y-1">
                                <p class="text-sm text-blue-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                                    Pilih satu kejuaraan terbaik
                                </p>
                                <p class="text-sm text-blue-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                                    Sertifikat Asli dibawa saat verifikasi berkas
                                </p>
                            </div>
                        </div>

                        <!-- Sub-form 2: Hafalan Al-Qur'an -->
                        <div class="bg-white border border-blue-200 rounded-xl p-5">
                            <h4 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <!-- <span class="bg-blue-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs">2</span> -->
                                Hafalan Al-Qur'an
                            </h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Hafalan</label>
                                <select id="hafalan_quran" 
                                    name="hafalan_quran" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    >
                                    <option value="">-- Pilih --</option>
                                    @foreach(getReferences("PRESTASI_KEAGAMAAN") as $i => $v)
                                        <option value="{{ $v->R_ID }}" {{ old('hafalan_quran', $isEdit ? ($siswa->SISWA_PRESTASI_KEAGAMAAN ?? '') : '') === $v->R_ID ? 'selected' : '' }}>{{ $v->R_INFO }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                @error('hafalan_quran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan Hafalan -->
                            <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg px-4 py-3 space-y-1">
                                <p class="text-sm text-blue-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                                    Sertifikat tahfidz asli dibawa saat verifikasi berkas
                                </p>
                                <p class="text-sm text-blue-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                                    Dilakukan pengujian saat verifikasi berkas
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <style>
                .jalur-input:checked + .jalur-option {
                    border-color: #2563eb;
                    background-color: #eff6ff;
                    box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
                }
                .jalur-input:checked + .jalur-option .checkmark-wrapper {
                    opacity: 1;
                }
                .sub-jalur-form {
                    transition: all 0.3s ease;
                }
                .sub-jalur-form.hidden {
                    display: none;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const radios = document.querySelectorAll('.jalur-input');
                    const subAfirmasi = document.getElementById('sub-afirmasi');
                    const subPrestasi = document.getElementById('sub-prestasi');

                    function updateSubForms() {
                        const selected = document.querySelector('.jalur-input:checked');
                        const value = selected ? selected.value : '';

                        subAfirmasi.classList.toggle('hidden', value !== 'JALUR_AFIRMASI');
                        subPrestasi.classList.toggle('hidden', value !== 'JALUR_PRESTASI');
                    }

                    radios.forEach(radio => radio.addEventListener('change', updateSubForms));

                    // Run on load (for edit mode)
                    updateSubForms();
                });
            </script>

            <!-- Section 4: Upload Dokumen -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">4</div>
                    <h2 class="text-2xl font-bold text-gray-800">Dokumen</h2>
                </div>

                <div class="mb-6 space-y-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3 mb-3">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 text-lg flex-shrink-0"></i>
                            <p class="text-sm text-blue-800">
                                Seluruh dokumen di bawah ini bersifat <strong>opsional</strong>. Verifikasi dilakukan secara langsung di madrasah pada tanggal yang sudah ditentukan.
                            </p>
                        </div>
                        <ul class="ml-8 space-y-1.5 text-sm text-blue-900 list-none">
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Pas Foto terbaru 3x4 sebanyak 2 lembar</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Fotokopi NISN</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Fotokopi Rapor kelas 5 semester 2</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Fotokopi Rapor kelas 6 semester 1</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Fotokopi Kartu Keluarga</li>
                            <li class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Fotokopi Akta Kelahiran</li>
                        </ul>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Foto --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Pas Foto
                            <!-- <span class="text-red-500">*</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_foto" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_foto" name="file_foto" class="hidden" 
                                        accept="image/jpeg,image/png,image/jpg"
                                        {{--
                                        {{ !$isEdit ? 'required' : '' }}
                                        --}}
                                        >
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG (Maks. 5MB)</p>
                            </div>

                            @if($isEdit && $siswa->SISWA_FILE_FOTO)
                                <div class="mt-4" id="existing-file_foto">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_FOTO) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto</p>
                                </div>
                            @endif
                            <div id="preview-file_foto" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_foto" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_foto')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_foto')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            NISN
                            <!-- <span class="text-blue-400">(opsional)</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_nisn" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_nisn" name="file_nisn" class="hidden" 
                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            @if($isEdit && $siswa->SISWA_FILE_NISN)
                                <div class="mt-4" id="existing-file_nisn">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_NISN) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                                </div>
                            @endif
                            <div id="preview-file_nisn" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_nisn" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_nisn')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_nisn')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rapor 52 --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Rapor kelas 5 semester 2
                            <!-- <span class="text-blue-400">(opsional)</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_rapor_52" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_rapor_52" name="file_rapor_52" class="hidden" 
                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            @if($isEdit && $siswa->SISWA_FILE_RAPOR_52)
                                <div class="mt-4" id="existing-file_rapor_52">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_RAPOR_52) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                                </div>
                            @endif
                            <div id="preview-file_rapor_52" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_rapor_52" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_rapor_52')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_rapor_52')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_rapor_52')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rapor 61 --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Rapor kelas 6 semester 1
                            <!-- <span class="text-blue-400">(opsional)</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_rapor_61" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_rapor_61" name="file_rapor_61" class="hidden" 
                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            @if($isEdit && $siswa->SISWA_FILE_RAPOR_61)
                                <div class="mt-4" id="existing-file_rapor_61">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_RAPOR_61) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                                </div>
                            @endif
                            <div id="preview-file_rapor_61" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_rapor_61" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_rapor_61')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_rapor_61')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_rapor_61')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KK --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kartu Keluarga
                            <!-- <span class="text-blue-400">(opsional)</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_kk" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_kk" name="file_kk" class="hidden" 
                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            @if($isEdit && $siswa->SISWA_FILE_KK)
                                <div class="mt-4" id="existing-file_kk">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_KK) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                                </div>
                            @endif
                            <div id="preview-file_kk" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_kk" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_kk')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_kk')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- AKTA --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Akta Kelahiran
                            <!-- <span class="text-blue-400">(opsional)</span> -->
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="file_akta" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" id="file_akta" name="file_akta" class="hidden" 
                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            @if($isEdit && $siswa->SISWA_FILE_AKTA)
                                <div class="mt-4" id="existing-file_akta">
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-sm text-gray-700">File saat ini: 
                                                <a href="{{ asset('storage/' . $siswa->SISWA_FILE_AKTA) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                                </div>
                            @endif
                            <div id="preview-file_akta" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_akta" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_akta')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_akta')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_akta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Tutup overlay locked --}}
            @if($isLocked)
                </div>{{-- end opacity/pointer wrapper --}}
            </div>{{-- end relative wrapper --}}
            @endif

            {{-- Submit — sembunyikan jika locked --}}
            @if(!$isLocked)
            <div class="p-8 bg-gray-50">
                <div class="flex items-start mb-4">
                    <input type="checkbox" id="persetujuan" name="persetujuan" class="mt-1 mr-3 w-4 h-4 text-blue-600" required>
                    <label for="persetujuan" class="text-sm text-gray-700">
                        Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. 
                        Apabila dikemudian hari terbukti tidak benar, saya bersedia menerima sanksi sesuai ketentuan yang berlaku.
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg flex items-center justify-center">
                        <!-- <i class="fas fa-paper-plane mr-2"></i> -->
                        D A F T A R
                    </button>
                </div>
            </div>
            @else
            {{-- Pesan info locked di bagian bawah form --}}
            <div class="p-8 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center gap-3 text-gray-500">
                    <i class="fas fa-lock text-lg"></i>
                    <span class="text-sm">Form terkunci karena status pendaftaran sudah <strong>{{ $currentStatus['label'] }}</strong>. Hubungi panitia untuk informasi lebih lanjut.</span>
                </div>
            </div>
            @endif

        </form>

    </div>
</div>

@push('scripts')
<script>

window.allKota = @json(
    $refKota
        ->groupBy('PROV_ID')
        ->map(function($items) {
        return $items->map(function($o) {
            return [
                'value' => $o->KOTA_ID,
                'label' => $o->KOTA_JENIS . ' ' . $o->KOTA_NAMA,
            ];
        })->values();
    })
);

window.allKecamatan = @json(
    $refKecamatan
        ->groupBy('KOTA_ID')
        ->map(function ($items) {
            return $items->map(function ($o) {
                return [
                    'value' => $o->KEC_ID,
                    'label' => $o->KEC_NAMA,
                ];
            })->values();
        })
);

window.allKelurahan = @json(
    $refKelurahan
        ->groupBy('KEC_ID')
        ->map(function ($items) {
            return $items->map(function ($o) {
                return [
                    'value' => $o->KEL_ID,
                    'label' => $o->KEL_NAMA,
                ];
            })->values();
        })
);

// File Upload Preview & Remove
const fileInputs = ['file_foto', 'file_nisn', 'file_kk', 'file_akta', 'file_rapor_52', 'file_rapor_61'];

fileInputs.forEach(inputId => {
    const input = document.getElementById(inputId);
    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = inputId === 'foto' ? 5 * 1024 * 1024 : 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert(`Ukuran file terlalu besar. Maksimal ${inputId === 'foto' ? '5MB' : '5MB'}`);
                    e.target.value = '';
                    return;
                }

                const preview = document.getElementById(`preview-${inputId}`);
                const namaFile = document.getElementById(`nama-${inputId}`);
                
                if (preview && namaFile) {
                    preview.classList.remove('hidden');
                    namaFile.textContent = file.name;
                }

                updateProgress();
            }
        });
    }
});

function previewFile(inputId) {
    const input = document.getElementById(inputId);
    const file = input.files[0];
    if (file) {
        const fileURL = URL.createObjectURL(file);
        window.open(fileURL, '_blank');
    }
}

function removeFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(`preview-${inputId}`);
    if (input) input.value = '';
    if (preview) preview.classList.add('hidden');
    updateProgress();
}


// Tambah di luar DOMContentLoaded, sejajar dengan updateProgress()
function updateSubForms() {
    const selected = document.querySelector('.jalur-input:checked');
    const value = selected ? selected.value : '';

    const subAfirmasi = document.getElementById('sub-afirmasi');
    const subPrestasi = document.getElementById('sub-prestasi');

    subAfirmasi.classList.toggle('hidden', value !== 'JALUR_AFIRMASI');
    subPrestasi.classList.toggle('hidden', value !== 'JALUR_PRESTASI');

    // Toggle required secara dinamis
    const pilihanAfirmasi = document.getElementById('pilihan_afirmasi');
    if (pilihanAfirmasi) {
        pilihanAfirmasi.required = (value === 'JALUR_AFIRMASI');
    }

    // Untuk prestasi: required dikelola via validatePrestasi(), bukan required HTML
    updateProgress();
}

// Cek apakah minimal 1 sub-form prestasi terisi
function isPrestasiFilled() {
    const jalur = document.querySelector('.jalur-input:checked');
    if (!jalur || jalur.value !== 'JALUR_PRESTASI') return true; // bukan prestasi, skip

    const tingkatJuara   = document.getElementById('tingkat_juara')?.value?.trim();
    const penyelenggara  = document.getElementById('penyelenggara_kejuaraan')?.value?.trim();
    const hafalanQuran   = document.getElementById('hafalan_quran')?.value?.trim();

    const kejuaraanFilled = tingkatJuara && penyelenggara;   // keduanya harus ada jika isi kejuaraan
    const hafalanFilled   = !!hafalanQuran;

    return kejuaraanFilled || hafalanFilled;
}


@if(!$isLocked)
function updateProgress() {
    const form = document.getElementById('registration-form');
    const requiredInputs = form.querySelectorAll('[required]');

    let filledCount = 0;
    let totalCount = 0;
    const radioGroups = {};

    requiredInputs.forEach(input => {
        // Skip input yang ada di dalam sub-form tersembunyi
        if (input.closest('.sub-jalur-form.hidden')) return;

        if (input.type === 'radio') {
            if (!radioGroups[input.name]) {
                radioGroups[input.name] = true;
                totalCount++;
                if (form.querySelector(`input[name="${input.name}"]:checked`)) filledCount++;
            }
        } else if (input.type === 'checkbox') {
            totalCount++;
            if (input.checked) filledCount++;
        } else if (input.type === 'file') {
            totalCount++;
            if (input.files.length > 0) filledCount++;
        } else {
            totalCount++;
            if (input.value.trim() !== '') filledCount++;
        }
    });

    // Tambahan slot progres untuk prestasi (minimal 1 sub-form)
    const jalur = document.querySelector('.jalur-input:checked');
    if (jalur?.value === 'JALUR_PRESTASI') {
        totalCount++;
        if (isPrestasiFilled()) filledCount++;
    }

    const percentage = totalCount === 0 ? 0 : Math.round((filledCount / totalCount) * 100);
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-percentage');
    if (progressBar) progressBar.style.width = percentage + '%';
    if (progressText) progressText.textContent = percentage;
}
@else
function updateProgress() {} // noop saat locked
@endif


document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Jalur radio → jalankan updateSubForms
    document.querySelectorAll('.jalur-input').forEach(radio => {
        radio.addEventListener('change', updateSubForms);
    });

    // Input sub-prestasi → update progress saat diketik/dipilih
    ['tingkat_juara', 'penyelenggara_kejuaraan', 'hafalan_quran'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateProgress);
            el.addEventListener('change', updateProgress);
        }
    });

    updateSubForms(); // init — set required & visibility sesuai nilai awal (edit mode)
    updateProgress();

    const provInput = document.getElementById('provinsi')
    const kotaWrapper = document.querySelector('[data-select-id="kota"]')
    if (!provInput || !kotaWrapper) return
    provInput.addEventListener('change', (e) => {
        if (e.detail?.isInit) return
        const provId = provInput.value
        const kotaData = window.allKota[provId] ?? []
        updateSelect('kota', kotaData)
        updateSelect('kecamatan', [])
        updateSelect('kelurahan', [])
    })

    const kotaInput = document.getElementById('kota')
    if (kotaInput) {
        kotaInput.addEventListener('change', (e) => {
            if (e.detail?.isInit) return
            const kotaId = kotaInput.value
            const kecData = window.allKecamatan[kotaId] ?? []
            updateSelect('kecamatan', kecData)
            updateSelect('kelurahan', [])
        })
    }

    const kecInput = document.getElementById('kecamatan')
    if (kecInput) {
        kecInput.addEventListener('change', (e) => {
            if (e.detail?.isInit) return
            const kecId = kecInput.value
            const kelData = window.allKelurahan[kecId] ?? []
            updateSelect('kelurahan', kelData)
        })
    }

    @if($isEdit)
        prefillSelects()
    @endif
});


function updateSelect(target, options, isInit = false) {
    window.dispatchEvent(new CustomEvent('update-options', {
        detail: { target, options, isInit }
    }))
}

async function prefillSelects() {
    function pickOption(id, value) {
        return new Promise(resolve => {
            const hidden = document.getElementById(id)
            if (!hidden || !value) return resolve()
            hidden.value = value
            const wrapper = document.querySelector(`[data-select-id="${id}"]`)
            if (!wrapper) return resolve()
            const alpine = wrapper._x_dataStack?.[0]
            if (!alpine) return resolve()
            const match = alpine.options.find(o => String(o.value) === String(value))
            if (match) alpine.selected = match
            hidden.dispatchEvent(new CustomEvent('change', { detail: { isInit: true } }))
            resolve()
        })
    }

    function fillAndPick(id, options, value) {
        return new Promise(resolve => {
            updateSelect(id, options)
            setTimeout(() => {
                const hidden = document.getElementById(id)
                if (hidden && value) hidden.value = value
                const wrapper = document.querySelector(`[data-select-id="${id}"]`)
                if (wrapper) {
                    const alpine = wrapper._x_dataStack?.[0]
                    if (alpine) {
                        const match = alpine.options.find(o => String(o.value) === String(value))
                        if (match) alpine.selected = match
                    }
                }
                resolve()
            }, 50)
        })
    }

    await pickOption('tempat_lahir', '{{ old("tempat_lahir", $isEdit ? $siswa->SISWA_TEMPAT_LAHIR : "") }}')
    await pickOption('provinsi', '{{ old("provinsi", $isEdit ? $siswa->SISWA_ALAMAT_PROVINSI : "") }}')

    const provId = '{{ old("provinsi", $isEdit ? $siswa->SISWA_ALAMAT_PROVINSI : "") }}'
    const kotaOptions = window.allKota[provId] ?? []
    await fillAndPick('kota', kotaOptions, '{{ old("kota", $isEdit ? $siswa->SISWA_ALAMAT_KOTA : "") }}')

    const kotaId = '{{ old("kota", $isEdit ? $siswa->SISWA_ALAMAT_KOTA : "") }}'
    const kecOptions = window.allKecamatan[kotaId] ?? []
    await fillAndPick('kecamatan', kecOptions, '{{ old("kecamatan", $isEdit ? $siswa->SISWA_ALAMAT_KECAMATAN : "") }}')

    const kecId = '{{ old("kecamatan", $isEdit ? $siswa->SISWA_ALAMAT_KECAMATAN : "") }}'
    const kelOptions = window.allKelurahan[kecId] ?? []
    await fillAndPick('kelurahan', kelOptions, '{{ old("kelurahan", $isEdit ? $siswa->SISWA_ALAMAT_KELURAHAN : "") }}')
}


// Form submit — hanya aktif jika tidak locked
@if(!$isLocked)
document.getElementById('registration-form').addEventListener('submit', async function(e) {
    e.preventDefault()
    const form = this

    const confirm = await Swal.fire({
        icon: 'question',
        title: 'Konfirmasi Pendaftaran?',
        html: 'Pastikan data pendaftaran sudah benar.<br><b></b>',
        showCancelButton: true,
        confirmButtonText: 'Ya, Daftar',
        cancelButtonText: 'Batal'
    })

    if (!confirm.isConfirmed) return

    const formData = new FormData(form)

    try {
        Swal.fire({
            title: 'Mendaftarkan...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        })

        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: formData
        })

        const data = await res.json()

        Swal.close()
        if (data.STATUS !== 'SUCCESS') {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.MESSAGE })
            return
        }

        await Swal.fire({ icon: 'success', title: 'Berhasil Mendaftar', text: data.MESSAGE })

    } catch (err) {
        console.log(err)
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan' })
    }
})
@endif

</script>
@endpush

@endsection