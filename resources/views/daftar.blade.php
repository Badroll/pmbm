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

        <form id="registration-form" action="{{ url('daftar') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-b-lg">
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Nama Rumisih"
                               required>
                        @error('nama_ibu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tempat Lahir -->
                    {{--
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>

                        <div x-data="{
                                open: false,
                                search: '',
                                selected: null,
                                options: [],
                                filtered: [],
                                init() {
                                    this.options = JSON.parse(this.$el.dataset.options)
                                    this.filtered = this.options
                                    this.$watch('search', value => {
                                        if (value === '') {
                                            this.filtered = this.options
                                        } else {
                                            this.filtered = this.options.filter(o =>
                                                o.label.toLowerCase().includes(value.toLowerCase())
                                            )
                                        }
                                    })
                                }
                            }"
                            data-options="{{ json_encode($refKota->map(fn($k) => ['value' => $k->KOTA_ID, 'label' => $k->KOTA_JENIS . ' ' . $k->KOTA_NAMA])) }}"
                            class="relative">

                            <input type="hidden" id="tempat_lahir" name="tempat_lahir" value="" required>

                            <button type="button"
                                @click="open = !open; $nextTick(() => { if(open) $refs.searchInput.focus() })"
                                class="w-full flex items-center justify-between px-4 py-3 bg-white border rounded-lg transition"
                                :class="open ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-300'">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'"
                                    x-text="selected ? selected.label : '-- Pilih --'"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                @click.outside="open = false"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">

                                <div class="p-2 border-b border-gray-100">
                                    <input type="text"
                                        x-model="search"
                                        x-ref="searchInput"
                                        placeholder="Cari kota..."
                                        @click.stop
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <ul class="max-h-52 overflow-y-auto py-1">
                                    <template x-for="option in filtered" :key="option.value">
                                        <li @click="
                                                selected = option; 
                                                open = false; 
                                                search = '';
                                                filtered = options;
                                                document.getElementById('tempat_lahir').value = option.value;
                                                document.getElementById('tempat_lahir').dispatchEvent(new Event('change'));
                                            "
                                            class="px-4 py-2 text-sm cursor-pointer transition"
                                            :class="selected?.value === option.value
                                                ? 'bg-blue-50 text-blue-700 font-medium'
                                                : 'text-gray-700 hover:bg-gray-50'">
                                            <span x-text="option.label"></span>
                                        </li>
                                    </template>

                                    <li x-show="filtered.length === 0"
                                        class="px-4 py-2 text-sm text-gray-400 text-center italic">
                                        Kota tidak ditemukan
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    --}}

                    <x-select-searchable
                        label="Tempat Kelahiran"
                        name="tempat_lahir"
                        id="tempat_lahir"
                        :options="$refKota->map(fn($o) => [
                            'value' => $o->KOTA_ID,
                            'label' => $o->KOTA_JENIS . ' ' . $o->KOTA_NAMA,
                        ])->values()"
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]{10,13}"
                               required>
                        @error('no_wa')
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

                    <x-select-searchable
                        label="Provinsi"
                        name="provinsi"
                        id="provinsi"
                        :options="$refProvinsi->map(fn($o) => [
                            'value' => $o->PROV_ID,
                            'label' => $o->PROV_NAMA,
                        ])->values()"
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
                            Bahasa Indonesia <span class="text-red-500">*</span>
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
                            PAI / Aqidah Akhlak <span class="text-red-500">*</span>
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

                    <!-- Sub Section Nilai Kelas 6 Semester 1 -->
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
                        <label for="nilai_61_mtk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matematika <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_61_mtk" 
                               name="nilai_61_mtk" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_61_mtk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_61_ipa" class="block text-sm font-semibold text-gray-700 mb-2">
                            IPA <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_61_ipa" 
                               name="nilai_61_ipa" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_61_ipa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_61_bind" class="block text-sm font-semibold text-gray-700 mb-2">
                            Bahasa Indonesia <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_61_bind" 
                               name="nilai_61_bind" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_61_bind')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_61_pai" class="block text-sm font-semibold text-gray-700 mb-2">
                            PAI / Aqidah Akhlak <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilai_61_pai" 
                               name="nilai_61_pai" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Contoh: 85.50"
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        @error('nilai_61_pai')
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
                                <label for="file_pas_foto" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" 
                                           id="file_pas_foto" 
                                           name="file_pas_foto" 
                                           class="hidden" 
                                           accept="image/jpeg,image/png,image/jpg"
                                           required>
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG (Max: 3MB)</p>
                            </div>
                            <div id="preview-file_pas_foto" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_pas_foto" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_pas_foto')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_pas_foto')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_pas_foto')
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
                                <label for="file_skl" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        <i class="fas fa-upload mr-2"></i>Unggah File
                                    </span>
                                    <input type="file" 
                                           id="file_skl" 
                                           name="file_skl" 
                                           class="hidden" 
                                           accept="application/pdf,image/jpeg,image/png,image/jpg"
                                           required>
                                </label>
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, JPG, PNG (Max: 3MB)</p>
                            </div>
                            <div id="preview-file_skl" class="mt-4 hidden">
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span id="nama-file_skl" class="text-sm text-gray-700"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewFile('file_skl')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button type="button" onclick="removeFile('file_skl')" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file_skl')
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

