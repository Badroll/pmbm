<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searchable Dropdown</title>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-72">
        <label for="siswa" class="block text-sm font-medium text-gray-700 mb-2">Pilih Murid</label>
        <select id="siswa" name="siswa" class="w-full">
            <option value="">-- Pilih Murid --</option>
            <option value="1">Budi Santoso</option>
            <option value="2">Siti Aminah</option>
            <option value="3">Andi Pratama</option>
            <option value="4">Rina Kartika</option>
            <option value="5">Agus Setiawan</option>
        </select>
    </div>

    <script>
        const element = document.getElementById('siswa');
            const choices = new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                removeItemButton: false,
                shouldSort: false,
                placeholderValue: 'Cari siswa...',
            });
    </script>

</body>
</html>
