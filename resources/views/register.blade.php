@extends('layouts.app')
@section('title', 'Register - PMBM')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <!-- <div class="inline-block bg-white p-4 rounded-full shadow-lg mb-4">
                <i class="fas fa-user-plus text-blue-600 text-5xl"></i>
            </div> -->
            <h1 class="text-4xl font-bold text-white mb-2">Buat Akun Baru</h1>
            <p class="text-blue-100">Buat akun untuk memulai pendaftaran</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <form action="{{ url('auth/register') }}" method="POST" id="register-form">
                @csrf

                <!-- Email / No HP -->
                <div class="mb-5">
                    <label for="login_identifier" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email atau No. HP <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="login_identifier" 
                               name="username" 
                               value="{{ old('login_identifier') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('login_identifier') border-red-500 @enderror"
                               placeholder="contoh@email.com atau 08xxxxxxxxxx"
                               required>
                    </div>
                    @error('login_identifier')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- <div class="mb-5">
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
                               value="{{ old('email') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                               placeholder="contoh@email.com"
                               required>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Email akan digunakan untuk verifikasi akun
                    </p>
                </div> -->

                <!-- <div class="mb-5">
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
                               value="{{ old('no_hp') }}"
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
                </div> -->

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter"
                               minlength="8"
                               required>
                        <button type="button" 
                                onclick="togglePassword('password')" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center space-x-1">
                            <div id="strength-bar-1" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                            <div id="strength-bar-2" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                            <div id="strength-bar-3" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                            <div id="strength-bar-4" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-gray-500 mt-1"></p>
                    </div>

                    <ul class="mt-2 text-xs text-gray-600 space-y-1">
                        <li class="flex items-center" id="req-length">
                            <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                            Minimal 8 karakter
                        </li>
                        <li class="flex items-center" id="req-uppercase">
                            <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                            Mengandung huruf besar
                        </li>
                        <li class="flex items-center" id="req-lowercase">
                            <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                            Mengandung huruf kecil
                        </li>
                        <li class="flex items-center" id="req-number">
                            <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                            Mengandung angka
                        </li>
                    </ul>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Ulangi password"
                               required>
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <i id="password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="password-match" class="mt-2 hidden">
                        <p class="text-sm flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-green-600">Password cocok</span>
                        </p>
                    </div>
                    <div id="password-nomatch" class="mt-2 hidden">
                        <p class="text-sm flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-red-600">Password tidak cocok</span>
                        </p>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               required>
                        <label for="terms" class="ml-3 text-sm text-gray-700">
                            Saya menyetujui 
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Syarat dan Ketentuan</a> 
                            serta 
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Kebijakan Privasi</a>
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        id="submit-btn"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Sudah punya akun? 
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Masuk Sekarang
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="#" class="text-white hover:text-blue-100 text-sm flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Beranda
            </a>
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

// Password strength checker
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    let strength = 0;
    
    // Check requirements
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    // Update requirement indicators
    updateRequirement('req-length', hasLength);
    updateRequirement('req-uppercase', hasUppercase);
    updateRequirement('req-lowercase', hasLowercase);
    updateRequirement('req-number', hasNumber);
    
    // Calculate strength
    if (hasLength) strength++;
    if (hasUppercase) strength++;
    if (hasLowercase) strength++;
    if (hasNumber) strength++;
    
    // Update strength bars
    const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
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
    
    const strengthText = document.getElementById('strength-text');
    if (password.length > 0) {
        strengthText.textContent = 'Kekuatan password: ' + texts[strength - 1];
        strengthText.className = 'text-xs mt-1 ' + 
            (strength === 1 ? 'text-red-500' : 
             strength === 2 ? 'text-yellow-500' : 
             strength === 3 ? 'text-blue-500' : 'text-green-500');
    } else {
        strengthText.textContent = '';
    }
    
    // Check password match
    checkPasswordMatch();
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
document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const matchDiv = document.getElementById('password-match');
    const nomatchDiv = document.getElementById('password-nomatch');
    
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

// Form validation
document.getElementById('register-form').addEventListener('submit', function(e) {
    const login_identifier = document.getElementById('login_identifier').value.trim();
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const terms = document.getElementById('terms').checked;
    
    if (!login_identifier || || !password || !confirmation) {
        e.preventDefault();
        alert('Semua field harus diisi!');
        return false;
    }
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        return false;
    }
    
    if (!terms) {
        e.preventDefault();
        alert('Anda harus menyetujui Syarat dan Ketentuan!');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
});

// // Phone number input validation
// document.getElementById('no_hp').addEventListener('input', function(e) {
//     // Remove non-numeric characters
//     this.value = this.value.replace(/[^0-9]/g, '');
    
//     // Limit to 13 digits
//     if (this.value.length > 13) {
//         this.value = this.value.slice(0, 13);
//     }
// });

</script>
@endpush

@endsection