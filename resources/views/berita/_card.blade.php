@foreach($beritaList as $berita)
    <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
        {{-- Thumbnail --}}
        <a href="{{ route('berita.show', $berita->BERITA_SLUG) }}" class="block overflow-hidden flex-shrink-0">
            <div class="w-full h-48 overflow-hidden">
                <img src="{{ $berita->thumbnail_url }}"
                     alt="{{ $berita->BERITA_JUDUL }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy">
            </div>
        </a>
        {{-- Konten --}}
        <div class="p-5 flex flex-col flex-1">
            <div class="mb-3">
                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $berita->badge_color }}">
                    {{ $berita->BERITA_KATEGORI }}
                </span>
            </div>
            <a href="{{ route('berita.show', $berita->BERITA_SLUG) }}"
               class="block text-gray-800 font-bold text-base leading-snug hover:text-green-600 transition-colors mb-2 line-clamp-2">
                {{ $berita->BERITA_JUDUL }}
            </a>
            <p class="text-sm text-gray-500 line-clamp-3 flex-1">
                {{ $berita->excerpt }}
            </p>
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $berita->tanggal_publish }}
                </span>
                <a href="{{ route('berita.show', $berita->BERITA_SLUG) }}"
                   class="text-xs font-semibold text-blue-600 hover:text-green-700 transition-colors">
                    Baca Selengkapnya →
                </a>
            </div>
        </div>
    </article>
@endforeach