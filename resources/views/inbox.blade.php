@extends('layouts.app')
@section('title', 'Inbox & Notifikasi - PMBM')
@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Inbox & Notifikasi</h1>
                    <p class="text-gray-600">Kelola semua notifikasi dan pesan Anda di sini</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-check-double mr-2"></i>
                        Tandai Semua Dibaca
                    </button>
                    <!-- <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Semua
                    </button> -->
                </div>
            </div>
        </div>

        {{--
        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="flex flex-wrap border-b border-gray-200">
                <button class="tab-button active px-6 py-4 font-semibold text-blue-600 border-b-2 border-blue-600 hover:bg-gray-50 transition">
                    <i class="fas fa-inbox mr-2"></i>Semua <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs ml-2">8</span>
                </button>
                <button class="tab-button px-6 py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-50 hover:text-gray-800 transition">
                    <i class="fas fa-check-circle mr-2"></i>Success <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs ml-2">2</span>
                </button>
                <button class="tab-button px-6 py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-50 hover:text-gray-800 transition">
                    <i class="fas fa-info-circle mr-2"></i>Info <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs ml-2">3</span>
                </button>
                <button class="tab-button px-6 py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-50 hover:text-gray-800 transition">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Warning <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs ml-2">2</span>
                </button>
                <button class="tab-button px-6 py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-50 hover:text-gray-800 transition">
                    <i class="fas fa-exclamation-circle mr-2"></i>Danger <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs ml-2">1</span>
                </button>
            </div>
        </div>
        --}}

        <!-- Notifications List -->
        <div class="space-y-4">

            <!-- SUCCESS Notification 2 -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-check text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Verifikasi Dokumen Selesai
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">5 jam lalu</span>
                            </div>
                            <p class="text-gray-600 mb-3">
                                Semua dokumen Anda telah diverifikasi dan dinyatakan lengkap. Anda dapat melanjutkan ke tahap selanjutnya dalam proses pendaftaran.
                            </p>
                            <div class="flex items-center space-x-3">
                                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-arrow-right mr-1"></i>Lanjutkan
                                </button>
                                <button class="text-sm text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-check mr-1"></i>Tandai Dibaca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFO Notification 2 -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Pengumuman Penting
                                    <!-- <span class="ml-2 bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">INFO</span> -->
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">2 hari lalu</span>
                            </div>
                            <p class="text-gray-600 mb-3">
                                Pengumuman hasil seleksi gelombang 1 akan diumumkan pada tanggal 5 Mei 2026. Cek secara berkala melalui website resmi atau dashboard Anda.
                            </p>
                            <div class="flex items-center space-x-3">
                                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-external-link-alt mr-1"></i>Buka Website
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFO Notification 3 -->
            <!-- <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 border-blue-500 opacity-60">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Pesan Selamat Datang
                                    <span class="ml-2 bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">INFO</span>
                                    <span class="ml-2 bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">DIBACA</span>
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">3 hari lalu</span>
                            </div>
                            <p class="text-gray-600 mb-3">
                                Terima kasih telah mendaftar di MTsN 2 Kota Semarang. Kami sangat senang Anda memilih sekolah kami untuk melanjutkan pendidikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- WARNING Notification 1 -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 border-yellow-500">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Dokumen Perlu Dilengkapi
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">1 jam lalu</span>
                            </div>
                            <p class="text-gray-600 mb-3">
                                Terdapat beberapa dokumen yang belum lengkap. Mohon upload dokumen berikut sebelum <strong>10 Maret 2026</strong>:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 mb-3 space-y-1">
                                <li>Surat Keterangan Sehat</li>
                                <li>Pas Foto 3x4 (background merah)</li>
                            </ul>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i>Batas waktu: <strong>3 hari lagi</strong>
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-upload mr-1"></i>Upload Sekarang
                                </button>
                                <button class="text-sm text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-check mr-1"></i>Tandai Dibaca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DANGER Notification -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 border-red-500">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Dokumen Ditolak
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-4">30 menit lalu</span>
                            </div>
                            <p class="text-gray-600 mb-3">
                                Dokumen Ijazah yang Anda upload ditolak karena tidak memenuhi persyaratan. Alasan penolakan:
                            </p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                <ul class="list-disc list-inside text-red-800 text-sm space-y-1">
                                    <li>Gambar tidak jelas (blur)</li>
                                    <li>Tidak ada cap legalisir dari sekolah</li>
                                    <li>Format file tidak sesuai (harus PDF atau JPG)</li>
                                </ul>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                <i class="fas fa-info-circle text-red-600 mr-1"></i>
                                Silakan upload ulang dokumen yang sesuai dengan ketentuan. Batas waktu: <strong class="text-red-600">2 hari lagi</strong>
                            </p>
                            <div class="flex items-center space-x-3">
                                <button class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-question-circle mr-1"></i>Lihat Panduan
                                </button>
                                <button class="text-sm text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-check mr-1"></i>Tandai Dibaca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Empty State (Hidden by default, shown when no notifications) -->
        <div id="empty-state" class="hidden bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Notifikasi</h3>
            <p class="text-gray-500">Anda sudah membaca semua notifikasi</p>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">1</button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active', 'text-blue-600', 'border-blue-600');
            tab.classList.add('text-gray-600', 'border-transparent');
        });
        
        // Add active class to clicked tab
        this.classList.add('active', 'text-blue-600', 'border-blue-600');
        this.classList.remove('text-gray-600', 'border-transparent');
        
        // Here you can add AJAX call to filter notifications
        // filterNotifications(this.textContent.trim());
    });
});

