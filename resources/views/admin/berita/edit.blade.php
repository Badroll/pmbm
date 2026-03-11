@extends('layouts.app')

@section('title', 'Edit Berita - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.berita.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Berita</h1>
            <p class="text-sm text-gray-500">Perbarui informasi berita yang sudah ada.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input type="text" id="BERITA_JUDUL" name="BERITA_JUDUL"
                       value="{{ $berita->BERITA_JUDUL }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
                       placeholder="Tulis judul berita...">
            </div>

            {{-- Kategori & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="BERITA_KATEGORI" name="BERITA_KATEGORI"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ $berita->BERITA_KATEGORI === $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="BERITA_STATUS" name="BERITA_STATUS"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 bg-white">
                        <option value="draft"     {{ $berita->BERITA_STATUS === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $berita->BERITA_STATUS === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Thumbnail
                    <span class="font-normal text-gray-400 ml-1">(JPG/PNG/WEBP, maks 2MB)</span>
                </label>

                <div class="flex items-start gap-4">
                    {{-- Preview --}}
                    <div id="preview_container_thumbnail" class="hidden flex-shrink-0">
                        <div class="relative w-28 h-28">
                            <img id="preview_thumbnail" src="" alt="Preview"
                                 class="w-28 h-28 object-cover rounded-xl border border-gray-200">
                            <button type="button" id="hapus_thumbnail"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors shadow">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Input --}}
                    <label for="thumbnail"
                           class="flex flex-col items-center justify-center w-28 h-28 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-colors">
                        <i class="fas fa-image text-2xl text-gray-400 mb-1"></i>
                        <span class="text-xs text-gray-400 text-center px-2">Ganti gambar</span>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden">
                    </label>
                </div>
                <input type="hidden" id="hapus_thumbnail_flag" name="hapus_thumbnail" value="0">
            </div>

            {{-- Isi Berita (Quill) --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Isi Berita <span class="text-red-500">*</span>
                </label>
                <div id="quill-editor"
                     class="border border-gray-300 rounded-b-lg bg-white"
                     style="min-height: 280px;"></div>
                <input type="hidden" id="BERITA_ISI" name="BERITA_ISI" value="{{ $berita->BERITA_ISI }}">
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.berita.index') }}"
                   class="px-5 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="button" id="btnUpdate"
                        class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors">
                    <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Quill.js --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
$(document).ready(function () {

    // ---- Quill Editor ----
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi berita di sini...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'link'],
                ['clean']
            ]
        }
    });

    // Set isi awal dari data yang sudah ada
    const existingContent = $('#BERITA_ISI').val();
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // ---- Preview Thumbnail existing ----
    @if($berita->BERITA_THUMBNAIL)
        setPreviewFromUrl('thumbnail', 'preview_thumbnail', "{{ asset('storage/' . $berita->BERITA_THUMBNAIL) }}");
    @endif

    // ---- Preview Thumbnail baru ----
    registerPreviewFile(
        'thumbnail',
        'preview_thumbnail',
        2048,
        function () {
            $('#hapus_thumbnail_flag').val('1');
        }
    );

    // ---- Submit Update ----
    $('#btnUpdate').on('click', function () {
        // Sync Quill ke hidden input
        $('#BERITA_ISI').val(quill.root.innerHTML);

        const judul    = $('#BERITA_JUDUL').val().trim();
        const kategori = $('#BERITA_KATEGORI').val();
        const isi      = quill.getText().trim();

        if (!judul) {
            Swal.fire({ icon: 'warning', title: 'Judul wajib diisi', text: 'Mohon isi judul berita.' });
            return;
        }
        if (!kategori) {
            Swal.fire({ icon: 'warning', title: 'Kategori wajib dipilih', text: 'Mohon pilih kategori berita.' });
            return;
        }
        if (!isi || isi.length < 10) {
            Swal.fire({ icon: 'warning', title: 'Isi berita wajib diisi', text: 'Mohon tulis isi berita.' });
            return;
        }

        const formData = new FormData();
        formData.append('_token',          $('meta[name="csrf-token"]').attr('content'));
        formData.append('BERITA_JUDUL',    judul);
        formData.append('BERITA_KATEGORI', kategori);
        formData.append('BERITA_STATUS',   $('#BERITA_STATUS').val());
        formData.append('BERITA_ISI',      $('#BERITA_ISI').val());
        formData.append('hapus_thumbnail', $('#hapus_thumbnail_flag').val());

        const thumbnailFile = $('#thumbnail')[0].files[0];
        if (thumbnailFile) {
            formData.append('thumbnail', thumbnailFile);
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1.5"></i> Menyimpan...');

        $.ajax({
            url:         "{{ route('admin.berita.update', $berita->BERITA_ID) }}",
            type:        'POST',
            data:        formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'SUCCESS') {
                    Swal.fire({
                        icon: 'success', title: 'Berhasil', text: res.message,
                        timer: 1500, showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.berita.index') }}";
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                    btn.prop('disabled', false).html('<i class="fas fa-save mr-1.5"></i> Simpan Perubahan');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0][0];
                    Swal.fire({ icon: 'warning', title: 'Validasi gagal', text: firstError });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
                }
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1.5"></i> Simpan Perubahan');
            }
        });
    });

});
</script>
@endpush