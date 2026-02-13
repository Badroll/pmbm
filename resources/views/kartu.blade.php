@extends('layouts.app')
@section('title', 'Dokumen & Kartu Pendaftaran - PMBM')
@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Dokumen & Kartu Pendaftaran</h1>
                    <p class="text-gray-600">Kelola dan unduh dokumen penting Anda di sini</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 rounded">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-id-card mr-2"></i>
                            <strong>No. Pendaftaran:</strong> PMBM-2026-001234
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{--
        <!-- Status Progress -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Status Pendaftaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Step 1 - Completed -->
                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Step 1</p>
                        <p class="text-sm font-semibold text-gray-800">Pendaftaran</p>
                    </div>
                </div>

                <!-- Step 2 - Completed -->
                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Step 2</p>
                        <p class="text-sm font-semibold text-gray-800">Verifikasi</p>
                    </div>
                </div>

                <!-- Step 3 - Active -->
                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center animate-pulse">
                            <i class="fas fa-clipboard-check text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Step 3</p>
                        <p class="text-sm font-semibold text-gray-800">Tes Seleksi</p>
                    </div>
                </div>

                <!-- Step 4 - Pending -->
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Step 4</p>
                        <p class="text-sm font-semibold text-gray-800">Pengumuman</p>
                    </div>
                </div>
            </div>
        </div>
        --}}

        <!-- Dokumen Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card: Kartu Pendaftaran -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-blue-600">
                <div class="p-6">
                    <!-- Header Card -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-id-card text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Kartu Pendaftaran</h3>
                                <p class="text-xs text-gray-500">Registration Card</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    </div>

                    <!-- Info -->
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Terbit: 10 Feb 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-file-pdf w-5 text-gray-400"></i>
                            <span>Format: PDF</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-download w-5 text-gray-400"></i>
                            <span>2 kali diunduh</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-4">
                        Kartu identitas pendaftaran yang wajib dibawa saat mengikuti seleksi.
                    </p>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2">
                        <button onclick="previewDocument('kartu-pendaftaran')" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>Lihat Kartu
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="downloadDocument('kartu-pendaftaran')" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                            <button onclick="printDocument('kartu-pendaftaran')" class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-print mr-1"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Kartu Ujian Seleksi -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-green-600">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-signature text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Kartu Ujian Seleksi</h3>
                                <p class="text-xs text-gray-500">Exam Card</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    </div>

                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Terbit: 12 Feb 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock w-5 text-gray-400"></i>
                            <span>Ujian: 15 Mar 2026, 08:00</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                            <span>Aula Madrasah</span>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Penting:</strong> Wajib dibawa saat ujian!
                        </p>
                    </div>

                    <div class="flex flex-col space-y-2">
                        <button onclick="previewDocument('kartu-ujian')" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>Lihat Kartu
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="downloadDocument('kartu-ujian')" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                            <button onclick="printDocument('kartu-ujian')" class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-print mr-1"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Bukti Pembayaran -->
            <!-- <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-purple-600">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Bukti Pembayaran</h3>
                                <p class="text-xs text-gray-500">Payment Receipt</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Lunas
                        </span>
                    </div>

                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Tanggal: 11 Feb 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-money-bill-wave w-5 text-gray-400"></i>
                            <span>Nominal: Rp 250.000</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-credit-card w-5 text-gray-400"></i>
                            <span>Metode: Transfer Bank</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Bukti pembayaran biaya pendaftaran yang telah terverifikasi.
                    </p>

                    <div class="flex flex-col space-y-2">
                        <button onclick="previewDocument('bukti-pembayaran')" class="w-full bg-purple-600 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-purple-700 transition flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>Lihat Bukti
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="downloadDocument('bukti-pembayaran')" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                            <button onclick="printDocument('bukti-pembayaran')" class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-print mr-1"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Card: Surat Keterangan Lulus (Locked) -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-gray-400 opacity-75">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trophy text-gray-400 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Surat Keterangan Lulus</h3>
                                <p class="text-xs text-gray-500">Acceptance Letter</p>
                            </div>
                        </div>
                        <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-lock mr-1"></i>Terkunci
                        </span>
                    </div>

                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Rilis: 5 Mei 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-hourglass-half w-5 text-gray-400"></i>
                            <span>Status: Menunggu Pengumuman</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Dokumen akan tersedia setelah pengumuman hasil seleksi
                        </p>
                    </div>

                    <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2.5 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Belum Tersedia
                    </button>
                </div>
            </div>

            <!-- Card: Formulir Daftar Ulang (Locked) -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-gray-400 opacity-75">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Formulir Daftar Ulang</h3>
                                <p class="text-xs text-gray-500">Re-registration Form</p>
                            </div>
                        </div>
                        <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-lock mr-1"></i>Terkunci
                        </span>
                    </div>

                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Rilis: 10 Mei 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user-check w-5 text-gray-400"></i>
                            <span>Untuk: Peserta Lulus</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Dokumen untuk siswa yang dinyatakan lulus seleksi
                        </p>
                    </div>

                    <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2.5 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Belum Tersedia
                    </button>
                </div>
            </div>

            <!-- Card: Panduan Ujian -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-orange-600">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Panduan Ujian</h3>
                                <p class="text-xs text-gray-500">Exam Guide</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    </div>

                    <div class="mb-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                            <span>Update: 12 Feb 2026</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-file-pdf w-5 text-gray-400"></i>
                            <span>12 Halaman</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-language w-5 text-gray-400"></i>
                            <span>Bahasa Indonesia</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Panduan lengkap pelaksanaan ujian seleksi, tata tertib, dan tips persiapan.
                    </p>

                    <div class="flex flex-col space-y-2">
                        <button onclick="previewDocument('panduan-ujian')" class="w-full bg-orange-600 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-orange-700 transition flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>Baca Panduan
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="downloadDocument('panduan-ujian')" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                            <button onclick="printDocument('panduan-ujian')" class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-print mr-1"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Info Banner -->
        <div class="mt-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-2">Informasi Penting</h3>
                    <ul class="space-y-2 text-blue-100">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1"></i>
                            <span>Pastikan Anda mencetak kartu ujian sebelum hari pelaksanaan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1"></i>
                            <span>Bawa kartu pendaftaran dan kartu ujian asli (tidak boleh fotokopi)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1"></i>
                            <span>Jika ada kesalahan data, segera hubungi panitia sebelum ujian</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1"></i>
                            <span>Simpan semua dokumen dengan baik untuk keperluan verifikasi</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <a href="#" class="inline-flex items-center bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                            <i class="fas fa-headset mr-2"></i>
                            Hubungi Panitia
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800" id="modal-title">Preview Dokumen</h3>
            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="preview-content" class="text-center">
                <i class="fas fa-file-pdf text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Preview dokumen akan muncul di sini</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview document