window.allKota = @json(
    $refKota->groupBy('PROV_ID')->map(function($items) {
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
        ->map(fn($items)=>
            $items->map(fn($o)=>[
                'value' => $o->KEC_ID,
                'label' => $o->KEC_NAMA,
            ])->values()
        )
);

window.allKelurahan = @json(
    $refKelurahan
        ->groupBy('KEC_ID')
        ->map(fn($items)=>
            $items->map(fn($o)=>[
                'value'=>$o->KEL_ID,
                'label'=>$o->KEL_NAMA,
            ])->values()
        )
);

    
// File Upload Preview & Remove
const fileInputs = ['pas_foto', 'skl'];

fileInputs.forEach(inputId => {
    const input = document.getElementById(inputId);
    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 5MB for documents, 2MB for photos)
                const maxSize = inputId === 'pas_foto' ?
                    2 * 1024 * 1024 
                    : 5 * 1024 * 1024;
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
    let totalCount = 0;

    const radioGroups = {};

    requiredInputs.forEach(input => {
        if (input.type === 'radio') {
            if (!radioGroups[input.name]) {
                radioGroups[input.name] = form.querySelectorAll(`input[name="${input.name}"][required]`);
                totalCount++;

                const checked = form.querySelector(`input[name="${input.name}"]:checked`);
                if (checked) filledCount++;
            }
        } 
        else if (input.type === 'checkbox') {
            totalCount++;
            if (input.checked) filledCount++;
        } 
        else if (input.type === 'file') {
            totalCount++;
            if (input.files.length > 0) filledCount++;
        } 
        else {
            totalCount++;
            if (input.value.trim() !== '') filledCount++;
        }
    });

    const percentage = Math.round((filledCount / totalCount) * 100);

    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-percentage');

    if (progressBar) progressBar.style.width = percentage + '%';
    if (progressText) progressText.textContent = percentage;
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

    const provInput = document.getElementById('provinsi')
    const kotaWrapper = document.querySelector('[data-select-id="kota"]')
    if (!provInput || !kotaWrapper) return
    provInput.addEventListener('change', () => {
        const provId = provInput.value
        const kotaData = window.allKota[provId] ?? []
        
        // isi
        updateSelect('kota', kotaData)

        // reset bawahnya
        updateSelect('kecamatan', [])
        updateSelect('kelurahan', [])
    })

    const kotaInput = document.getElementById('kota')
    if (kotaInput) {
        kotaInput.addEventListener('change', () => {
            const kotaId = kotaInput.value
            const kecData = window.allKecamatan[kotaId] ?? []
            
            // fill
            updateSelect('kecamatan', kecData)

            // reset kelurahan
            updateSelect('kelurahan', [])
        })
    }

    const kecInput = document.getElementById('kecamatan')
    if (kecInput) {
        kecInput.addEventListener('change', () => {
            const kecId = kecInput.value
            const kelData = window.allKelurahan[kecId] ?? []

            // fill
            updateSelect('kelurahan', kelData)
        })
    }

});

function updateSelect(target, options){
    window.dispatchEvent(new CustomEvent('update-options',{
        detail:{ target, options }
    }))
}


// Form validation before submit
// // manual way
// document.getElementById('registration-form').addEventListener('submit', function(e) {
//     const persetujuan = document.getElementById('persetujuan');
    
//     if (!persetujuan.checked) {
//         e.preventDefault();
//         alert('Anda harus menyetujui pernyataan sebelum mengirim formulir');
//         return false;
//     }
    
//     // Additional validation can be added here
//     return true;
// });

// js way
document.getElementById('registration-form').addEventListener('submit', async function(e) {
    e.preventDefault()

    const form = this
    const persetujuan = document.getElementById('persetujuan')

    if (!persetujuan.checked) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Anda harus menyetujui pernyataan sebelum mengirim formulir'
        })
        return
    }

    // 🔥 KONFIRMASI
    const confirm = await Swal.fire({
        icon: 'question',
        title: 'Kirim Pengajuan?',
        html: 'Pastikan data sudah benar.<br><b>Data tidak dapat diubah setelah dikirim.</b>',
        showCancelButton: true,
        confirmButtonText: 'Ya, kirim',
        cancelButtonText: 'Batal'
    })

    if (!confirm.isConfirmed) return

    const formData = new FormData(form)
    console.log(formData)
    //return

    try {

        Swal.fire({
            title: 'Mengirim...',
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
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.MESSAGE
            })
            return
        }

        await Swal.fire({
            icon: 'success',
            title: 'Berhasil mendaftar dengan nomor '+data.PAYLOAD+'',
            text: data.MESSAGE
        })

        // form.reset()
        // go to detail pendaftaran

    } catch (err) {
        console.log(err)
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan'
        })
    }
})


</script>
@endpush

@endsection