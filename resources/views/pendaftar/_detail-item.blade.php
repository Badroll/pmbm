{{-- resources/views/pendaftar/_detail-item.blade.php --}}
{{-- Usage: @include('pendaftar._detail-item', ['label' => '...', 'value' => '...', 'mono' => false]) --}}
<div>
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">{{ $label }}</p>
    <p class="text-sm text-gray-800 font-medium {{ isset($mono) && $mono ? 'font-mono' : '' }}">
        {{ $value ?: '-' }}
    </p>
</div>