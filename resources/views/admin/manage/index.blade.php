@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

<div
    x-data="xData()"
    x-init="init()"
    class="max-w-6xl mx-auto px-6 py-10"
>

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Akun</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola akun pengguna</p>
        </div>
        <button
            @click="openCreate()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Akun
        </button>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
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
            <tbody>
                <template x-if="users.length === 0">
                    <tr>
                        <td colspan="8" class="text-center py-16 text-gray-300 text-sm">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5.196-3.796M9 20H4v-2a4 4 0 015.196-3.796M15 7a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Tidak ada data pengguna
                        </td>
                    </tr>
                </template>

                <template x-for="(u, i) in users" :key="u.U_ID">
                    <tr class="border-t border-gray-50 hover:bg-gray-50/60 transition-colors">
                        <td class="px-6 py-4 text-gray-300 font-mono text-xs" x-text="i + 1"></td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase" x-text="u.U_USERNAME.charAt(0)"></div>
                                <span class="font-medium text-gray-800" x-text="u.U_USERNAME"></span>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                :class="roleClass(u.U_ROLE)"
                                x-text="roleLabel(u.U_ROLE)">
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium"
                                :class="u.U_ACCOUNT_STATUS === 'ACCOUNT_STATUS_ACTIVE' ? 'text-emerald-600' : 'text-gray-400'">
                                <span class="w-1.5 h-1.5 rounded-full"
                                    :class="u.U_ACCOUNT_STATUS === 'ACCOUNT_STATUS_ACTIVE' ? 'bg-emerald-500' : 'bg-gray-300'">
                                </span>
                                <span x-text="statusLabel(u.U_ACCOUNT_STATUS)"></span>
                            </span>
                        </td>

                        <td class="px-4 py-4 text-gray-400 text-xs" x-text="u.U_LOGIN_LAST ?? 'Belum pernah'"></td>

                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit(u)"
                                    class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                                </button>
                                <button @click="deleteUser(u.U_ID)"
                                    class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL BACKDROP ===== --}}
    <div
        x-cloak
        x-show="modal !== null"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50"
        @click.self="modal = null"
    >
        {{-- ===== MODAL PANEL ===== --}}
        <div
            x-cloak
            x-show="modal !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden 
            shadow-modal ring-1 ring-white/20"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 text-base" x-text="modal === 'create' ? 'Tambah User Baru' : 'Edit User'"></h2>
                <button @click="modal = null" class="text-gray-300 hover:text-gray-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-6 space-y-3">

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email/No. Telp</label>
                    <input x-model="form.username" type="text" placeholder="johndoe"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                        <select x-model="form.role"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-white">
                            <option value="">Pilih Role</option>
                            <option value="ROLE_ADMIN_BERITA">Admin Berita</option>
                            <option value="ROLE_ADMIN_BERKAS">Admin Verifikasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select x-model="form.status"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-white">
                            <option value="">Pilih Status</option>
                            <option value="ACCOUNT_STATUS_ACTIVE">Aktif</option>
                            <option value="ACCOUNT_STATUS_INACTIVE">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">
                        Password <span x-show="modal === 'edit'" class="text-gray-300 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input x-model="form.password" type="password" placeholder="••••••••"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                </div>

                <div x-show="modal === 'create'">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Konfirmasi Password</label>
                    <input x-model="form.password_confirmation" type="password" placeholder="••••••••"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                </div>

                <p x-show="errorMsg" x-text="errorMsg" class="text-xs text-red-500 pt-1"></p>
            </div>

            {{-- Modal Footer --}}
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100">
                <button @click="modal = null"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 font-medium rounded-xl hover:bg-gray-100 transition">
                    Batal
                </button>
                <button @click="submitForm()"
                    :disabled="loading"
                    class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-medium rounded-xl shadow-sm transition">
                    <span x-show="!loading" x-text="modal === 'create' ? 'Simpan' : 'Update'"></span>
                    <span x-show="loading">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function xData() {
    return {
        users: @json($admin),
        modal: null,
        loading: false,
        errorMsg: '',
        form: {},

        init() {
            
        },

        roleLabel(role) {
            const map = {
                'ROLE_SISWA': 'Siswa',
                'ROLE_ADMIN_BERITA': 'Admin Berita',
                'ROLE_ADMIN_BERKAS': 'Admin Verifikasi',
            };
            return map[role] ?? role ?? '-';
        },

        roleClass(role) {
            const map = {
                'ROLE_SISWA': 'bg-blue-100 text-blue-700',
                'ROLE_ADMIN_BERITA': 'bg-violet-100 text-violet-700',
                'ROLE_ADMIN_BERKAS': 'bg-sky-100 text-sky-700',
            };
            return map[role] ?? 'bg-gray-100 text-gray-500';
        },

        statusLabel(status) {
            return status === 'ACCOUNT_STATUS_ACTIVE' ? 'Aktif' : 'Non-aktif';
        },

        resetForm() {
            this.form = {
                username: '',
                role: '',
                status: '',
                password: '',
                password_confirmation: '',
                _id: null,
            };
            this.errorMsg = '';
        },

        openCreate() {
            this.resetForm();
            this.modal = 'create';
        },

        openEdit(u) {
            this.resetForm();
            this.form = {
                _id: u.U_ID,
                username: u.U_USERNAME,
                role: u.U_ROLE,
                status: u.U_ACCOUNT_STATUS,
                password: '',
            };
            this.modal = 'edit';
        },

        
        async submitForm() {
            this.loading = true;
            this.errorMsg = '';

            const isEdit = this.modal === 'edit';
            const url    = isEdit ? `/api/auth/update` : '/api/auth/register';
            const token  = document.querySelector('meta[name="csrf-token"]').content;

            const payload = new URLSearchParams({
                ...this.form,
                _token: token,
                ...(isEdit ? { id: this.form._id } : {}),
            });

            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: payload,
                });
                const data = await res.json();
                
                Swal.close();
                if (data.STATUS !== 'SUCCESS') {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.MESSAGE
                    }); 
                    return
                }

                // SUCCESS
                this.modal = null;

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.MESSAGE,
                    timer: 1500,
                    showConfirmButton: false
                });

                location.reload();

            } catch (err) {
                Swal.close();
                this.errorMsg = err.message;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan'
                });

            } finally {
                this.loading = false;
            }
        },


        async deleteUser(id) {
            const confirm = await Swal.fire({
                title: 'Hapus User?',
                text: 'Data tidak bisa dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            });

            if (!confirm.isConfirmed) return;

            const token = document.querySelector('meta[name="csrf-token"]').content;

            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const res = await fetch(`/api/auth/delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        _token: token,
                        id: id
                    }),
                });
                const data = await res.json();

                Swal.close();
                if (data.STATUS !== 'SUCCESS') {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.MESSAGE
                    }); 
                    return
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Terhapus',
                    text: data.MESSAGE,
                    timer: 1500,
                    showConfirmButton: false
                });

                location.reload();

            } catch (err) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan'
                });
            }
        },
    }
}
</script>
@endpush