function previewDocument(docType) {
    const modal = document.getElementById('preview-modal');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('preview-content');
    
    const titles = {
        'kartu-pendaftaran': 'Kartu Pendaftaran',
        'kartu-ujian': 'Kartu Ujian Seleksi',
        'bukti-pembayaran': 'Bukti Pembayaran',
        'panduan-ujian': 'Panduan Ujian'
    };
    
    title.textContent = 'Preview: ' + titles[docType];
    
    // Simulate PDF preview (in real app, load actual PDF)
    content.innerHTML = `
        <div class="bg-gray-100 p-8 rounded-lg">
            <div class="bg-white shadow-lg max-w-2xl mx-auto p-8">
                <div class="text-center mb-6">
                    <img src="https://via.placeholder.com/100" alt="Logo" class="mx-auto mb-4 rounded-full">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">MTS N 2 KOTA SEMARANG</h2>
                    <h3 class="text-xl font-semibold text-blue-600">${titles[docType]}</h3>
                </div>
                <div class="border-t-2 border-gray-300 pt-6 space-y-3">
                    <p class="text-sm"><strong>No. Pendaftaran:</strong> PMBM-2026-001234</p>
                    <p class="text-sm"><strong>Nama:</strong> Ahmad Rizki Pratama</p>
                    <p class="text-sm"><strong>Jurusan Pilihan:</strong> Rekayasa Perangkat Lunak (RPL)</p>
                    <p class="text-sm"><strong>Tanggal Terbit:</strong> 10 Februari 2026</p>
                </div>
                <div class="mt-8 text-center">
                    <div class="inline-block border-2 border-gray-300 p-4">
                        <i class="fas fa-qrcode text-6xl text-gray-400"></i>
                        <p class="text-xs text-gray-500 mt-2">QR Code</p>
                    </div>
                </div>
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>Dokumen ini sah dan terverifikasi oleh sistem</p>
                </div>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
}

// Download document
function downloadDocument(docType) {
    // Simulate download (in real app, trigger actual download)
    alert('Mengunduh dokumen: ' + docType + '\n\nFile akan tersimpan di folder Downloads Anda.');
    
    // Here you would typically use:
    // window.location.href = '/download/' + docType;
}

// Print document
function printDocument(docType) {
    // Open preview first, then print
    previewDocument(docType);
    
    setTimeout(() => {
        window.print();
    }, 500);
}

// Close modal on outside click
document.getElementById('preview-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});
</script>
@endpush

@endsection