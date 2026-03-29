@extends('layouts.app')

@section('title', 'Berita - PPDB MTs')

@section('content')

{{-- ===== HERO ===== --}}

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Berita & Pengumuman</h1>
                    <p class="text-gray-600">Dapatkan informasi berita & pengumuman PMBM dari Madrasah</p>
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

        {{-- ===== FILTER KATEGORI ===== --}}
        <section class="bg-white border-b border-gray-200 sticky top-16 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">
                    <a href="{{ route('berita.index') }}"
                    class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                            {{ !$kategoriAktif ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    @foreach($kategoriList as $kat)
                        <a href="{{ route('berita.index', ['kategori' => $kat]) }}"
                        class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                                {{ $kategoriAktif === $kat ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2">

                @if($beritaList->count() > 0)

                    {{-- Grid container — card di-append ke sini --}}
                    <div id="berita-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @include('berita._card', ['beritaList' => $beritaList])
                    </div>

                    {{-- Load More --}}
                    <div id="load-more-wrapper" class="mt-10 flex flex-col items-center gap-2 py-6">

                        {{-- Tombol — disembunyikan kalau tidak ada halaman berikutnya --}}
                        <button id="btnLoadMore"
                                data-next-page="{{ $beritaList->currentPage() + 1 }}"
                                data-kategori="{{ $kategoriAktif ?? '' }}"
                                data-url="{{ route('berita.index') }}"
                                class="{{ $beritaList->hasMorePages() ? '' : 'hidden' }}
                                    inline-flex items-center gap-2 px-6 py-2.5 rounded-full
                                    border border-blue-600 text-blue-600 text-sm font-semibold
                                    hover:bg-blue-600 hover:text-white transition-colors">
                            <svg id="icon-load" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span id="label-load">Muat Lebih Banyak</span>
                        </button>

                        {{-- Info sudah semua --}}
                        <p id="semua-tampil" class="hidden text-sm text-gray-400">
                            Semua berita sudah ditampilkan.
                        </p>

                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-20">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v12a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium mb-1">Belum ada berita</p>
                        <p class="text-gray-400 text-sm">
                            @if($kategoriAktif)
                                Belum ada berita dalam kategori <strong>{{ $kategoriAktif }}</strong>.
                                <a href="{{ route('berita.index') }}" class="text-blue-600 hover:underline">Lihat semua berita</a>
                            @else
                                Belum ada berita yang dipublikasikan.
                            @endif
                        </p>
                    </div>
                @endif

            </div>
        </section>

    </div>
</div>

@endsection

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Animasi card masuk saat load more */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .card-animate {
        animation: fadeSlideUp 0.3s ease forwards;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {

    const btn = $('#btnLoadMore');

    btn.on('click', function () {
        const nextPage  = parseInt(btn.data('next-page'));
        const kategori  = btn.data('kategori');
        const url       = btn.data('url');

        // Loading state
        btn.prop('disabled', true);
        $('#icon-load').addClass('animate-spin');
        $('#label-load').text('Memuat...');

        $.ajax({
            url:     url,
            type:    'GET',
            data:    {
                page:     nextPage,
                kategori: kategori || undefined,
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                // Append card baru ke grid
                const $newCards = $(res.html);
                $newCards.addClass('card-animate');
                $('#berita-grid').append($newCards);

                if (res.hasMore) {
                    // Update nomor halaman berikutnya
                    btn.data('next-page', res.nextPage);
                    btn.prop('disabled', false);
                    $('#icon-load').removeClass('animate-spin');
                    $('#label-load').text('Muat Lebih Banyak');
                } else {
                    // Semua data sudah ditampilkan
                    btn.addClass('hidden');
                    $('#semua-tampil').removeClass('hidden');
                }
            },
            error: function () {
                btn.prop('disabled', false);
                $('#icon-load').removeClass('animate-spin');
                $('#label-load').text('Muat Lebih Banyak');
                alert('Gagal memuat berita. Silakan coba lagi.');
            }
        });
    });

});
</script>
@endpush