// Mark as read functionality
document.querySelectorAll('button').forEach(button => {
    if (button.textContent.includes('Tandai Dibaca')) {
        button.addEventListener('click', function() {
            const notificationCard = this.closest('.bg-white');
            notificationCard.style.opacity = '0.6';
            
            // Add "DIBACA" badge
            const title = notificationCard.querySelector('h3');
            if (!title.querySelector('.bg-gray-200')) {
                const readBadge = document.createElement('span');
                readBadge.className = 'ml-2 bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full';
                readBadge.textContent = 'DIBACA';
                title.appendChild(readBadge);
            }
            
            // Change button text
            this.innerHTML = '<i class="fas fa-check-double mr-1"></i>Sudah Dibaca';
            this.classList.add('text-green-600');
            this.disabled = true;
            
            // Here you can add AJAX call to update read status
            // markAsRead(notificationId);
        });
    }
});

// Delete notification functionality
document.querySelectorAll('button').forEach(button => {
    if (button.textContent.includes('Hapus')) {
        button.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                const notificationCard = this.closest('.bg-white.rounded-lg.shadow-sm');
                
                // Animate removal
                notificationCard.style.transition = 'all 0.3s ease';
                notificationCard.style.opacity = '0';
                notificationCard.style.transform = 'translateX(100%)';
                
                setTimeout(() => {
                    notificationCard.remove();
                    
                    // Check if there are no more notifications
                    const remainingNotifications = document.querySelectorAll('.space-y-4 > .bg-white');
                    if (remainingNotifications.length === 0) {
                        document.getElementById('empty-state').classList.remove('hidden');
                    }
                }, 300);
                
                // Here you can add AJAX call to delete notification
                // deleteNotification(notificationId);
            }
        });
    }
});

// Mark all as read
document.querySelector('button:has(.fa-check-double)').addEventListener('click', function() {
    if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Tandai Dibaca')) {
                button.click();
            }
        });
    }
});

// Delete all notifications
document.querySelector('button:has(.fa-trash)').addEventListener('click', function() {
    if (confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) {
        const notificationCards = document.querySelectorAll('.space-y-4 > .bg-white');
        
        notificationCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(100%)';
                
                setTimeout(() => {
                    card.remove();
                    
                    if (index === notificationCards.length - 1) {
                        document.getElementById('empty-state').classList.remove('hidden');
                    }
                }, 300);
            }, index * 100);
        });
        
        // Here you can add AJAX call to delete all notifications
        // deleteAllNotifications();
    }
});
</script>
@endpush

@endsection