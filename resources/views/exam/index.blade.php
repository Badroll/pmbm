<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tes CBT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #1a2744;
            --navy-light: #243356;
            --gold: #c8962e;
            --gold-light: #e8b84b;
            --cream: #f7f4ee;
            --cream-dark: #ede9e0;
            --text-dark: #1a1a2e;
            --text-muted: #6b7280;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            /* font-family: 'DM Sans', sans-serif; */
            background-color: var(--cream);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* h1, h2, h3, .serif { font-family: 'DM Serif Display', serif; } */

        /* ── TOKEN SCREEN ── */
        #screen-token {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--cream);
            position: relative;
            overflow: hidden;
        }

        /* #screen-token::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 340px; height: 340px;
            border-radius: 50%;
            background: rgba(26, 39, 68, 0.06);
            pointer-events: none;
        }

        #screen-token::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: rgba(200, 150, 46, 0.07);
            pointer-events: none;
        } */

        .token-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 8px 48px rgba(26, 39, 68, 0.10);
            position: relative;
            z-index: 1;
        }

        .token-card .logo-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .logo-emblem {
            width: 40px; height: 40px;
            background: var(--navy);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-emblem svg { width: 22px; height: 22px; fill: var(--gold-light); }

        .token-card h2 {
            font-size: 1.75rem;
            color: var(--navy);
            margin-bottom: 0.4rem;
        }

        .token-card p.sub {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            /* font-family: 'DM Sans', sans-serif; */
            font-size: 1rem;
            color: var(--text-dark);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            background: #fafafa;
            letter-spacing: 0.1em;
            text-align: center;
            font-weight: 500;
        }

        .input-group input:focus {
            border-color: var(--navy);
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 39, 68, 0.08);
        }

        .btn-primary {
            width: 100%;
            padding: 0.85rem 1.5rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            /* font-family: 'DM Sans', sans-serif; */
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            letter-spacing: 0.02em;
        }

        .btn-primary:hover { background: var(--navy-light); box-shadow: 0 4px 16px rgba(26,39,68,0.18); }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-size: 0.85rem;
            margin-top: 1rem;
            display: none;
        }

        /* ── EXAM SCREEN ── */
        #screen-exam { display: none; min-height: 100vh; }

        /* Header Bar */
        .exam-header {
            background: #2563eb;
            color: white;
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 16px rgba(0,0,0,0.18);
        }

        .exam-header .title-group { display: flex; align-items: center; gap: 10px; }
        .exam-header .title-group span.badge {
            background: var(--gold);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .exam-header h3 {
            /* font-family: 'DM Serif Display', serif; */
            font-size: 1.1rem;
            color: white;
        }

        /* Timer */
        .timer-wrap {
            display: flex; align-items: center; gap: 8px;
        }

        .timer-icon { opacity: 0.7; }

        #timer-display {
            /* font-family: 'DM Mono', monospace; */
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: #e2e8f0;
            transition: color 0.3s;
            min-width: 72px;
            text-align: right;
        }

        #timer-display.warning { color: #fbbf24; }
        #timer-display.danger { color: #f87171; animation: pulse-danger 1s infinite; }

        @keyframes pulse-danger {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Progress Bar */
        .progress-bar-wrap {
            background: var(--cream-dark);
            height: 4px;
            position: sticky;
            top: 60px;
            z-index: 49;
        }

        #progress-bar {
            height: 100%;
            background: var(--gold);
            width: 0%;
            transition: width 0.5s ease;
        }

        /* Main Layout */
        .exam-body {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: calc(100vh - 64px);
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Sidebar Navigation */
        .soal-nav {
            background: white;
            border-right: 1px solid var(--cream-dark);
            padding: 1.5rem 1rem;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
        }

        .soal-nav h4 {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
            padding: 0 0.25rem;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
        }

        .nav-btn {
            aspect-ratio: 1;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            color: var(--text-dark);
            display: flex; align-items: center; justify-content: center;
        }

        .nav-btn:hover { border-color: var(--navy); color: var(--navy); }
        .nav-btn.answered { background: var(--navy); border-color: var(--navy); color: white; }
        .nav-btn.active { border-color: var(--gold); box-shadow: 0 0 0 2px rgba(200,150,46,0.3); }

        .nav-legend {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--cream-dark);
            display: flex; flex-direction: column; gap: 6px;
        }

        .legend-item {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.75rem; color: var(--text-muted);
        }

        .legend-dot {
            width: 14px; height: 14px; border-radius: 4px; flex-shrink: 0;
        }

        .legend-dot.answered-dot { background: var(--navy); }
        .legend-dot.empty-dot { background: white; border: 1.5px solid #e5e7eb; }

        /* Nav Footer */
        .nav-footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--cream-dark);
        }

        .btn-finish {
            width: 100%;
            padding: 0.65rem;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            /* font-family: 'DM Sans', sans-serif; */
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-finish:hover { background: #b91c1c; }

        /* Soal Content */
        .soal-content {
            padding: 2rem 2.5rem;
            overflow-y: auto;
        }

        /* Soal Card */
        .soal-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            margin-bottom: 1.25rem;
        }

        .soal-header {
            display: flex; align-items: flex-start; gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .soal-number {
            background: var(--navy);
            color: white;
            border-radius: 10px;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .soal-text {
            font-size: 0.975rem;
            line-height: 1.75;
            color: var(--text-dark);
            padding-top: 8px;
            flex: 1;
        }

        /* Option Buttons */
        .options-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .option-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 0.85rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.18s;
            user-select: none;
        }

        .option-label:hover {
            border-color: var(--navy);
            background: rgba(26, 39, 68, 0.03);
        }

        .option-label input[type="radio"] { display: none; }

        .option-label.selected {
            border-color: var(--navy);
            background: rgba(26, 39, 68, 0.05);
        }

        .option-label.selected .opt-badge {
            background: var(--navy);
            color: white;
        }

        .opt-badge {
            width: 28px; height: 28px;
            border-radius: 7px;
            border: 1.5px solid #d1d5db;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            color: var(--text-muted);
            transition: all 0.18s;
        }

        .opt-text {
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--text-dark);
            padding-top: 3px;
        }

        /* Saving indicator */
        .save-indicator {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex; align-items: center; gap: 5px;
            margin-top: 0.75rem;
            min-height: 18px;
            transition: opacity 0.3s;
        }

        .save-indicator.saving { color: var(--gold); }
        .save-indicator.saved { color: #16a34a; }
        .save-indicator.error-save { color: #dc2626; }

        /* Nav Buttons */
        .soal-nav-btns {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 0.5rem;
        }

        .btn-nav {
            padding: 0.6rem 1.25rem;
            border-radius: 9px;
            border: 1.5px solid #e5e7eb;
            background: white;
            /* font-family: 'DM Sans', sans-serif; */
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            color: var(--navy);
        }

        .btn-nav:hover:not(:disabled) { border-color: var(--navy); background: var(--navy); color: white; }
        .btn-nav:disabled { opacity: 0.4; cursor: not-allowed; }

        .soal-counter {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ── DONE SCREEN ── */
        #screen-done {
            display: none;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--cream);
        }

        .done-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 48px rgba(26,39,68,0.10);
        }

        .done-emblem {
            width: 72px; height: 72px;
            background: var(--navy);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .done-emblem svg { width: 36px; height: 36px; }

        .done-card h2 { font-size: 1.75rem; color: var(--navy); margin-bottom: 0.5rem; }
        .done-card p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; }

        .score-big {
            /* font-family: 'DM Serif Display', serif; */
            font-size: 4.5rem;
            color: var(--navy);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .score-label { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.75rem; }

        .score-detail {
            background: var(--cream);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-dark);
            display: flex; justify-content: space-around;
        }

        .score-detail .item { text-align: center; }
        .score-detail .item .val { font-size: 1.5rem; font-weight: 700; color: var(--navy); }
        .score-detail .item .lbl { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; text-transform: uppercase; letter-spacing: 0.06em; }

        /* Overlay loading */
        #loading-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(26,39,68,0.4);
            z-index: 999;
            align-items: center; justify-content: center;
        }

        .spinner {
            width: 48px; height: 48px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Responsive */
        @media (max-width: 768px) {
            .exam-body { grid-template-columns: 1fr; }
            .soal-nav { position: static; height: auto; border-right: none; border-bottom: 1px solid var(--cream-dark); }
            .soal-content { padding: 1.25rem; }
            .soal-card { padding: 1.25rem; }
        }

        /* Confirm modal */
        .modal-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 100;
            align-items: center; justify-content: center;
        }

        .modal-box {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 360px;
            width: 90%;
            text-align: center;
        }

        .modal-box h3 { font-size: 1.2rem; color: var(--navy); margin-bottom: 0.5rem; }
        .modal-box p { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.6; }
        .modal-actions { display: flex; gap: 10px; }
        .btn-cancel { flex: 1; padding: 0.65rem; border: 1.5px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.875rem; color: var(--text-dark); transition: 0.15s; }
        .btn-cancel:hover { border-color: #9ca3af; }
        .btn-confirm { flex: 1; padding: 0.65rem; border: none; border-radius: 8px; background: #dc2626; color: white; cursor: pointer; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.875rem; transition: 0.15s; }
        .btn-confirm:hover { background: #b91c1c; }
    </style>
</head>
<body>

{{-- ── LOADING OVERLAY ── --}}
<div id="loading-overlay" style="display:flex;">
    <div class="spinner"></div>
</div>

{{-- ── SCREEN 1: TOKEN ── --}}
<div id="screen-token">
    <div class="token-card">

        <h2>Masukkan Token</h2>
        <p class="sub">Masukkan token ujian yang telah diberikan oleh pengawas untuk memulai sesi ujian Anda.</p>

        <div class="input-group">
            <label>Token Ujian</label>
            <input type="text" id="input-token" placeholder="" autocomplete="off" autofocus value="DEF456">
        </div>

        <button class="btn-primary" id="btn-verify" onclick="verifyToken()">
            Mulai Ujian →
        </button>

        <div class="error-msg" id="token-error"></div>
    </div>
</div>

{{-- ── SCREEN 2: EXAM ── --}}
<div id="screen-exam">
    {{-- Header --}}
    <div class="exam-header">
        <div class="title-group">
            <h3 id="header-jenis" >Lembar Ujian</h3>
        </div>

        <div class="timer-wrap">
            <svg class="timer-icon" width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <div id="timer-display">90:00</div>
        </div>
    </div>

    {{-- Progress --}}
    <div class="progress-bar-wrap">
        <div id="progress-bar"></div>
    </div>

    <div class="exam-body">
        {{-- Sidebar --}}
        <aside class="soal-nav">
            <h4>Navigasi Soal</h4>
            <div class="nav-grid" id="nav-grid"></div>

            <!-- <div class="nav-legend">
                <div class="legend-item">
                    <div class="legend-dot answered-dot"></div>
                    <span>Sudah dijawab</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot empty-dot"></div>
                    <span>Belum dijawab</span>
                </div>
            </div> -->

            <div class="nav-footer">
                <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:8px;text-align:center;">
                    <span id="answered-count">0</span> dari <span id="total-count">0</span> soal dijawab
                </div>
                <button class="btn-finish" onclick="showFinishModal()">Selesai &amp; Kumpulkan</button>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="soal-content">
            <div id="soal-container"></div>

            <div class="soal-nav-btns">
                <button class="btn-nav" id="btn-prev" onclick="navigate(-1)">← Sebelumnya</button>
                <span class="soal-counter" id="soal-counter">Soal 1 dari 0</span>
                <button class="btn-nav" id="btn-next" onclick="navigate(1)">Selanjutnya →</button>
            </div>
        </main>
    </div>
</div>

{{-- ── SCREEN 3: DONE ── --}}
<div id="screen-done" style="display:flex;">
    <div class="done-card">
        <div class="done-emblem">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <h2>Ujian Selesai</h2>
        <p>Jawaban Anda telah berhasil dikumpulkan. Berikut hasil ujian Anda:</p>
        <div class="score-big" id="score-nilai">—</div>
        <div class="score-label">Nilai Akhir</div>
        <div class="score-detail">
            <div class="item">
                <div class="val" id="score-benar">—</div>
                <div class="lbl">Benar</div>
            </div>
            <div class="item">
                <div class="val" id="score-salah">—</div>
                <div class="lbl">Salah</div>
            </div>
            <div class="item">
                <div class="val" id="score-total">—</div>
                <div class="lbl">Total</div>
            </div>
        </div>
        <p style="font-size:0.8rem;margin-bottom:0;">Silakan serahkan kartu ujian kepada pengawas.</p>
    </div>
</div>

{{-- ── CONFIRM MODAL ── --}}
<div class="modal-backdrop" id="finish-modal">
    <div class="modal-box">
        <h3>Kumpulkan Jawaban?</h3>
        <p>Pastikan semua soal sudah Anda isi. Setelah dikumpulkan, jawaban tidak dapat diubah lagi.</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="hideFinishModal()">Periksa Lagi</button>
            <button class="btn-confirm" onclick="finishExam()">Ya, Kumpulkan</button>
        </div>
    </div>
</div>

{{-- ========================= --}}
{{-- UPDATE BAGIAN JAVASCRIPT --}}
{{-- ========================= --}}

<script>

// ─── STATE ───────────────────────────────────────────────────────────────────
let state = {
    pgrjn_id   : null,
    soal       : [],
    jawaban    : {},
    currentIdx : 0,
    timerInt   : null,
    sisaDetik  : 0,
    saving     : {},
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ─────────────────────────────────────────────────────────────────────────────
// VERIFY TOKEN
// ─────────────────────────────────────────────────────────────────────────────
function verifyToken() {

    const token = $('#input-token').val().trim();

    if (!token) {
        showTokenError('Masukkan token terlebih dahulu.');
        return;
    }

    $('#btn-verify')
        .prop('disabled', true)
        .text('Memeriksa...');

    $('#token-error').hide();

    $.post('{{ route("exam.verify-token") }}', {
        token
    })

    .done(function(res) {

        if (res.success) {

            initExam(res);

        } else {

            showTokenError(res.message || 'Token tidak valid.');

            $('#btn-verify')
                .prop('disabled', false)
                .text('Mulai Ujian →');
        }
    })

    .fail(function(xhr) {

        let msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';

        // =====================================================
        // SEMUA TEST SUDAH SELESAI
        // =====================================================

        if (xhr.responseJSON?.finished_all) {

            alert('Semua tes telah selesai.');

            window.location.href = '/';

            return;
        }

        // =====================================================
        // WAITING TEST
        // =====================================================

        if (xhr.responseJSON?.waiting) {

            showTokenError(msg);

        } else {

            showTokenError(msg);
        }

        $('#btn-verify')
            .prop('disabled', false)
            .text('Mulai Ujian →');
    });
}

// ENTER
$('#input-token').on('keypress', function(e) {

    if (e.which === 13) {
        verifyToken();
    }
});

function showTokenError(msg) {

    $('#token-error')
        .text(msg)
        .show();
}

// ─────────────────────────────────────────────────────────────────────────────
// INIT EXAM
// ─────────────────────────────────────────────────────────────────────────────
function initExam(data) {

    state.pgrjn_id  = data.pgrjn_id;
    state.soal      = data.soal;
    state.sisaDetik = data.sisa_detik;
    state.jawaban   = {};
    state.currentIdx = 0;

    // MAP EXISTING JAWABAN
    if (data.jawaban) {

        Object.entries(data.jawaban).forEach(([examId, jwb]) => {

            state.jawaban[examId] = jwb;
        });
    }

    // HEADER
    $('#header-jenis').text('Tes ' + data.jenis);

    $('#total-count').text(state.soal.length);

    // BUILD UI
    buildNavGrid();

    renderSoal(0);

    updateProgress();

    // SHOW EXAM SCREEN
    $('#screen-token').hide();

    $('#screen-exam').show();

    // TIMER
    startTimer(state.sisaDetik);

    $('#loading-overlay').hide();
}

// ─────────────────────────────────────────────────────────────────────────────
// RENDER SOAL
// ─────────────────────────────────────────────────────────────────────────────
function renderSoal(idx) {

    state.currentIdx = idx;

    const s = state.soal[idx];

    const total = state.soal.length;

    const selected = state.jawaban[s.EXAM_ID] || null;

    const opts = [
        { key: 'A', text: s.EXAM_A },
        { key: 'B', text: s.EXAM_B },
        { key: 'C', text: s.EXAM_C },
        { key: 'D', text: s.EXAM_D },
    ].filter(o => o.text);

    const optsHtml = opts.map(o => `
        <label class="option-label ${selected === o.key ? 'selected' : ''}"
               data-val="${o.key}"
               onclick="selectOption('${s.EXAM_ID}', '${o.key}', this)">

            <input type="radio"
                   name="soal_${s.EXAM_ID}"
                   value="${o.key}"
                   ${selected === o.key ? 'checked' : ''}>

            <span class="opt-badge">${o.key}</span>

            <span class="opt-text">${escHtml(o.text)}</span>
        </label>
    `).join('');

    $('#soal-container').html(`
        <div class="soal-card" id="soal-${s.EXAM_ID}">

            <div class="soal-header">

                <div class="soal-number">
                    ${s.EXAM_NO || (idx + 1)}
                </div>

                <div class="soal-text">
                    ${escHtml(s.EXAM_KET)}
                </div>

            </div>

            <div class="options-grid">
                ${optsHtml}
            </div>

            <div class="save-indicator"
                 id="save-ind-${s.EXAM_ID}">
            </div>

        </div>
    `);

    $('#soal-counter').text(`Soal ${idx + 1} dari ${total}`);

    $('#btn-prev').prop('disabled', idx === 0);

    $('#btn-next').prop('disabled', idx === total - 1);

    $('.nav-btn').removeClass('active');

    $(`.nav-btn[data-idx="${idx}"]`).addClass('active');
}

// ─────────────────────────────────────────────────────────────────────────────
// SELECT OPTION
// ─────────────────────────────────────────────────────────────────────────────
function selectOption(examId, val, labelEl) {

    $(`#soal-${examId} .option-label`)
        .removeClass('selected');

    $(labelEl)
        .addClass('selected');

    state.jawaban[examId] = val;

    updateNavBtn(examId);

    updateProgress();

    saveAnswer(examId, val);
}

// ─────────────────────────────────────────────────────────────────────────────
// SAVE ANSWER
// ─────────────────────────────────────────────────────────────────────────────
function saveAnswer(examId, val) {

    const ind = $(`#save-ind-${examId}`);

    ind.attr('class', 'save-indicator saving')
       .html('⟳ Menyimpan...');

    $.post('{{ route("exam.save-answer") }}', {

        pgrjn_id : state.pgrjn_id,
        exam_id  : examId,
        jawaban  : val,

    })

    .done(function() {

        ind.attr('class', 'save-indicator saved')
           .html('✓ Tersimpan');

        setTimeout(() => {

            ind.html('');

        }, 2000);
    })

    .fail(function(xhr) {

        ind.attr('class', 'save-indicator error-save')
           .html('✗ Gagal menyimpan');

        if (xhr.status === 403) {

            clearInterval(state.timerInt);

            doTimeUp();
        }
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// NAVIGATION
// ─────────────────────────────────────────────────────────────────────────────
function navigate(dir) {

    const next = state.currentIdx + dir;

    if (next < 0 || next >= state.soal.length) {
        return;
    }

    renderSoal(next);
}

function buildNavGrid() {

    const html = state.soal.map((s, i) => {

        const cls = state.jawaban[s.EXAM_ID]
            ? 'answered'
            : '';

        return `
            <button class="nav-btn ${cls}"
                    data-idx="${i}"
                    data-id="${s.EXAM_ID}"
                    onclick="renderSoal(${i})">

                ${s.EXAM_NO || i + 1}

            </button>
        `;
    }).join('');

    $('#nav-grid').html(html);
}

function updateNavBtn(examId) {

    const soalIdx = state.soal.findIndex(
        s => s.EXAM_ID == examId
    );

    if (soalIdx < 0) return;

    const btn = $(`.nav-btn[data-idx="${soalIdx}"]`);

    if (state.jawaban[examId]) {

        btn.addClass('answered');

    } else {

        btn.removeClass('answered');
    }

    const answered = Object.keys(state.jawaban).length;

    $('#answered-count').text(answered);
}

function updateProgress() {

    const answered = Object.keys(state.jawaban).length;

    const total = state.soal.length;

    const pct = total > 0
        ? (answered / total * 100).toFixed(1)
        : 0;

    $('#progress-bar').css('width', pct + '%');

    $('#answered-count').text(answered);
}

// ─────────────────────────────────────────────────────────────────────────────
// TIMER
// ─────────────────────────────────────────────────────────────────────────────
function startTimer(detik) {

    state.sisaDetik = detik;

    renderTimer();

    clearInterval(state.timerInt);

    state.timerInt = setInterval(function() {

        state.sisaDetik--;

        renderTimer();

        if (state.sisaDetik <= 0) {

            clearInterval(state.timerInt);

            doTimeUp();
        }

    }, 1000);
}

function renderTimer() {

    const s = state.sisaDetik;

    const m = Math.floor(s / 60);

    const sec = s % 60;

    const str =
        String(m).padStart(2, '0') +
        ':' +
        String(sec).padStart(2, '0');

    const el = $('#timer-display');

    el.text(str);

    el.removeClass('warning danger');

    if (s <= 60) {

        el.addClass('danger');

    } else if (s <= 300) {

        el.addClass('warning');
    }
}

function doTimeUp() {

    alert('Waktu ujian telah habis.');

    finishExam();
}

// ─────────────────────────────────────────────────────────────────────────────
// FINISH
// ─────────────────────────────────────────────────────────────────────────────
function showFinishModal() {

    $('#finish-modal').css('display', 'flex');
}

function hideFinishModal() {

    $('#finish-modal').hide();
}

function finishExam() {

    hideFinishModal();

    $('#loading-overlay').css('display','flex');

    $.post('{{ route("exam.finish") }}', {

        pgrjn_id: state.pgrjn_id

    })

    .done(function(res) {

        clearInterval(state.timerInt);

        $('#loading-overlay').hide();

        // =====================================================
        // ADA TEST BERIKUTNYA
        // =====================================================

        if (res.next_test === 'Psikotest') {

            alert('Tes Akademik selesai. Selanjutnya Psikotest akan dimulai.');

            // RESET STATE
            state = {
                pgrjn_id   : null,
                soal       : [],
                jawaban    : {},
                currentIdx : 0,
                timerInt   : null,
                sisaDetik  : 0,
                saving     : {},
            };

            // RESET UI
            $('#screen-exam').hide();

            $('#screen-token').show();

            // AUTO VERIFY ULANG
            verifyToken();

            return;
        }

        // =====================================================
        // SEMUA TEST SELESAI
        // =====================================================

        alert('Semua tes selesai.');

        window.location.href = '/';
    })

    .fail(function() {

        $('#loading-overlay').hide();

        alert('Gagal mengumpulkan. Coba lagi.');
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// DONE SCREEN
// ─────────────────────────────────────────────────────────────────────────────
function showDone(res) {

    $('#screen-exam').hide();

    $('#score-nilai').text(res.nilai);

    $('#score-benar').text(res.benar);

    $('#score-salah').text(res.total - res.benar);

    $('#score-total').text(res.total);

    $('#screen-done').css('display', 'flex');
}

// ─────────────────────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────────────────────
function escHtml(str) {

    if (!str) return '';

    return str
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

// ─────────────────────────────────────────────────────────────────────────────
// READY
// ─────────────────────────────────────────────────────────────────────────────
$(document).ready(function() {

    $('#loading-overlay').hide();

    $('#screen-done').hide();
});

</script>

</body>
</html>