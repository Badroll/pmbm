@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Akun</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola akun pengguna</p>
        </div>
        <button
            onclick="openCreate()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Akun
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4 mb-4 flex flex-col sm:flex-row sm:items-center gap-3">
    
        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">
            Filter Role:
        </label>

        <select id="role-filter"
            class="w-full sm:w-64 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            
            <option value="all">Semua</option>
            <option value="ROLE_ADMIN_BERITA">Admin Berita</option>
            <option value="ROLE_ADMIN_BERKAS">Admin Berkas</option>
            <option value="ROLE_SISWA">Siswa</option>
        </select>

        <div class="w-full"></div>

    </div>

    {{-- ===== TABLE WRAPPER (horizontal scroll for mobile) ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full p-4">
            <table id="datatable1Id" class="w-full text-sm" style="min-width: 640px;">
                <thead>
                    <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left w-10">#</th>
                        <th class="px-4 py-4 text-left">Username</th>
                        <th class="px-4 py-4 text-left">Role</th>
                        <th class="px-4 py-4 text-left">Status</th>
                        <th class="px-4 py-4 text-left">Login Terakhir</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== MODAL BACKDROP ===== --}}
<div id="modal-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div id="modal-panel" class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden ring-1 ring-black/5">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
            <h2 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Akun Baru</h2>
            <button onclick="closeModal()" class="text-gray-300 hover:text-gray-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-6 space-y-3">
            <input type="hidden" id="form-id">

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Email / No. Telp</label>
                <input id="form-username" type="text" placeholder="johndoe"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                    <select id="form-role"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-white">
                        <option value="">Pilih Role</option>
                        <option value="ROLE_ADMIN_BERITA">Admin Berita</option>
                        <option value="ROLE_ADMIN_BERKAS">Admin Verifikasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select id="form-status"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-white">
                        <option value="">Pilih Status</option>
                        <option value="ACCOUNT_STATUS_ACTIVE">Aktif</option>
                        <option value="ACCOUNT_STATUS_INACTIVE">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">
                    Password
                    <span id="pw-hint" class="text-gray-300 font-normal hidden">(kosongkan jika tidak diubah)</span>
                </label>
                <input id="form-password" type="password" placeholder="••••••••"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            </div>

            <div id="confirm-password-wrap">
                <label class="block text-xs font-medium text-gray-500 mb-1">Konfirmasi Password</label>
                <input id="form-password-confirmation" type="password" placeholder="••••••••"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            </div>

            <p id="error-msg" class="text-xs text-red-500 pt-1 hidden"></p>
        </div>

        {{-- Modal Footer --}}
        <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100">
            <button onclick="closeModal()"
                class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 font-medium rounded-xl hover:bg-gray-100 transition">
                Batal
            </button>
            <button id="submit-btn" onclick="submitForm()"
                class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-medium rounded-xl shadow-sm transition">
                Simpan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─── State ───────────────────────────────────────────────────────────────────
let currentModal = null;   // 'create' | 'edit'
let activeRole = 'all';
let datatable1   = null;

// ─── DataTable Init ──────────────────────────────────────────────────────────
$(document).ready(function () {
    // datatable
    let datatable1Id = "datatable1Id"
    datatable1 = $(`#${datatable1Id}`).DataTable({
        processing  : true,
        serverSide  : true,
        scrollX     : false,   // kita handle sendiri via overflow-x CSS
        ajax: {
            url : '/admin/manage/datatable',   // sesuaikan route
            type: 'GET',
            data: function (d) {
                d.role = activeRole;        // kirim filter role aktif
            },
        },
        columns: [
            { data: 'no',         orderable: false, width: '40px' },
            { data: 'username',   orderable: true  },
            { data: 'role',       orderable: false },
            { data: 'status',     orderable: false },
            { data: 'login_last', orderable: true  },
            { data: 'aksi',       orderable: false, className: 'text-center' },
        ],
        language: {
            processing    : '<div class="text-blue-500 text-sm py-2">Memuat data...</div>',
            emptyTable    : '<div class="py-12 text-gray-300 text-sm">Tidak ada data</div>',
            zeroRecords   : '<div class="py-12 text-gray-300 text-sm">Data tidak</div>',
            info          : 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty     : 'Tidak ada data',
            infoFiltered  : '(disaring dari _MAX_ total data)',
            search        : '',
            searchPlaceholder: 'Cari...',
            lengthMenu    : 'Tampilkan _MENU_ data',
            paginate: {
                first   : '«',
                last    : '»',
                next    : '›',
                previous: '‹',
            },
        },
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4"lf>t<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-t border-gray-100"ip>',
        drawCallback: function () {
            // row hover stripe sudah via CSS
        },
    });

    // ─── Style search input ───────────────────────────────────────────────
    $(`#${datatable1Id}_filter input`).addClass(
        'border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition ml-2'
    );
    $(`#${datatable1Id}_length select`).addClass(
        'border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition mx-1'
    );


    // filte rrole
    $('#role-filter').on('change', function () {
        activeRole = $(this).val();
        datatable1.ajax.reload();
    });


    // ─── Backdrop click close ─────────────────────────────────────────────
    $('#modal-backdrop').on('click', function (e) {
        if (e.target === this) closeModal();
    });

});

