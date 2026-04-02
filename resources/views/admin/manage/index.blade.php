@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500">Kelola akun pengguna sistem</p>
        </div>
        <button id="btn-open-create" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Tambah User
        </button>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari username/email/telp..."
            class="border px-3 py-2 rounded-lg text-sm">

        <select name="role" class="border px-3 py-2 rounded-lg text-sm">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
            <option value="operator" {{ request('role')=='operator'?'selected':'' }}>Operator</option>
            <option value="user" {{ request('role')=='user'?'selected':'' }}>User</option>
        </select>

        <select name="status" class="border px-3 py-2 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            <option value="banned" {{ request('status')=='banned'?'selected':'' }}>Banned</option>
        </select>

        <select name="per_page" class="border px-3 py-2 rounded-lg text-sm">
            <option value="10">10</option>
            <option value="25" {{ request('per_page')==25?'selected':'' }}>25</option>
            <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
        </select>

        <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">
            Filter
        </button>
    </form>

    {{-- TABLE --}}
    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th>Username</th>
                    <th>Email/Telp</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($admin as $i => $u)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td>{{ $u->U_USERNAME }}</td>
                    <td>{{ $u->U_EMAIL }}</td>
                    <td>{{ $u->refRole->R_INFO }}</td>
                    <td>{{ $u->refAccountStatus->R_INFO }}</td>
                    <td>{{ $u->U_LOGIN_LAST ?? 'Belum pernah' }}</td>
                    <td class="space-x-2">
                        <button class="btn-edit text-blue-600" data-id="{{ $u->U_ID }}">Edit</button>
                        <button class="btn-delete text-red-600" data-id="{{ $u->U_ID }}">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

{{-- ================= MODAL CREATE ================= --}}
<div id="modal-create" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="font-bold mb-4">Tambah User</h2>

        <form id="form-create">
            @csrf

            <input name="U_USERNAME" placeholder="Username" class="w-full border p-2 mb-2 rounded">
            <input name="U_EMAIL" placeholder="Email" class="w-full border p-2 mb-2 rounded">
            <input name="U_PHONE" placeholder="Telp" class="w-full border p-2 mb-2 rounded">

            <select name="U_ROLE" class="w-full border p-2 mb-2 rounded">
                <option value="">Role</option>
                <option value="admin">Admin</option>
                <option value="operator">Operator</option>
                <option value="user">User</option>
            </select>

            <select name="U_ACCOUNT_STATUS" class="w-full border p-2 mb-2 rounded">
                <option value="">Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="banned">Banned</option>
            </select>

            <input type="password" name="U_PASSWORD" placeholder="Password" class="w-full border p-2 mb-2 rounded">
            <input type="password" name="U_PASSWORD_CONFIRM" placeholder="Konfirmasi" class="w-full border p-2 mb-2 rounded">

            <div class="flex justify-end gap-2">
                <button type="button" class="btn-close">Batal</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modal-edit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="font-bold mb-4">Edit User</h2>

        <form id="form-edit">
            @csrf
            <input type="hidden" id="edit_id">
            <input type="hidden" name="_method" value="PUT">

            <input id="edit_username" name="U_USERNAME" class="w-full border p-2 mb-2 rounded">
            <input id="edit_email" name="U_EMAIL" class="w-full border p-2 mb-2 rounded">
            <input id="edit_phone" name="U_PHONE" class="w-full border p-2 mb-2 rounded">

            <select id="edit_role" name="U_ROLE" class="w-full border p-2 mb-2 rounded">
                <option value="admin">Admin</option>
                <option value="operator">Operator</option>
                <option value="user">User</option>
            </select>

            <select id="edit_status" name="U_ACCOUNT_STATUS" class="w-full border p-2 mb-2 rounded">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="banned">Banned</option>
            </select>

            <input type="password" name="U_PASSWORD" placeholder="Password baru" class="w-full border p-2 mb-2 rounded">

            <div class="flex justify-end gap-2">
                <button type="button" class="btn-close">Batal</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){

    function reload(){ location.reload(); }

    $('#btn-open-create').click(function(){
        $('#modal-create').removeClass('hidden');
    });

    $('.btn-close').click(function(){
        $('#modal-create, #modal-edit').addClass('hidden');
    });

    // CREATE
    $('#form-create').submit(function(e){
        e.preventDefault();

        $.post('/admin/users', $(this).serialize())
        .done(reload)
        .fail(()=>alert('Gagal'));
    });

    // OPEN EDIT
    $('.btn-edit').click(function(){
        let id = $(this).data('id');

        $.get('/admin/users/'+id, function(res){
            let u = res.data;
            $('#edit_id').val(u.U_ID);
            $('#edit_username').val(u.U_USERNAME);
            $('#edit_email').val(u.U_EMAIL);
            $('#edit_phone').val(u.U_PHONE);
            $('#edit_role').val(u.U_ROLE);
            $('#edit_status').val(u.U_ACCOUNT_STATUS);

            $('#modal-edit').removeClass('hidden');
        });
    });

    // EDIT
    $('#form-edit').submit(function(e){
        e.preventDefault();

        let id = $('#edit_id').val();

        $.post('/admin/users/'+id, $(this).serialize())
        .done(reload)
        .fail(()=>alert('Gagal'));
    });

    // DELETE
    $('.btn-delete').click(function(){
        if(!confirm('Hapus user?')) return;

        let id = $(this).data('id');

        $.post('/admin/users/'+id, {
            _method:'DELETE',
            _token:$('meta[name="csrf-token"]').attr('content')
        }).done(reload);
    });

});
</script>
@endpush