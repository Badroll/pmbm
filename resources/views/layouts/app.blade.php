<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PMBM')</title>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Choices -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.tailwindcss.min.css">
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.tailwindcss.min.js"></script>

    <style>
        /* .flatpickr-input { width: 100%; }
        .choices__inner {
            @apply bg-white border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm 
                focus-within:ring-2 focus-within:ring-blue-300 focus-within:border-blue-400;
            min-height: auto !important;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
        }
        .choices__list--single { padding: 0 !important; }
        .choices__input { @apply border-gray-300 text-gray-900 text-sm rounded-md; padding: 0.4rem 0.5rem !important; }
        .choices__list--dropdown { @apply border border-gray-300 rounded-md shadow-lg; }
        .choices__item--selectable.is-highlighted { @apply bg-blue-100 text-blue-700; } */
    </style>
</head>
<body class="bg-gray-50">

    @include('components.navbar')

    <main class="pt-16">
    @yield('content')
    </main>

    @include('components.footer')

    @include('components.flash-message')

    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offset = 64; // Height of fixed navbar
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // instant scroll to specific Y axis
        window.addEventListener("load", function () {
            const scrollPos = localStorage.getItem("scrollPos");
            if (scrollPos !== null) {
                window.scrollTo(0, parseInt(scrollPos));
                localStorage.removeItem("scrollPos");
            }
        });

        function setupDaterangepicker(elId, startElId, endElId, startDate = '', endDate = '') {
            const $el = $('#' + elId);
            const $start = $('#' + startElId);
            const $end = $('#' + endElId);

            const fp = flatpickr($el[0], {
                mode: 'range',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                allowInput: false,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        $start.val(instance.formatDate(selectedDates[0], 'Y-m-d'));
                        $end.val(instance.formatDate(selectedDates[1], 'Y-m-d'));
                    } else if (selectedDates.length === 1) {
                        $start.val(instance.formatDate(selectedDates[0], 'Y-m-d'));
                        $end.val('');
                    } else {
                        $start.val('');
                        $end.val('');
                    }
                    $start.trigger('change');
                    $end.trigger('change');
                }
            });

            // Set default jika ada
            if (startDate && endDate) {
                fp.setDate([startDate, endDate], true);
                $start.val(startDate);
                $end.val(endDate);
            }
        }

        function setupDropdown(id, placeholder, defaultValue = '') {
            const element = document.getElementById(id);
            const choices = new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                removeItemButton: false,
                shouldSort: false,
                placeholderValue: placeholder,
            });

            if (defaultValue) {
                choices.setChoiceByValue(defaultValue);
            }
        }

        function registerPreviewFile(id, previewId, maxSize, onDelete){
            const inputFile = $("#"+id);
            const preview = $("#"+previewId);
            const previewContainer = $("#preview_container_"+id);
            const btnHapus = $("#hapus_"+id);

            // PREVIEW DAN VALIDASI FOTO
            inputFile.on("change", function() {
                const file = this.files[0];

                if (!file) {
                    previewContainer.addClass("hidden");
                    preview.attr("src", "");
                    return;
                }

                if (file.size > (1024 * maxSize)) {
                    Swal.fire({
                        icon: "error",
                        title: "Ukuran terlalu besar!",
                        text: "Maksimal ukuran foto adalah "+maxSize+" KB"
                    });
                    $(this).val("");
                    previewContainer.addClass("hidden");
                    preview.attr("src", "");
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewContainer.removeClass("hidden");
                    preview.attr("src", e.target.result);
                    
                };
                reader.readAsDataURL(file);
            });

            // TOMBOL HAPUS FOTO
            btnHapus.on("click", function() {
                inputFile.val(""); // reset input file
                preview.attr("src", "");
                previewContainer.addClass("hidden");

                onDelete();
            });

        }

        function setPreviewFromUrl(id, previewId, imageUrl) {
            const preview = $("#"+previewId);
            const previewContainer = $("#preview_container_"+id);
            
            if (imageUrl) {
                preview.attr("src", imageUrl);
                previewContainer.removeClass("hidden");
            }
        }

    </script>

    @stack('scripts')
    
</body>
</html>