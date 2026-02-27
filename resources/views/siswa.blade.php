@extends('layouts.app')
@section('title', 'Data Pendaftar - PMBM')
@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Data Pendaftar PMBM</h1>
                <p class="mt-0.5 text-sm text-gray-500">Daftar seluruh murid yang telah mendaftar</p>
            </div>
            <span class="inline-flex self-start sm:self-auto items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                {{ count($siswa) }} Pendaftar
            </span>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
        <form method="GET" action="#">
            <div class="flex flex-col gap-3">
                {{-- Search --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NISN, atau no. pendaftaran..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
                </div>

                {{-- Filter row --}}
                <div class="flex gap-2">
                    <select name="jalur"
                        class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition bg-white text-gray-700">
                        <option value="">Semua Jalur</option>
                        <option value="JALUR_REGULER"  {{ request('jalur') == 'JALUR_REGULER'  ? 'selected' : '' }}>Reguler</option>
                        <option value="JALUR_AFIRMASI" {{ request('jalur') == 'JALUR_AFIRMASI' ? 'selected' : '' }}>Afirmasi</option>
                        <option value="JALUR_PRESTASI" {{ request('jalur') == 'JALUR_PRESTASI' ? 'selected' : '' }}>Prestasi</option>
                    </select>

                    <select name="status"
                        class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition bg-white text-gray-700">
                        <option value="">Semua Status</option>
                        <option value="STATUS_PENDING"    {{ request('status') == 'STATUS_PENDING'    ? 'selected' : '' }}>Pending</option>
                        <option value="STATUS_VERIFIKASI" {{ request('status') == 'STATUS_VERIFIKASI' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="STATUS_DITERIMA"   {{ request('status') == 'STATUS_DITERIMA'   ? 'selected' : '' }}>Diterima</option>
                        <option value="STATUS_DITOLAK"    {{ request('status') == 'STATUS_DITOLAK'    ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search','jalur','status']))
                    <a href="#"
                        class="inline-flex items-center gap-1 px-4 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- === MOBILE: Card List (tampil di bawah sm) === --}}
    <div class="block sm:hidden space-y-3 mb-5">
        @forelse($siswa as $s)
        @php
            $jalurMap = [
                'JALUR_REGULER'  => ['label' => 'Reguler',  'class' => 'bg-blue-100 text-blue-700'],
                'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'class' => 'bg-orange-100 text-orange-700'],
                'JALUR_PRESTASI' => ['label' => 'Prestasi', 'class' => 'bg-purple-100 text-purple-700'],
            ];
            $statusMap = [
                'STATUS_PENDING'    => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700', 'dot' => 'bg-yellow-400'],
                'STATUS_VERIFIKASI' => ['label' => 'Verifikasi', 'class' => 'bg-blue-100 text-blue-700',    'dot' => 'bg-blue-400'],
                'STATUS_DITERIMA'   => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-700',  'dot' => 'bg-green-400'],
                'STATUS_DITOLAK'    => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-700',      'dot' => 'bg-red-400'],
            ];
            $jalur  = $jalurMap[$s->SISWA_JALUR]   ?? ['label' => $s->SISWA_JALUR,   'class' => 'bg-gray-100 text-gray-600'];
            $status = $statusMap[$s->SISWA_STATUS]  ?? ['label' => $s->SISWA_STATUS,  'class' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-start gap-3">
                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr($s->SISWA_NAMA, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $s->SISWA_NAMA }}</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $status['class'] }} whitespace-nowrap flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                            {{ $status['label'] }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">NISN: {{ $s->SISWA_NISN }}</p>
                    <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $s->SISWA_NO }}</p>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-400">Jalur</span>
                    <p><span class="inline-flex px-2 py-0.5 rounded-lg font-semibold {{ $jalur['class'] }}">{{ $jalur['label'] }}</span></p>
                </div>
                <div>
                    <span class="text-gray-400">Skor</span>
                    <p class="font-bold text-gray-800 text-sm">{{ number_format($s->SISWA_SKOR, 1) }}</p>
                </div>
                <div>
                    <span class="text-gray-400">Asal Sekolah</span>
                    <p class="text-gray-700 truncate">{{ $s->SISWA_SEKOLAH }}</p>
                </div>
                <div>
                    <span class="text-gray-400">Tgl. Daftar</span>
                    <p class="text-gray-700">{{ \Carbon\Carbon::parse($s->SISWA_TGL_DAFTAR)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-50 flex justify-end">
                <a href="siswa/{{ $s->SISWA_ID }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm text-gray-500">Tidak ada data pendaftar ditemukan.</p>
        </div>
        @endforelse
    </div>

    {{-- === DESKTOP: Table (tampil dari sm ke atas) === --}}
    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">No.</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Asal Sekolah</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tgl. Daftar</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Skor</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswa as $s)
                    @php
                        $jalurMap = [
                            'JALUR_REGULER'  => ['label' => 'Reguler',  'class' => 'bg-blue-100 text-blue-700'],
                            'JALUR_AFIRMASI' => ['label' => 'Afirmasi', 'class' => 'bg-orange-100 text-orange-700'],
                            'JALUR_PRESTASI' => ['label' => 'Prestasi', 'class' => 'bg-purple-100 text-purple-700'],
                        ];
                        $statusMap = [
                            'STATUS_PENDING'    => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700', 'dot' => 'bg-yellow-400'],
                            'STATUS_VERIFIKASI' => ['label' => 'Verifikasi', 'class' => 'bg-blue-100 text-blue-700',    'dot' => 'bg-blue-400'],
                            'STATUS_DITERIMA'   => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-700',  'dot' => 'bg-green-400'],
                            'STATUS_DITOLAK'    => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-700',      'dot' => 'bg-red-400'],
                        ];
                        $jalur  = $jalurMap[$s->SISWA_JALUR]   ?? ['label' => $s->SISWA_JALUR,   'class' => 'bg-gray-100 text-gray-600'];
                        $status = $statusMap[$s->SISWA_STATUS]  ?? ['label' => $s->SISWA_STATUS,  'class' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'];
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition">
                        <td class="px-5 py-4">
                            <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">{{ str_pad($s->SISWA_ID, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($s->SISWA_NAMA, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 whitespace-nowrap">{{ $s->SISWA_NAMA }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $s->SISWA_JENIS_KELAMIN == 'JENIS_KELAMIN_L' ? 'Laki-laki' : 'Perempuan' }}
                                        &middot; {{ \Carbon\Carbon::parse($s->SISWA_TGL_LAHIR)->age }} thn
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-600 font-mono text-xs whitespace-nowrap">{{ $s->SISWA_NISN }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap {{ $jalur['class'] }}">
                                {{ $jalur['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-600 max-w-[180px] truncate" title="{{ $s->SISWA_SEKOLAH }}">
                            {{ $s->SISWA_SEKOLAH }}
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($s->SISWA_TGL_DAFTAR)->format('d M Y') }}<br>
                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($s->SISWA_TGL_DAFTAR)->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-gray-800">{{ number_format($s->SISWA_SKOR, 1) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap {{ $status['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="siswa/{{ $s->SISWA_ID }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Tidak ada data pendaftar ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    {{--
    @if($siswa->hasPages())
    <div class="flex justify-center">
        {{ $siswa->appends(request()->query())->links() }}
    </div>
    @endif
    --}}

</div>
@push('scripts')
<script>
</script>
@endpush
@endsection