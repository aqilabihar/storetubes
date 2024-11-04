<header class="bg-blue-600 shadow-lg fixed top-0 left-0 w-full z-50">
    <div class="flex justify-between items-center px-6 py-4">
        <!-- Logo / Title -->
        <h1 class="text-lg font-bold text-white">Sistem Notulen</h1>

        <!-- Navigation Links -->
        <nav class="flex items-center gap-8">
            <a href="/beranda_copy" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="/tambah_rapat" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-calendar-plus"></i> Buat Rapat
            </a>
            <a href="/kita_rapat" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-journal-text"></i> Rapat Anda
            </a>
            <a href="/notulen_view" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-file-earmark-text"></i> Lihat Notulen
            </a>
            <a href="/beranda" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-envelope-open"></i> Lihat Undangan Rapat
            </a>
        </nav>

        <!-- Icon Buttons -->
        <div class="relative">
            <div class="flex items-center gap-4">
                <!-- Mail Icon -->
                <button class="icon-button text-white p-2 rounded-full focus:outline-none" onclick="alert('Mail clicked')">
                    <i class="bi bi-envelope-fill h-6 w-6"></i>
                </button>
                <!-- Settings Icon -->
                <button class="icon-button text-white p-2 rounded-full focus:outline-none" onclick="alert('Settings clicked')">
                    <i class="bi bi-gear-fill h-6 w-6"></i>
                </button>
                <!-- Profile Icon with Dropdown -->
                <button class="icon-button bg-teal-700 p-3 rounded-full focus:outline-none" onclick="toggleDropdown()">
                    <i class="bi bi-person-circle h-6 w-6 text-white"></i>
                </button>
            </div>
            <!-- Dropdown for Profile Icon -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                <!--  -->
                <a href="<?= base_url('/auth/logout'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    // Function to toggle the visibility of the dropdown
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close the dropdown if clicked outside
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profileDropdown');
        const profileIcon = document.querySelector('.icon-button.bg-teal-700');
        if (!profileIcon.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>