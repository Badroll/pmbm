@extends('layouts.app')

@section('title', $berita->BERITA_JUDUL . ' - PPDB MTs')

@section('content')

{{-- ===== BREADCRUMB ===== --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-green-600 transition-colors">Beranda</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('berita.index') }}" class="hover:text-green-600 transition-colors">Berita & Pegngumuman</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-700 truncate max-w-xs">{{ $berita->BERITA_JUDUL }}</span>
        </nav>
    </div>
</div>

{{-- ===== ARTIKEL UTAMA ===== --}}
<section class="mt-4 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Thumbnail --}}
            <div class="w-full overflow-hidden">
                <img src="{{ $berita->thumbnail_url }}"
                    alt="{{ $berita->BERITA_JUDUL }}"
                    class="w-full h-auto object-cover">
            </div>

            <div class="p-6 sm:p-10">

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $berita->badge_color }}">
                        {{ $berita->BERITA_KATEGORI }}
                    </span>
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $berita->tanggal_publish }}
                    </span>
                </div>

                {{-- Judul --}}
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 leading-tight mb-6">
                    {{ $berita->BERITA_JUDUL }}
                </h1>

                {{-- Divider --}}
                <div class="w-16 h-1 bg-green-500 rounded-full mb-6"></div>

                {{-- Isi Konten dari Quill --}}
                <div class="prose prose-green max-w-none text-gray-700 leading-relaxed ql-content">
                    {!! $berita->BERITA_ISI !!}
                </div>

                {{-- ===== SHARE MEDSOS ===== --}}
                <div class="mt-10 pt-6 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-600 mb-3 text-center">Bagikan:</p>
                    <div class="flex flex-wrap gap-3 justify-center">

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($berita->BERITA_JUDUL . ' - ' . url()->current()) }}"
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>

                        {{-- Salin Link --}}
                        <button onclick="salinLink(this)"
                                data-url="{{ url()->current() }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Salin Link</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="mb-6 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- ===== BERITA TERKAIT ===== --}}
        @if($beritaTerkait->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                Berita Terkait
                <span class="text-base font-normal text-gray-400 ml-1">— {{ $berita->BERITA_KATEGORI }}</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @include('berita._card', ['beritaList' => $beritaTerkait])
            </div>
        </div>
        @endif

        {{-- Tombol Kembali --}}
        <div class="mt-8 text-center">
            <a href="{{ route('berita.index') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-blue-600 text-blue-600 text-sm font-semibold hover:bg-blue-600 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Styling konten Quill agar tampil rapi */
    .ql-content h1 { font-size: 1.5rem; font-weight: 700; margin: 1.25rem 0 0.75rem; color: #1f2937; }
    .ql-content h2 { font-size: 1.25rem; font-weight: 700; margin: 1rem 0 0.5rem; color: #1f2937; }
    .ql-content h3 { font-size: 1.1rem; font-weight: 600; margin: 0.75rem 0 0.5rem; color: #374151; }
    .ql-content p  { margin-bottom: 0.875rem; line-height: 1.75; }
    .ql-content ul, .ql-content ol { padding-left: 1.5rem; margin-bottom: 0.875rem; }
    .ql-content ul { list-style-type: disc; }
    .ql-content ol { list-style-type: decimal; }
    .ql-content li { margin-bottom: 0.25rem; line-height: 1.75; }
    .ql-content strong { font-weight: 700; color: #111827; }
    .ql-content em { font-style: italic; }
    .ql-content a  { color: #16a34a; text-decoration: underline; }
    .ql-content blockquote {
        border-left: 4px solid #16a34a;
        padding: 0.5rem 1rem;
        margin: 1rem 0;
        background: #f0fdf4;
        color: #374151;
        font-style: italic;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    .ql-content img { max-width: 100%; border-radius: 0.75rem; margin: 1rem 0; }

    /* Hide scrollbar filter kategori */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
<script>
    function salinLink(btn) {
        const url = btn.getAttribute('data-url');
        navigator.clipboard.writeText(url).then(() => {
            const span = btn.querySelector('span');
            span.textContent = 'Tersalin!';
            btn.classList.add('bg-green-100', 'text-green-700');
            setTimeout(() => {
                span.textContent = 'Salin Link';
                btn.classList.remove('bg-green-100', 'text-green-700');
            }, 2000);
        });
    }
</script>
@endpush