@extends('layouts.app')
@section('title', 'Form Pendaftaran - PMBM')
@section('content')

<!-- Progress Bar -->
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

<!-- Form Container -->
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-lg p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Formulir Pendaftaran Siswa Baru</h1>
            <p class="text-blue-100">Tahun Ajaran 2026/2027</p>
        </div>

        <form id="registration-form" action="#" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-b-lg">
            @csrf
            
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Masukkan nama lengkap sesuai ijazah"
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="10 digit NISN"
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
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Kota kelahiran"
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Kota kelahiran"
                               required>
                        @error('nama_ibu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tempat Lahir -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih--</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_lahir" 
                               name="tanggal_lahir" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                            No. WhatsApp aktif <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="no_hp" 
                               name="no_hp" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]{10,13}"
                               required>
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Section Nilai Kelas 5 Semester 2 -->
                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">
                                Alamat
                            </span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih--</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih--</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih--</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kelurahan/Desa <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <option value="">-- Pilih--</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" 
                                  name="alamat" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                  placeholder="Masukkan alamat lengkap"
                                  required></textarea>
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
                    <h2 class="text-2xl font-bold text-gray-800">Data Sekolah Asal</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Sekolah -->
                    <div class="md:col-span-2">
                        <label for="asal_sekolah" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Sekolah Asal <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="asal_sekolah" 
                               name="asal_sekolah" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: SMP Negeri 1 Semarang"
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
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                        @error('tahun_lulus')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nilai Rata-rata -->
                    <div>
                        <label for="nilai_rata" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nilai Rata-rata Ijazah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_rata" 
                               name="nilai_rata" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_rata')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Section Nilai Kelas 5 Semester 2 -->
                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">
                                Nilai rapor kelas 5 semester 2
                            </span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <div>
                        <label for="nilai_52_mtk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matematika <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_mtk" 
                               name="nilai_52_mtk" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_mtk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_ipa" class="block text-sm font-semibold text-gray-700 mb-2">
                            IPA <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_ipa" 
                               name="nilai_52_ipa" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_ipa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_bind" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matematika <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_bind" 
                               name="nilai_52_bind" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_bind')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_pai" class="block text-sm font-semibold text-gray-700 mb-2">
                            IPA <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_pai" 
                               name="nilai_52_pai" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_pai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Section Nilai Kelas 5 Semester 2 -->
                    <div class="md:col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="h-px bg-gray-300 flex-1"></div>
                            <span class="px-3 text-sm font-semibold text-gray-600">
                                Nilai rapor kelas 6 semester 1
                            </span>
                            <div class="h-px bg-gray-300 flex-1"></div>
                        </div>
                    </div>

                    <div>
                        <label for="nilai_52_mtk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matematika <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_mtk" 
                               name="nilai_52_mtk" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_mtk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_ipa" class="block text-sm font-semibold text-gray-700 mb-2">
                            IPA <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_ipa" 
                               name="nilai_52_ipa" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_ipa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_bind" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matematika <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_bind" 
                               name="nilai_52_bind" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_bind')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_52_pai" class="block text-sm font-semibold text-gray-700 mb-2">
                            IPA <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_52_pai" 
                               name="nilai_52_pai" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_52_pai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Section 3: Pilihan Jalur Pendaftaran -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">3</div>
                    <h2 class="text-2xl font-bold text-gray-800">Pilihan Jalur Pendaftaran</h2>
                </div>
                
                <div class="mb-4">
                    <!-- <label class="block text-sm font-semibold text-gray-700 mb-4">
                        Pilih Jalur Pendaftaran <span class="text-red-500">*</span>
                    </label> -->
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Jalur Reguler -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="reguler" class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Reguler</h3>
                                    <!-- <p class="text-sm text-gray-600">Pendaftaran umum berdasarkan nilai rapor</p> -->
                                </div>
                                <div class="checkmark-wrapper mt-4 flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Jalur Prestasi -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="prestasi" class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-yellow-100 text-yellow-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Prestasi</h3>
                                    <!-- <p class="text-sm text-gray-600">Untuk siswa berprestasi akademik & non-akademik</p> -->
                                </div>
                                <div class="checkmark-wrapper mt-4 flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Jalur Afirmasi -->
                        <label class="jalur-card cursor-pointer">
                            <input type="radio" name="jalur_pendaftaran" value="afirmasi" class="hidden jalur-input" required>
                            <div class="border-2 border-gray-300 rounded-xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-lg jalur-option">
                                <div class="flex flex-col items-center text-center">
                                    <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Jalur Afirmasi</h3>
                                    <!-- <p class="text-sm text-gray-600">Untuk siswa afirmasi</p> -->
                                </div>
                                <div class="checkmark-wrapper mt-4 flex justify-center opacity-0 transition-opacity duration-300">
                                    <div class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    @error('jalur_pendaftaran')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
            </style>

            <!-- Section 4: Upload Dokumen -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">4</div>
                    <h2 class="text-2xl font-bold text-gray-800">Upload Dokumen</h2>
                </div>

                <div class="space-y-6">

                    <!-- Upload Ijazah -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Foto <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="ijazah" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" 
                                           id="ijazah" 
                                           name="ijazah" 
                                           class="hidden" 
                                           accept="image/jpeg,image/png,image/jpg"
                                           required>
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG (Max: 3MB)</p>
                            </div>
                            <div id="preview-ijazah" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-ijazah" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('ijazah')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('ijazah')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('ijazah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            SKL <span class="text-blue-400">(opsional)</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                                <label for="ijazah" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" 
                                           id="ijazah" 
                                           name="ijazah" 
                                           class="hidden" 
                                           accept="application/pdf,image/jpeg,image/png,image/jpg"
                                           required>
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Max: 3MB)</p>
                            </div>
                            <div id="preview-ijazah" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-ijazah" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('ijazah')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('ijazah')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('ijazah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Submit Button -->
            <div class="p-8 bg-gray-50">
                <div class="flex items-start mb-4">
                    <input type="checkbox" 
                           id="persetujuan" 
                           name="persetujuan" 
                           class="mt-1 mr-3 w-4 h-4 text-blue-600"
                           required>
                    <label for="persetujuan" class="text-sm text-gray-700">
                        Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. 
                        Apabila dikemudian hari terbukti tidak benar, saya bersedia menerima sanksi sesuai ketentuan yang berlaku.
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        D A F T A R
                    </button>
                    <!-- <button type="reset" 
                            class="flex-1 bg-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-400 transition flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>
                        Reset Form
                    </button> -->
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// File Upload Preview & Remove
const fileInputs = ['pas_foto', 'ijazah', 'kartu_keluarga', 'akta_kelahiran'];

