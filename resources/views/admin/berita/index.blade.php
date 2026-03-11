@extends('layouts.app')

@section('title', 'Manajemen Berita - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Berita dan Pengumuman</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola berita dan pengumuman yang ditampilkan di website.</p>
        </div>
        <a href="{{ route('admin.berita.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-plus"></i>
            Tambah Berita
        </a>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6">
            <table id="tabelBerita" class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="pb-3 pr-4 w-10">No</th>
                        <th class="pb-3 pr-4 w-16">Foto</th>
                        <th class="pb-3 pr-4">Judul</th>
                        <th class="pb-3 pr-4 w-32">Kategori</th>
                        <th class="pb-3 pr-4 w-36">Tanggal</th>
                        <th class="pb-3 pr-4 w-28">Status</th>
                        <th class="pb-3 w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    const table = $('#tabelBerita').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.berita.data') }}",
            type: "GET",
        },
        columns: [
            { data: 'no',        orderable: false, searchable: false, width: '40px' },
            { data: 'thumbnail', orderable: false, searchable: false, width: '60px' },
            { data: 'judul',     orderable: false },
            { data: 'kategori',  orderable: false, width: '120px' },
            { data: 'tanggal',   orderable: false, width: '140px' },
            { data: 'status',    orderable: false, width: '110px' },
            { data: 'aksi',      orderable: false, searchable: false, width: '110px' },
        ],
        language: {
            search:           "Cari:",
            lengthMenu:       "Tampilkan _MENU_ data",
            info:             "Menampilkan _START_ - _END_ dari _TOTAL_ berita",
            infoEmpty:        "Tidak ada data",
            zeroRecords:      "Tidak ada berita ditemukan",
            paginate: {
                previous: "&laquo;",
                next:     "&raquo;"
            },
            processing:       "Memuat data..."
        },
        pageLength: 10,
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4"lf>t<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4"ip>',
    });


    // Toggle Status (publish / tarik ke draft)
    window.toggleStatus = function(id, statusSaat) {
        const label = statusSaat === 'published' ? 'menarik berita ini ke draft' : 'mempublish berita ini';

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin ' + label + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '/admin/berita/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.STATUS === 'SUCCESS') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.MESSAGE, timer: 1500, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.MESSAGE });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
                }
            });
        });
    };


    // Hapus Berita
    window.hapusBerita = function(id) {
        Swal.fire({
            title: 'Hapus Berita?',
            text: 'Berita yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '/admin/berita/' + id,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.STATUS === 'SUCCESS') {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.MESSAGE, timer: 1500, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.MESSAGE });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
                }
            });
        });
    };
    

});
</script>
@endpush