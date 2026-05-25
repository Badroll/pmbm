@extends('layouts.app')

@section('content')

{{-- Contoh data: ubah $status menjadi 'diterima', 'tidak_diterima', atau 'cadangan' --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap');

    .peng-wrap {
        font-family: 'DM Sans', sans-serif;
        min-height: calc(100vh - 4rem);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: #f5f4f0;
        position: relative;
        overflow: hidden;
    }

    .peng-wrap::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, #c8c6be 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: 0.45;
        pointer-events: none;
    }

    .card {
        position: relative;
        background: #ffffff;
        border-radius: 24px;
        padding: 3rem 2.5rem;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 2px 0 #d4d2ca, 0 8px 40px rgba(0,0,0,0.06);
        animation: cardIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .stamp {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: stampIn 0.5s 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    @keyframes stampIn {
        from { opacity: 0; transform: scale(0.4) rotate(-15deg); }
        to   { opacity: 1; transform: scale(1) rotate(0deg); }
    }

    .stamp-diterima  { background: #eaf3de; }
    .stamp-diterima svg polyline { stroke: #3B6D11; }
    .stamp-tidak     { background: #fcebeb; }
    .stamp-tidak svg line { stroke: #A32D2D; }
    .stamp-cadangan  { background: #FAEEDA; }
    .stamp-cadangan svg path,
    .stamp-cadangan svg circle { stroke: #854F0B; }

    .label-status {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        text-align: center;
        line-height: 1.2;
        animation: fadeUp 0.4s 0.35s ease both;
    }
    .label-diterima  { color: #3B6D11; }
    .label-tidak     { color: #A32D2D; }
    .label-cadangan  { color: #854F0B; }

    .sub-status {
        text-align: center;
        font-size: 0.8rem;
        color: #b4b2a9;
        margin-top: 0.4rem;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        font-weight: 500;
        animation: fadeUp 0.4s 0.42s ease both;
    }

    .divider {
        border: none;
        border-top: 1px solid #e8e7e2;
        margin: 1.75rem 0;
        animation: fadeUp 0.4s 0.45s ease both;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 1rem;
        animation: fadeUp 0.4s 0.5s ease both;
    }
    .info-label { font-size: 0.78rem; color: #b4b2a9; text-transform: uppercase; letter-spacing: 0.07em; }
    .info-value { font-size: 0.95rem; color: #2C2C2A; font-weight: 500; text-align: right; }

    .note-box {
        margin-top: 1.5rem;
        background: #fef9f2;
        border-left: 3px solid #EF9F27;
        border-radius: 0 8px 8px 0;
        padding: 0.9rem 1rem;
        animation: fadeUp 0.4s 0.55s ease both;
    }
    .note-title { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.07em; color: #BA7517; font-weight: 500; margin-bottom: 0.35rem; }
    .note-text  { font-size: 0.875rem; color: #444441; line-height: 1.65; margin: 0; }

    .cadangan-box {
        margin-top: 1.5rem;
        background: #f5f4f0;
        border-radius: 10px;
        padding: 1rem 1.1rem;
        text-align: center;
        animation: fadeUp 0.4s 0.55s ease both;
    }
    .cadangan-box p { font-size: 0.85rem; color: #5F5E5A; line-height: 1.65; margin: 0; }

    .confetti-dot {
        position: absolute;
        border-radius: 50%;
        animation: floatDot linear infinite;
        pointer-events: none;
    }
    @keyframes floatDot {
        0%   { transform: translateY(0) rotate(0deg); opacity: 0.7; }
        100% { transform: translateY(-340px) rotate(360deg); opacity: 0; }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="peng-wrap" id="pengumuman-wrap">

    @if($status === 'diterima')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrap = document.getElementById('pengumuman-wrap');
        const colors = ['#9FE1CB','#C0DD97','#97C459','#5DCAA5','#1D9E75','#eaf3de'];
        for (let i = 0; i < 20; i++) {
            const d = document.createElement('div');
            d.className = 'confetti-dot';
            const size = Math.random() * 9 + 4;
            d.style.cssText = `
                width:${size}px;height:${size}px;
                background:${colors[Math.floor(Math.random()*colors.length)]};
                left:${Math.random()*100}%;
                bottom:${Math.random()*15}%;
                animation-duration:${(Math.random()*4+3).toFixed(1)}s;
                animation-delay:${(Math.random()*2).toFixed(1)}s;
            `;
            wrap.appendChild(d);
        }
    });
    </script>
    @endif

    <div class="card">

        {{-- Stamp icon --}}
        <div class="stamp stamp-{{ $status === 'tidak_diterima' ? 'tidak' : $status }}">
            @if($status === 'diterima')
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            @elseif($status === 'tidak_diterima')
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            @else
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            @endif
        </div>

        {{-- Judul status --}}
        <div class="label-status label-{{ $status === 'tidak_diterima' ? 'tidak' : $status }}">
            @if($status === 'diterima')         Selamat, Anda Diterima
            @elseif($status === 'tidak_diterima') Belum Diterima
            @else                                Daftar Cadangan
            @endif
        </div>

        <div class="sub-status">Pengumuman Kelulusan Seleksi</div>

        <hr class="divider">

        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ $nama }}</span>
        </div>
        <div class="info-row" style="margin-top:0.75rem;">
            <span class="info-label">No. Peserta</span>
            <span class="info-value">{{ $nomor }}</span>
        </div>

        @if($status === 'tidak_diterima')
        <div class="note-box">
            <div class="note-title">Catatan</div>
            <p class="note-text">{{ $catatan }}</p>
        </div>
        @endif

        @if($status === 'cadangan')
        <div class="cadangan-box">
            <p>Anda masuk dalam daftar cadangan. Keputusan akhir akan diinformasikan lebih lanjut sesuai ketersediaan kuota.</p>
        </div>
        @endif

    </div>
</div>

@endsection