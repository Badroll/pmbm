@extends('layouts.app')
@section('title', 'Profil Saya - PMBM')
@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi akun dan keamanan Anda</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sidebar Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                    <!-- Avatar -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                                {{ strtoupper(substr(Session::get('SESSION_U_EMAIL') ?? 'U', 0, 1)) }}
                            </div>
                            <!-- <button class="absolute bottom-2 right-2 bg-blue-600 text-white w-10 h-10 rounded-full hover:bg-blue-700 transition shadow-lg">
                                <i class="fas fa-camera"></i>
                            </button> -->
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ Session::get('SESSION_U_USERNAME') ?? 'User' }}</h3>
                        <p class="text-sm text-gray-500">{{ Session::get('SESSION_U_EMAIL') ?? '-' }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="#info-akun" class="nav-link active flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-lg font-medium transition">
                            <i class="fas fa-user-circle mr-3"></i>
                            Informasi Akun
                        </a>
                        <a href="#ganti-password" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <i class="fas fa-lock mr-3"></i>
                            Ganti Password
                        </a>
                        <!-- <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <i class="fas fa-bell mr-3"></i>
                            Notifikasi
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <i class="fas fa-shield-alt mr-3"></i>
                            Keamanan
                        </a> -->
                    </nav>

                    <!-- Logout Button -->
                    <form action="{{ url('auth/logout') }}" method="GET" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section: Informasi Akun -->
                <div id="info-akun" class="bg-white rounded-lg shadow-sm">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-1">Informasi Akun</h2>
                                <p class="text-sm text-gray-600">Update email dan nomor HP Anda</p>
                            </div>
                            <i class="fas fa-user-edit text-3xl text-blue-600"></i>
                        </div>
                    </div>

                    <form action="#" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Session::get('SESSION_U_EMAIL') ?? 'a@b.c') }}"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                       placeholder="contoh@email.com"
                                       required>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Email akan digunakan untuk login dan notifikasi penting
                            </p>
                        </div>

                        <!-- No HP -->
                        <div class="mb-6">
                            <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                                No. HP / WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       value="{{ old('no_hp', Session::get('SESSION_U_PHONE') ?? '08x') }}"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('no_hp') border-red-500 @enderror"
                                       placeholder="08xxxxxxxxxx"
                                       pattern="[0-9]{10,13}"
                                       required>
                            </div>
                            @error('no_hp')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Nomor aktif WhatsApp untuk notifikasi
                            </p>
                        </div>

                        <!-- Last Updated Info -->
                        <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-clock mr-2"></i>
                                Terakhir diperbarui: <strong>{{ Auth::user() ? Auth::user()->updated_at->format('d M Y, H:i') : 'Belum pernah' }}</strong>
                            </p>
                        </div> -->

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md hover:shadow-lg flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                            <!-- <button type="reset" 
                                    class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center justify-center">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button> -->
                        </div>
                    </form>
                </div>

                <!-- Section: Ganti Password -->
                <div id="ganti-password" class="bg-white rounded-lg shadow-sm">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-1">Ganti Password</h2>
                                <p class="text-sm text-gray-600">Perbarui password Anda secara berkala untuk keamanan</p>
                            </div>
                            <i class="fas fa-key text-3xl text-blue-600"></i>
                        </div>
                    </div>

                    <form action="#" method="POST" id="password-form" class="p-6">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('current_password') border-red-500 @enderror"
                                       placeholder="Masukkan password saat ini"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('current_password')" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i id="current_password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-6">
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password" 
                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('new_password') border-red-500 @enderror"
                                       placeholder="Minimal 8 karakter"
                                       minlength="8"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('new_password')" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i id="new_password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-3">
                                <div class="flex items-center space-x-1">
                                    <div id="new-strength-bar-1" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                    <div id="new-strength-bar-2" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                    <div id="new-strength-bar-3" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                    <div id="new-strength-bar-4" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                </div>
                                <p id="new-strength-text" class="text-xs text-gray-500 mt-1"></p>
                            </div>

                            <ul class="mt-2 text-xs text-gray-600 space-y-1">
                                <li class="flex items-center" id="new-req-length">
                                    <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                    Minimal 8 karakter
                                </li>
                                <li class="flex items-center" id="new-req-uppercase">
                                    <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                    Mengandung huruf besar
                                </li>
                                <li class="flex items-center" id="new-req-lowercase">
                                    <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                    Mengandung huruf kecil
                                </li>
                                <li class="flex items-center" id="new-req-number">
                                    <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                    Mengandung angka
                                </li>
                            </ul>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-6">
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('new_password_confirmation') border-red-500 @enderror"
                                       placeholder="Ulangi password baru"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('new_password_confirmation')" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i id="new_password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="new-password-match" class="mt-2 hidden">
                                <p class="text-sm flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-green-600">Password cocok</span>
                                </p>
                            </div>
                            <div id="new-password-nomatch" class="mt-2 hidden">
                                <p class="text-sm flex items-center">
                                    <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                    <span class="text-red-600">Password tidak cocok</span>
                                </p>
                            </div>
                        </div>

                        <!-- Security Tips -->
                        <!-- <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                                <i class="fas fa-shield-alt mr-2"></i>Tips Keamanan Password
                            </h4>
                            <ul class="text-xs text-yellow-700 space-y-1">
                                <li>• Jangan gunakan password yang sama dengan akun lain</li>
                                <li>• Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
                                <li>• Hindari menggunakan informasi pribadi (tanggal lahir, nama, dll)</li>
                            </ul>
                        </div> -->

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md hover:shadow-lg flex items-center justify-center">
                                <i class="fas fa-key mr-2"></i>
                                Update Password
                            </button>
                            <!-- <button type="reset" 
                                    class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center justify-center">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button> -->
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Navigation active state
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active from all
        document.querySelectorAll('.nav-link').forEach(l => {
            l.classList.remove('active', 'text-blue-600', 'bg-blue-50');
            l.classList.add('text-gray-700');
        });
        
        // Add active to clicked
        this.classList.add('active', 'text-blue-600', 'bg-blue-50');
        this.classList.remove('text-gray-700');
        
        // Scroll to section
        const target = this.getAttribute('href');
        if (target.startsWith('#')) {
            document.querySelector(target).scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Phone number validation
document.getElementById('no_hp').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 13) {
        this.value = this.value.slice(0, 13);
    }
});