fileInputs.forEach(inputId => {
    const input = document.getElementById(inputId);
    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 5MB for documents, 2MB for photos)
                const maxSize = inputId === 'pas_foto' ? 2 * 1024 * 1024 : 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert(`Ukuran file terlalu besar. Maksimal ${inputId === 'pas_foto' ? '2MB' : '5MB'}`);
                    e.target.value = '';
                    return;
                }

                // Show preview
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

// Preview file in new tab
function previewFile(inputId) {
    const input = document.getElementById(inputId);
    const file = input.files[0];
    
    if (file) {
        const fileURL = URL.createObjectURL(file);
        window.open(fileURL, '_blank');
    }
}

// Remove uploaded file
function removeFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(`preview-${inputId}`);
    
    if (input) {
        input.value = '';
    }
    
    if (preview) {
        preview.classList.add('hidden');
    }
    
    updateProgress();
}

// Progress bar calculation
function updateProgress() {
    const form = document.getElementById('registration-form');
    const requiredInputs = form.querySelectorAll('[required]');
    let filledCount = 0;
    
    requiredInputs.forEach(input => {
        if (input.type === 'checkbox') {
            if (input.checked) filledCount++;
        } else if (input.type === 'file') {
            if (input.files.length > 0) filledCount++;
        } else if (input.value.trim() !== '') {
            filledCount++;
        }
    });
    
    const percentage = Math.round((filledCount / requiredInputs.length) * 100);
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-percentage');
    
    if (progressBar) {
        progressBar.style.width = percentage + '%';
    }
    
    if (progressText) {
        progressText.textContent = percentage;
    }
}

// Auto-update progress on input change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Initial progress update
    updateProgress();
});

// Form validation before submit
document.getElementById('registration-form').addEventListener('submit', function(e) {
    const persetujuan = document.getElementById('persetujuan');
    
    if (!persetujuan.checked) {
        e.preventDefault();
        alert('Anda harus menyetujui pernyataan sebelum mengirim formulir');
        return false;
    }
    
    // Additional validation can be added here
    return true;
});

</script>
@endpush

@endsection