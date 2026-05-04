@extends('layouts.app')

@section('title', 'Jurnal Pendaftaran')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jurnal Pendaftaran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Detail jurnal ranking pendaftaran murid</p>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4 mb-4 flex flex-col sm:flex-row sm:items-center gap-3">
        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">
            Filter Jalur:
        </label>
        <select id="jalur-filter"
            class="w-full sm:w-64 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="all">Semua</option>
            <option value="JALUR_REGULER">Reguler</option>
            <option value="JALUR_PRESTASI">Prestasi</option>
            <option value="JALUR_AFIRMASI">Afirmasi</option>
        </select>
        <div class="w-full"></div>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full p-4">
            <table id="datatable1Id" class="w-full text-sm" style="min-width: 640px;">
                <thead>
                    <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left" style="width: 10%;">&nbsp;</th>
                        <th class="px-4 py-4 text-left" style="width: 20%;">Skor</th>
                        <th class="px-4 py-4 text-left" style="width: 50%;">Nama</th>
                        <th class="px-4 py-4 text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== MODAL ===== --}}
<div id="modal-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div id="modal-panel" class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden ring-1 ring-black/5">

        <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 text-base">Detail Jurnal Siswa</h2>
            <button onclick="closeModal()" class="text-gray-300 hover:text-gray-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-6 pb-6 space-y-4" id="modal-body">
            {{-- Diisi dinamis via JS --}}
            <div id="modal-loading" class="text-center py-8 text-sm text-gray-400">Memuat...</div>
        </div>

        <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100">
            <button onclick="closeModal()"
                class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition">
                Tutup
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
let activeJalur = 'all';
let datatable1  = null;

$(document).ready(function () {

    // ── Init DataTable ────────────────────────────────────────────────────
    datatable1 = $('#datatable1Id').DataTable({
        processing : true,
        serverSide : true,
        scrollX    : false,
        ajax: {
            url : '/jurnal/datatable',
            type: 'GET',
            data: function (d) {
                d.jalur = activeJalur;
            },
        },
        columns: [
            { data: 'ranking', orderable: true },
            { data: 'skor',    orderable: false },
            { data: 'judul',   orderable: false },
            { data: 'aksi',    orderable: false, className: 'text-center' },
        ],
        language: {
            processing       : '<div class="text-blue-500 text-sm py-2">Memuat data...</div>',
            emptyTable       : '<div class="py-12 text-gray-300 text-sm">Tidak ada data</div>',
            zeroRecords      : '<div class="py-12 text-gray-300 text-sm">Data tidak ditemukan</div>',
            info             : 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty        : 'Tidak ada data',
            infoFiltered     : '(disaring dari _MAX_ total data)',
            search           : '',
            searchPlaceholder: 'Cari nama / NISN...',
            lengthMenu       : 'Tampilkan _MENU_ data',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' },
        },
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4"lf>t<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-t border-gray-100"ip>',
    });

    // ── Style search & length ─────────────────────────────────────────────
    $('#datatable1Id_filter input').addClass(
        'border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition ml-2'
    );
    $('#datatable1Id_length select').addClass(
        'border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition mx-1'
    );

    // ── Filter jalur ──────────────────────────────────────────────────────
    $('#jalur-filter').on('change', function () {
        activeJalur = $(this).val();
        datatable1.ajax.reload();
    });

    // ── Tutup modal klik backdrop ─────────────────────────────────────────
    $('#modal-backdrop').on('click', function (e) {
        if (e.target === this) closeModal();
    });
});

// ── openDetail: fetch _raw dari server-side row via DataTable API ─────────────
// Karena serverSide:true, kita simpan _raw saat draw lewat rowCallback,
// atau cukup ambil dari data() yang di-cache DataTable untuk halaman aktif.
function openDetail(id) {
    showModal();
    $('#modal-loading').show();
    $('#modal-loading').siblings('.detail-content').remove();

    // Cari di cache halaman aktif
    const row = datatable1.rows().data().toArray().find(r => r._raw && r._raw.id == id);

    if (!row) {
        $('#modal-loading').text('Data tidak ditemukan.');
        return;
    }

    renderModal(row._raw);
}

function renderModal(raw) {
    const jalurMap = {
        'JALUR_REGULER' : '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Reguler</span>',
        'JALUR_PRESTASI': '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-700">Prestasi</span>',
        'JALUR_AFIRMASI': '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Afirmasi</span>',
    };

    const s = raw.skor_detail; // objek dari _raw (lihat controller)

    const row = (label, nilai, bobot, hasil) => `
        <tr class="border-b border-gray-50 last:border-0">
            <td class="py-2.5 pr-3 text-xs font-medium text-gray-700 whitespace-nowrap">${label}</td>
            <td class="py-2.5 pr-3 text-xs text-gray-600 text-right">${nilai}</td>
            <td class="py-2.5 pr-3 text-xs text-gray-400 text-center">${bobot}</td>
            <td class="py-2.5 text-xs font-semibold text-gray-800 text-right">${hasil}</td>
        </tr>
    `;

    const html = `
        <div class="detail-content space-y-5">

            {{-- Info dasar --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama Siswa</p>
                    <p class="text-sm font-medium text-gray-800">${raw.nama}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Jalur</p>
                    ${jalurMap[raw.jalur] ?? raw.jalur}
                </div>
            </div>

            {{-- Tabel breakdown skor --}}
            <div class="mt-4">
                <p class="text-xs font-semibold text-gray-400 tracking-wider mb-2">Rincian Skor</p>
                <div class="rounded-xl border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider">
                                <th class="px-3 py-2 text-left">Aspek</th>
                                <th class="px-3 py-2 text-right">Nilai</th>
                                <th class="px-3 py-2 text-center">x</th>
                                <th class="px-3 py-2 text-right">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 px-3">
                            ${
                                s.map(item => {
                                    const [label, nilai, bobot] = item;

                                    // filter jalur
                                    if (label.startsWith('E') && raw.jalur !== "JALUR_AFIRMASI") return '';
                                    if ((label.startsWith('F') || label.startsWith('G')) && raw.jalur !== "JALUR_PRESTASI") return '';

                                    const isUmur = label.startsWith('H');

                                    const nilaiFormatted = isUmur
                                        ? nilai
                                        : Number(nilai).toFixed(2);

                                    const hasil = isUmur
                                        ? nilai
                                        : (nilai * bobot).toFixed(2);

                                    return row(
                                        label,
                                        nilaiFormatted,
                                        `x${bobot}`,
                                        hasil
                                    );
                                }).join('')
                            }
                        </tbody>
                    </table>
                </div>

                {{-- Total --}}
                <div class="flex justify-between items-center mt-3 px-3 py-2.5 bg-blue-50 rounded-xl">
                    <span class="text-sm font-semibold text-blue-700">Total Skor</span>
                    <span class="text-lg font-bold text-blue-700">${raw.skor_total}</span>
                </div>
            </div>

        </div>
    `;

    $('#modal-loading').hide();
    $('#modal-body').append(html);
}


function showModal()  { $('#modal-backdrop').removeClass('hidden'); }
function closeModal() { $('#modal-backdrop').addClass('hidden'); }
</script>
@endpush