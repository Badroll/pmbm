<?php
    $isLoggedIn = Session::has("SESSION_U_ID");
    $sessionUserId = Session::get("SESSION_U_ID");
    $sessionUserRole = Session::get("SESSION_U_ROLE");
    $isSuperadmin = $sessionUserRole == "ROLE_SUPERADMIN";
    $isAdminBerkas = $sessionUserRole == "ROLE_ADMIN_BERKAS";
    $isAdminPMBM = $sessionUserRole == "ROLE_ADMIN_PMBM";
    $isSiswa= $sessionUserRole == "ROLE_SISWA";

    function isActiveDesktop($path){
        return Request::is($path)
            ? 'bg-white text-blue-600 font-semibold shadow py-1' 
            : 'text-white hover:bg-white hover:text-blue-600';
    }

    function isActiveMobile($path){
        return Request::is($path) 
            ? 'block bg-white text-blue-600 font-semibold' 
            : 'block text-white hover:text-blue-200';
    }
?>

<!-- Navbar -->
<nav class="bg-blue-600 shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Icon -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" class="w-9 h-9">
                <span class="text-white font-bold text-md">MTs Negeri 2 Kota Semarang</span>
            </div>

            <!-- Desktop Menu -->
            <?php
                $navMenuClass = "px-2 py-2 rounded-md transition-all duration-200 "; 
            ?>
            <div class="hidden md:flex space-x-8">
                <a href="{{ url('/') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('/') }}">Beranda</a>
                @if($isLoggedIn)
                    @if($isSiswa)
                    <a href="{{ url('/daftar') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('daftar') }}">Pendaftaran</a>
                    <a href="{{ url('/kartu') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('kartu') }}">Cetak Kartu</a>
                    <a href="{{ url('/inbox') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('inbox') }}">Notifikasi</a>
                    @elseif($isSuperadmin || $isAdminPMBM)
                        <a href="{{ url('/siswa') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('siswa') }}">Data Pendaftaran</a>
                    @endif
                    <a href="{{ url('/profil') }}" class="p{{ $navMenuClass }} {{ isActiveDesktop('profil') }}">Profil</a>
                @else
                    <a href="{{ url('/auth/login') }}" class="{{ $navMenuClass }} {{ isActiveDesktop('auth/login') }}">Masuk</a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
     <?php
        $navMenuClass = "block w-full px-3 py-2 rounded-md transition-all duration-200";
    ?>
    <div id="mobile-menu" class="hidden md:hidden bg-blue-700">
        <div class="px-4 py-3 space-y-3">
            <a href="{{ url('/') }}" class="{{ $navMenuClass }}{{ isActiveMobile('/') }}">Beranda</a>
            @if($isLoggedIn)
                @if($isSiswa)
                    <a href="{{ url('/daftar') }}" class="{{ $navMenuClass }} {{ isActiveMobile('daftar') }}">Pendaftaran</a>
                    <a href="{{ url('/kartu') }}" class="{{ $navMenuClass }} {{ isActiveMobile('kartu') }}">Cetak Kartu</a>
                    <a href="{{ url('/inbox') }}" class="{{ $navMenuClass }} {{ isActiveMobile('inbox') }}">Notifikasi</a>
                @elseif($isSuperadmin || $isAdminPMBM)
                    <a href="{{ url('/siswa') }}" class="{{ $navMenuClass }} {{ isActiveMobile('siswa') }}">Data Pendaftaran</a>
                @endif
                <a href="{{ url('/profil') }}" class="{{ $navMenuClass }} {{ isActiveMobile('profil') }}">Profil</a>
            @else
                <a href="{{ url('/auth/login') }}" class="{{ $navMenuClass }}  {{ isActiveMobile('auth/login') }}">Masuk</a>
            @endif
        </div>
    </div>
</nav>