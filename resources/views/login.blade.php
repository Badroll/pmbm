@extends('layouts.app')
@section('title', 'Login - PMBM')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <!-- <div class="inline-block bg-white p-4 rounded-full shadow-lg mb-4">
                <i class="fas fa-graduation-cap text-blue-600 text-5xl"></i>
            </div> -->
            <h1 class="text-4xl font-bold text-white mb-2">Masuk</h1>
            <p class="text-blue-100">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <form action="{{ url('auth/login') }}" method="POST" id="login-form">
                @csrf

                <!-- Email / No HP -->
                <div class="mb-6">
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

                <!-- Password -->
                <div class="mb-6">
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
                               placeholder="Masukkan password"
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
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm text-gray-700">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Lupa Password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="{{ url('auth/register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Buat Sekarang
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="/" class="text-white hover:text-blue-100 text-sm flex items-center justify-center">
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

// Auto-detect input type (email or phone)
document.getElementById('login_identifier').addEventListener('input', function(e) {
    const value = e.target.value;
    const icon = this.previousElementSibling.querySelector('i');
    
    if (value.includes('@')) {
        icon.classList.remove('fa-user', 'fa-phone');
        icon.classList.add('fa-envelope');
    } else if (/^\d+$/.test(value)) {
        icon.classList.remove('fa-user', 'fa-envelope');
        icon.classList.add('fa-phone');
    } else {
        icon.classList.remove('fa-envelope', 'fa-phone');
        icon.classList.add('fa-user');
    }
});


// Form validation
document.getElementById('login-form').addEventListener('submit', function(e) {

    const identifier = document.getElementById('login_identifier').value.trim();
    const password = document.getElementById('password').value;
    
    if (!identifier || !password) {
        e.preventDefault();
        alert('Semua field harus diisi!');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

    // const formData = new FormData();
    // formData.append('username', identifier);
    // formData.append('password', password);
    // $.ajax({
    //     url: '{{ url("auth/login") }}',
    //     method: 'POST',
    //     processData: false, // WAJIB
    //     contentType: false, // WAJIB
    //     data: formData,
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         //"x-login-token": "{{ Session::get('SESSION_U_LOGIN_TOKEN') }}"
    //     },
    //     beforeSend: function() {
    //         Swal.fire({
    //             title: 'Memproses...',
    //             text: 'Mohon tunggu sebentar.',
    //             allowOutsideClick: false,
    //             didOpen: () => Swal.showLoading()
    //         });
    //     },
    //     success: function(res) {
    //         console.log("KKKK", res);
    //         const status = res["STATUS"];
    //         const message = res["MESSAGE"];
    //         const swalIcon = status == "SUCCESS" ? 'success' : 'error';
    //         const swalTitle = status == "SUCCESS" ? 'Berhasil' : 'Gagal';
            
    //         Swal.fire({
    //             icon: swalIcon,
    //             title: swalTitle,
    //             text: message
    //         });
            
    //         if(status == "SUCCESS"){
    //             setTimeout(() => {
    //                 localStorage.setItem("scrollPos", window.scrollY);
    //                 location.reload();
    //             }, 1000);
    //         }
    //     },
    //     error: function(err) {
    //         console.error("XXX", err);
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Gagal',
    //             text: 'Maaf, terjadi kesalahan.'
    //         });
    //     }
    // });

});

</script>
@endpush

@endsection