// Password strength checker for new password
document.getElementById('new_password').addEventListener('input', function(e) {
    const password = e.target.value;
    let strength = 0;
    
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    updateRequirement('new-req-length', hasLength);
    updateRequirement('new-req-uppercase', hasUppercase);
    updateRequirement('new-req-lowercase', hasLowercase);
    updateRequirement('new-req-number', hasNumber);
    
    if (hasLength) strength++;
    if (hasUppercase) strength++;
    if (hasLowercase) strength++;
    if (hasNumber) strength++;
    
    const bars = ['new-strength-bar-1', 'new-strength-bar-2', 'new-strength-bar-3', 'new-strength-bar-4'];
    const colors = ['bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
    const texts = ['Lemah', 'Cukup', 'Baik', 'Kuat'];
    
    bars.forEach((bar, index) => {
        const element = document.getElementById(bar);
        element.className = 'h-1 w-1/4 rounded';
        
        if (index < strength) {
            element.classList.add(colors[strength - 1]);
        } else {
            element.classList.add('bg-gray-200');
        }
    });
    
    const strengthText = document.getElementById('new-strength-text');
    if (password.length > 0) {
        strengthText.textContent = 'Kekuatan password: ' + texts[strength - 1];
        strengthText.className = 'text-xs mt-1 ' + 
            (strength === 1 ? 'text-red-500' : 
             strength === 2 ? 'text-yellow-500' : 
             strength === 3 ? 'text-blue-500' : 'text-green-500');
    } else {
        strengthText.textContent = '';
    }
    
    checkNewPasswordMatch();
});

function updateRequirement(id, met) {
    const element = document.getElementById(id);
    const icon = element.querySelector('i');
    
    if (met) {
        icon.classList.remove('fa-circle', 'text-gray-300');
        icon.classList.add('fa-check-circle', 'text-green-500');
        element.classList.add('text-green-600');
    } else {
        icon.classList.remove('fa-check-circle', 'text-green-500');
        icon.classList.add('fa-circle', 'text-gray-300');
        element.classList.remove('text-green-600');
    }
}

// Check password confirmation match
document.getElementById('new_password_confirmation').addEventListener('input', checkNewPasswordMatch);

function checkNewPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    const matchDiv = document.getElementById('new-password-match');
    const nomatchDiv = document.getElementById('new-password-nomatch');
    
    if (confirmation.length > 0) {
        if (password === confirmation) {
            matchDiv.classList.remove('hidden');
            nomatchDiv.classList.add('hidden');
        } else {
            matchDiv.classList.add('hidden');
            nomatchDiv.classList.remove('hidden');
        }
    } else {
        matchDiv.classList.add('hidden');
        nomatchDiv.classList.add('hidden');
    }
}

// Password form validation
document.getElementById('password-form').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    
    if (!currentPassword || !newPassword || !confirmation) {
        e.preventDefault();
        alert('Semua field password harus diisi!');
        return false;
    }
    
    if (newPassword !== confirmation) {
        e.preventDefault();
        alert('Password baru dan konfirmasi tidak cocok!');
        return false;
    }
    
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('Password baru minimal 8 karakter!');
        return false;
    }
    
    if (currentPassword === newPassword) {
        e.preventDefault();
        alert('Password baru tidak boleh sama dengan password lama!');
        return false;
    }
    
    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
});

// Scroll spy for navigation
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('[id^="info-"], [id^="ganti-"]');
    const navLinks = document.querySelectorAll('.nav-link');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.pageYOffset >= sectionTop) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active', 'text-blue-600', 'bg-blue-50');
        link.classList.add('text-gray-700');
        
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active', 'text-blue-600', 'bg-blue-50');
            link.classList.remove('text-gray-700');
        }
    });
});
</script>
@endpush

@endsection