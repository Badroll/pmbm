{{-- Flash Message Component - Place this in layouts/app.blade.php before closing body tag --}}

<!-- Floating Flash Messages Container -->
<div id="flash-container" class="fixed top-20 right-4 z-50 space-y-3 max-w-md w-full pointer-events-none">
    @if (session('success'))
        <div class="flash-message pointer-events-auto bg-white rounded-lg shadow-2xl border-l-4 border-green-500 p-4 transform transition-all duration-500 ease-out translate-x-full opacity-0"
             data-type="success">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-gray-800">Berhasil!</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="closeFlashMessage(this)" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- Progress Bar -->
            <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar h-full bg-green-500 rounded-full"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flash-message pointer-events-auto bg-white rounded-lg shadow-2xl border-l-4 border-red-500 p-4 transform transition-all duration-500 ease-out translate-x-full opacity-0"
             data-type="error">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-gray-800">Error!</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="closeFlashMessage(this)" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar h-full bg-red-500 rounded-full"></div>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="flash-message pointer-events-auto bg-white rounded-lg shadow-2xl border-l-4 border-yellow-500 p-4 transform transition-all duration-500 ease-out translate-x-full opacity-0"
             data-type="warning">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-gray-800">Peringatan!</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ session('warning') }}</p>
                </div>
                <button onclick="closeFlashMessage(this)" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar h-full bg-yellow-500 rounded-full"></div>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="flash-message pointer-events-auto bg-white rounded-lg shadow-2xl border-l-4 border-blue-500 p-4 transform transition-all duration-500 ease-out translate-x-full opacity-0"
             data-type="info">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-gray-800">Informasi</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ session('info') }}</p>
                </div>
                <button onclick="closeFlashMessage(this)" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar h-full bg-blue-500 rounded-full"></div>
            </div>
        </div>
    @endif
</div>

<style>
/* Flash Message Animations */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.flash-message {
    animation: slideIn 0.5s ease-out forwards;
}

.flash-message.closing {
    animation: slideOut 0.5s ease-out forwards;
}

.flash-message .progress-bar {
    animation: progressBar 5s linear forwards;
}
</style>

<script>
// Flash Message Functions
function closeFlashMessage(button) {
    const flashMessage = button.closest('.flash-message');
    flashMessage.classList.add('closing');
    
    setTimeout(() => {
        flashMessage.remove();
    }, 500);
}

// Auto dismiss after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        // Show animation
        setTimeout(() => {
            message.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Auto dismiss
        setTimeout(() => {
            message.classList.add('closing');
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});

// Function to show flash message programmatically (optional)
function showFlashMessage(type, title, message) {
    const container = document.getElementById('flash-container');
    
    const colors = {
        success: { border: 'border-green-500', bg: 'bg-green-100', text: 'text-green-600', icon: 'fa-check-circle', progress: 'bg-green-500' },
        error: { border: 'border-red-500', bg: 'bg-red-100', text: 'text-red-600', icon: 'fa-exclamation-circle', progress: 'bg-red-500' },
        warning: { border: 'border-yellow-500', bg: 'bg-yellow-100', text: 'text-yellow-600', icon: 'fa-exclamation-triangle', progress: 'bg-yellow-500' },
        info: { border: 'border-blue-500', bg: 'bg-blue-100', text: 'text-blue-600', icon: 'fa-info-circle', progress: 'bg-blue-500' }
    };
    
    const color = colors[type] || colors.info;
    
    const flashHTML = `
        <div class="flash-message pointer-events-auto bg-white rounded-lg shadow-2xl border-l-4 ${color.border} p-4 transform transition-all duration-500 ease-out translate-x-full opacity-0"
             data-type="${type}">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 ${color.bg} rounded-full flex items-center justify-center">
                        <i class="fas ${color.icon} ${color.text} text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-gray-800">${title}</h3>
                    <p class="text-sm text-gray-600 mt-1">${message}</p>
                </div>
                <button onclick="closeFlashMessage(this)" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar h-full ${color.progress} rounded-full"></div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', flashHTML);
    
    const newMessage = container.lastElementChild;
    
    // Show animation
    setTimeout(() => {
        newMessage.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto dismiss
    setTimeout(() => {
        newMessage.classList.add('closing');
        setTimeout(() => {
            newMessage.remove();
        }, 500);
    }, 5000);
}

// Example usage (you can call this from JavaScript):
// showFlashMessage('success', 'Berhasil!', 'Data telah disimpan');
// showFlashMessage('error', 'Error!', 'Terjadi kesalahan');
// showFlashMessage('warning', 'Peringatan!', 'Harap lengkapi data');
// showFlashMessage('info', 'Informasi', 'Proses sedang berjalan');
</script>