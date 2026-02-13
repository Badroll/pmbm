<?php
    $isLoggedIn = Session::has("SESSION_U_ID");
    $sessionUserId = Session::get("SESSION_U_ID");
    $sessionUserRole = Session::get("SESSION_U_ROLE");
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
            <div class="hidden md:flex space-x-8">
                <a href="{{ url('/') }}" class="text-white hover:text-blue-200 transition">Beranda</a>
                @if($isLoggedIn)
                <a href="{{ url('/daftar') }}" class="text-white hover:text-blue-200 transition">Pendaftaran</a>
                <a href="{{ url('/kartu') }}" class="text-white hover:text-blue-200 transition">Dokumen</a>
                <a href="{{ url('/inbox') }}" class="text-white hover:text-blue-200 transition">Notifikasi</a>
                <a href="{{ url('/profil') }}" class="text-white hover:text-blue-200 transition">Profil</a>
                @else
                <a href="{{ url('/auth/login') }}" class="text-white hover:text-blue-200 transition">Masuk</a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-700">
        <div class="px-4 py-3 space-y-3">
            <a href="{{ url('/') }}" class="block text-white hover:text-blue-200 transition py-2">Beranda</a>
            @if($isLoggedIn)
            <a href="{{ url('/daftar') }}" class="block text-white hover:text-blue-200 transition py-2">Pendaftaran</a>
            <a href="{{ url('/kartu') }}" class="block text-white hover:text-blue-200 transition py-2">Dokumen</a>
            <a href="{{ url('/inbox') }}" class="block text-white hover:text-blue-200 transition py-2">Notifikasi</a>
            <a href="{{ url('/profil') }}" class="block text-white hover:text-blue-200 transition py-2">Profil</a>
            @else
            <a href="{{ url('/auth/login') }}" class="block text-white hover:text-blue-200 transition py-2">Masuk</a>
            @endif
        </div>
    </div>
</nav>