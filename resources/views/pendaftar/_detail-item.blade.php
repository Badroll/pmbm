{{-- resources/views/pendaftar/_detail-item.blade.php --}}
{{-- Usage: @include('pendaftar._detail-item', ['label' => '...', 'value' => '...', 'mono' => false]) --}}
<div>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
    <p class="text-sm text-gray-800 font-semibold {{ isset($mono) && $mono ? 'font-mono tracking-wide bg-gray-100 inline-block px-2 py-0.5 rounded-lg' : '' }}">
        {{ $value ?: '—' }}
    </p>
</div>