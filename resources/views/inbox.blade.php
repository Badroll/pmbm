@extends('layouts.app')
@section('title', 'Notifikasi - PMBM')
@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifikasi</h1>
                    <p class="text-gray-600">Notifikasi informasi PMBM</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <!-- <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-check-double mr-2"></i>
                        Tandai Semua Dibaca
                    </button>
                    <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Semua
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">

        @forelse($inbox as $item)

        @php
            $jenis = $item->INBOX_JENIS;

            $map = [
                'info' => [
                    'border' => 'border-blue-500',
                    'bg' => 'bg-blue-100',
                    'icon' => 'fa-bell',
                    'iconColor' => 'text-blue-600'
                ],
                'success' => [
                    'border' => 'border-green-500',
                    'bg' => 'bg-green-100',
                    'icon' => 'fa-user-check',
                    'iconColor' => 'text-green-600'
                ],
                'warning' => [
                    'border' => 'border-yellow-500',
                    'bg' => 'bg-yellow-100',
                    'icon' => 'fa-exclamation-triangle',
                    'iconColor' => 'text-yellow-600'
                ],
                'error' => [
                    'border' => 'border-red-500',
                    'bg' => 'bg-red-100',
                    'icon' => 'fa-exclamation-circle',
                    'iconColor' => 'text-red-600'
                ],
            ];

            $style = $map[$jenis] ?? $map['info'];

            $waktu = $item->INBOX_WAKTU_KIRIM ?? $item->INBOX_WAKTU_BUAT;
        @endphp

        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-l-4 {{ $style['border'] }}">
            <div class="p-5">
                <div class="flex items-start space-x-4">

                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 {{ $style['bg'] }} rounded-full flex items-center justify-center">
                            <i class="fas {{ $style['icon'] }} {{ $style['iconColor'] }} text-xl"></i>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $item->INBOX_JUDUL }}
                            </h3>

                            <span class="text-xs text-gray-500 whitespace-nowrap ml-4">
                                {{ \Carbon\Carbon::parse($waktu)->locale('id')->diffForHumans() }}
                            </span>
                        </div>

                        <p class="text-gray-600">
                            {!! nl2br((
                                str_replace(["*", "_"], "", $item->INBOX_ISI)
                            )) !!}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        @empty

        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Notifikasi</h3>
            <p class="text-gray-500">Belum ada notifikasi</p>
        </div>

        @endforelse

        </div>

        <!-- Empty State (Hidden by default, shown when no notifications) -->
        <div id="empty-state" class="hidden bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Notifikasi</h3>
            <p class="text-gray-500">Anda sudah membaca semua notifikasi</p>
        </div>

        <!-- Pagination -->
        <!-- <div class="mt-8 flex justify-center">
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
        </div> -->

    </div>
</div>

@push('scripts')
<script>



</script>
@endpush

@endsection