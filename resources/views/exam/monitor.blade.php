@extends('layouts.app')

@section('title', 'Monitoring Token CBT')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Monitoring Token CBT</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Monitoring token ujian CBT secara realtime
            </p>
        </div>

        <button
            onclick="refreshTokenManual()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow transition"
        >
            <svg 
                class="w-4 h-4" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M21 12a9 9 0 11-2.64-6.36"
                />
                
                <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M21 3v6h-6"
                />
            </svg>
            Refresh Token
        </button>
    </div>

    {{-- ===== TOKEN CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-8">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">
                        Token Aktif Saat Ini
                    </p>

                    <p class="text-xs text-gray-400 mt-1 italic">
                        Halaman refresh otomatis setiap 10 detik
                    </p>
                </div>

                <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
            </div>

            <div
                id="token-box"
                class="bg-blue-600 rounded-2xl px-6 py-8 text-center shadow"
            >
                <div
                    id="token-text"
                    class="text-5xl sm:text-6xl font-black tracking-[12px] text-white"
                >
                    {{ isset($token) ? implode(' ', str_split($token)) : '- - - - - -' }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>

const tokenEl = document.getElementById('token-text');

// ─── Refresh Manual ───────────────────────────────────────────────────────────
async function refreshTokenManual() {

    const token = document.querySelector('meta[name="csrf-token"]').content;

    Swal.fire({
        title            : 'Memperbarui Token...',
        text             : 'Mohon tunggu',
        allowOutsideClick: false,
        allowEscapeKey   : false,
        didOpen          : () => Swal.showLoading(),
    });

    try {

        const res = await fetch('/exam/refresh-token', {
            method : 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                _token: token
            }),
        });

        const data = await res.json();

        Swal.close();

        if (data.STATUS !== 'SUCCESS') {
            Swal.fire({
                icon : 'error',
                title: 'Gagal',
                text : data.MESSAGE || 'Gagal refresh token',
            });
            return;
        }

        tokenEl.innerText = data.PAYLOAD.split('').join(' ');

        Swal.fire({
            icon             : 'success',
            title            : 'Berhasil',
            text             : 'Token berhasil diperbarui',
            timer            : 1500,
            showConfirmButton: false,
        });

    } catch (err) {

        Swal.close();

        Swal.fire({
            icon : 'error',
            title: 'Gagal',
            text : err.message || 'Terjadi kesalahan',
        });
    }
}

// ─── Auto Reload Every 10 Seconds ────────────────────────────────────────────
setInterval(() => {
    window.location.reload();
}, 10000);

</script>
@endpush