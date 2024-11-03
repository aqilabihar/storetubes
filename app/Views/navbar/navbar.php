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
            <a href="/tambah_rapat" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-file-earmark-text"></i> Lihat Notulen
            </a>
            <a href="/beranda" class="nav-link text-white hover:text-teal-300 text-lg">
                <i class="bi bi-envelope-open"></i> Lihat Undangan Rapat
            </a>
        </nav>

        <!-- Icon Buttons -->
        <div class="flex items-center gap-4">
            <!-- Mail Icon -->
            <button class="icon-button text-white p-2 rounded-full focus:outline-none" onclick="alert('Mail clicked')">
                <i class="bi bi-envelope-fill h-6 w-6"></i>
            </button>
            <!-- Settings Icon -->
            <button class="icon-button text-white p-2 rounded-full focus:outline-none" onclick="alert('Settings clicked')">
                <i class="bi bi-gear-fill h-6 w-6"></i>
            </button>
            <!-- Profile Icon -->
            <button class="icon-button bg-teal-700 p-3 rounded-full focus:outline-none">
                <i class="bi bi-person-circle h-6 w-6 text-white"></i>
            </button>
        </div>
    </div>
</header>