// ─── Modal helpers ────────────────────────────────────────────────────────────
function openCreate() {
    currentModal = 'create';
    resetForm();
    document.getElementById('modal-title').textContent       = 'Tambah Akun Baru';
    document.getElementById('submit-btn').textContent        = 'Simpan';
    document.getElementById('confirm-password-wrap').classList.remove('hidden');
    document.getElementById('pw-hint').classList.add('hidden');
    showModal();
}

function openEdit(id) {
    // Ambil data mentah dari row DataTable
    const row = datatable1.rows().data().toArray().find(r => r._raw && r._raw.id == id);
    if (!row) return;

    currentModal = 'edit';
    resetForm();
    document.getElementById('modal-title').textContent       = 'Edit Akun';
    document.getElementById('submit-btn').textContent        = 'Update';
    document.getElementById('confirm-password-wrap').classList.add('hidden');
    document.getElementById('pw-hint').classList.remove('hidden');

    document.getElementById('form-id').value       = row._raw.id;
    document.getElementById('form-username').value = row._raw.username;
    document.getElementById('form-role').value     = row._raw.role;
    document.getElementById('form-status').value   = row._raw.status;

    showModal();
}

function showModal() {
    document.getElementById('modal-backdrop').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-backdrop').classList.add('hidden');
    currentModal = null;
}

function resetForm() {
    document.getElementById('form-id').value                      = '';
    document.getElementById('form-username').value                = '';
    document.getElementById('form-role').value                    = '';
    document.getElementById('form-status').value                  = '';
    document.getElementById('form-password').value                = '';
    document.getElementById('form-password-confirmation').value   = '';
    const err = document.getElementById('error-msg');
    err.textContent = '';
    err.classList.add('hidden');
}

function showError(msg) {
    const err = document.getElementById('error-msg');
    err.textContent = msg;
    err.classList.remove('hidden');
}

// ─── Submit form (create / edit) ─────────────────────────────────────────────
async function submitForm() {
    const isEdit  = currentModal === 'edit';
    const url     = isEdit ? '/api/auth/update' : '/api/auth/register';
    const token   = document.querySelector('meta[name="csrf-token"]').content;

    const payload = new URLSearchParams({
        username              : document.getElementById('form-username').value,
        role                  : document.getElementById('form-role').value,
        status                : document.getElementById('form-status').value,
        password              : document.getElementById('form-password').value,
        password_confirmation : document.getElementById('form-password-confirmation').value,
        _token                : token,
        ...(isEdit ? { id: document.getElementById('form-id').value } : {}),
    });

    Swal.fire({
        title           : 'Menyimpan...',
        text            : 'Mohon tunggu',
        allowOutsideClick: false,
        allowEscapeKey  : false,
        didOpen         : () => Swal.showLoading(),
    });

    try {
        const res  = await fetch(url, {
            method : 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body   : payload,
        });
        const data = await res.json();

        Swal.close();

        if (data.STATUS !== 'SUCCESS') {
            await Swal.fire({ icon: 'error', title: 'Gagal', text: data.MESSAGE });
            return;
        }

        closeModal();
        await Swal.fire({ icon: 'success', title: 'Berhasil', text: data.MESSAGE, timer: 1500, showConfirmButton: false });
        datatable1.ajax.reload(null, false);   // reload tanpa reset halaman

    } catch (err) {
        Swal.close();
        showError(err.message);
        Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan' });
    }
}

// ─── Delete ───────────────────────────────────────────────────────────────────
async function deleteUser(id) {
    const confirm = await Swal.fire({
        title           : 'Hapus Akun?',
        text            : 'Data tidak bisa dikembalikan',
        icon            : 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText : 'Batal',
    });

    if (!confirm.isConfirmed) return;

    const token = document.querySelector('meta[name="csrf-token"]').content;

    Swal.fire({
        title           : 'Menghapus...',
        text            : 'Mohon tunggu',
        allowOutsideClick: false,
        allowEscapeKey  : false,
        didOpen         : () => Swal.showLoading(),
    });

    try {
        const res  = await fetch('/api/auth/delete', {
            method : 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body   : new URLSearchParams({ _token: token, id }),
        });
        const data = await res.json();

        Swal.close();

        if (data.STATUS !== 'SUCCESS') {
            await Swal.fire({ icon: 'error', title: 'Gagal', text: data.MESSAGE });
            return;
        }

        await Swal.fire({ icon: 'success', title: 'Terhapus', text: data.MESSAGE, timer: 1500, showConfirmButton: false });
        datatable1.ajax.reload(null, false);

    } catch (err) {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan' });
    }
}
</script>

@endpush