{{--
    Partial: alamat-fields.blade.php
    Pemakaian:
        @include('ppdb.partials.alamat-fields', [
            'prefix'  => 'sd_ayah',         // prefix nama field, misal sd_ayah_provinsi
            'section' => 'section_alamat_ayah',  // nama section untuk $dis()
            'data'    => $isEdit ? $sd : null,   // object model atau null
            'fields'  => ['provinsi','kabupaten','kecamatan','kelurahan','rt_rw','alamat','kode_pos'],
        ])
--}}

@php
    $labels = [
        'provinsi'   => 'Provinsi',
        'kabupaten'  => 'Kabupaten/Kota',
        'kecamatan'  => 'Kecamatan',
        'kelurahan'  => 'Kelurahan/Desa',
        'rt_rw'      => 'RT/RW',
        'alamat'     => 'Alamat Lengkap',
        'kode_pos'   => 'Kode Pos',
    ];
    $placeholders = [
        'provinsi'   => 'Pilih atau ketik provinsi',
        'kabupaten'  => 'Pilih atau ketik kabupaten/kota',
        'kecamatan'  => 'Nama kecamatan',
        'kelurahan'  => 'Nama kelurahan/desa',
        'rt_rw'      => 'Contoh: 003/007',
        'alamat'     => 'Nama jalan, nomor rumah, dll.',
        'kode_pos'   => '5 digit kode pos',
    ];
    $isTextarea = ['alamat'];
    $isSpanFull = ['provinsi','kabupaten','alamat'];
@endphp

@foreach($fields as $field)
    @php
        $inputName = $prefix . '_' . $field;
        $inputId   = $inputName;
        $dbCol     = strtoupper($inputName);
        $oldVal    = old($inputName, $data ? ($data->$dbCol ?? '') : '');
        $label     = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        $ph        = $placeholders[$field] ?? '';
        $spanFull  = in_array($field, $isSpanFull);
    @endphp

    <div class="{{ $spanFull ? 'md:col-span-2' : '' }}">
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
        </label>

        @if(in_array($field, $isTextarea))
            <textarea id="{{ $inputId }}"
                name="{{ $inputName }}"
                rows="2"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="{{ $ph }}"
                {{ $dis($section) }}>{{ $oldVal }}</textarea>
        @elseif($field === 'kode_pos')
            <input type="text"
                id="{{ $inputId }}"
                name="{{ $inputName }}"
                value="{{ $oldVal }}"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="{{ $ph }}"
                maxlength="5"
                pattern="[0-9]{5}"
                {{ $dis($section) }}>
        @else
            <input type="text"
                id="{{ $inputId }}"
                name="{{ $inputName }}"
                value="{{ $oldVal }}"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="{{ $ph }}"
                {{ $dis($section) }}>
        @endif

        @error($inputName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endforeach