<button id="toggleSidebar"
    class="md:hidden fixed top-4 left-4 z-30 bg-utama text-kedua p-2 rounded shadow-lg hover:bg-kedua hover:text-utama transition-all duration-200">
    <i class="fa-solid fa-bars"></i>
</button>

<aside id="sidebar"
    class="fixed top-0 left-0 w-64 h-full bg-utama font-utama text-kedua shadow-lg flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-30">

    <div class="p-6 border-b border-kedua/30">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1">
                <img src="{{ asset('assets/images/LOGO-ONE-INVEN.png') }}" class="w-16 h-16 object-contain flex-shrink-0" alt="Logo One Inven ">
                <div class="flex flex-col justify-center">
                    <span class="font-bold text-lg text-kedua leading-tight">One Inven</span>
                    <span class="text-xs text-kedua leading-tight">Inventory System</span>
                </div>
            </div>
            
            <!-- Close Button -->
            <button id="closeSidebar" class="md:hidden hover:bg-kedua/10 p-2 rounded-lg transition-all duration-200 flex-shrink-0">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>
    
    <div class="p-4 border-b border-kedua relative">

        <button class="dropdown-btn flex items-center gap-3 w-full hover:bg-kedua/20 p-2 rounded transition">
            @if(auth()->user()->foto_pengguna)
                <img src="{{ asset('storage/pengguna/' . auth()->user()->foto_pengguna) }}"
                     class="h-10 w-10 rounded-full border-2 border-kedua object-cover">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_pengguna) }}&background=ffffff&color=1E3A8A&bold=true"
                     class="h-10 w-10 rounded-full border-2 border-kedua">
            @endif

            <div class="flex-1 text-left">
                <p class="font-semibold">{{ auth()->user()->nama_pengguna }}</p>
                <p class="text-sm">{{ ucfirst(auth()->user()->role_pengguna) }}</p>
            </div>

            <i class="fa-solid fa-chevron-right dropdown-arrow transition-transform duration-200"></i>
        </button>

        <div class="dropdown-menu hidden absolute left-4 right-4 mt-3 bg-white text-utama rounded-2xl shadow-[0_8px_25px_rgba(0,0,0,0.12)] p-4 z-50 animate-dropdown">
            <div class="space-y-3">

                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 flex items-center justify-center bg-utama text-kedua rounded-lg flex-shrink-0">
                        <i class="fa-solid fa-user text-base"></i>
                    </div>
                    <span class="font-bold text-sm">{{ auth()->user()->nama_lengkap }}</span>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 flex items-center justify-center bg-utama text-kedua rounded-lg flex-shrink-0">
                        <i class="fa-solid fa-at text-base"></i>
                    </div>
                    <span class="font-bold text-sm">{{ auth()->user()->nama_pengguna }}</span>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 flex items-center justify-center bg-utama text-kedua rounded-lg flex-shrink-0">
                        <i class="fa-solid fa-envelope text-base"></i>
                    </div>
                    <span class="font-bold text-sm min-w-0 break-words">{{ auth()->user()->email ?? '-' }}</span>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 flex items-center justify-center bg-utama text-kedua rounded-lg flex-shrink-0">
                        <i class="fa-solid fa-user-shield text-base"></i>
                    </div>
                    <span class="font-bold text-sm">{{ ucfirst(auth()->user()->role_pengguna) }}</span>
                </div>

            </div>

            <hr class="my-3 bg-utama">

            <div class="space-y-2">

                <a href="{{ route('profile') }}"class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl bg-yellow-500 text-kedua font-bold text-sm transition-all duration-200 hover:brightness-95 shadow-sm">
                    <i class="fa-solid fa-user-pen text-base"></i>
                    <span>Edit Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl bg-red-500 text-kedua font-bold text-sm transition-all duration-200 hover:brightness-95 shadow-sm">
                        <i class="fa-solid fa-right-from-bracket text-base"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

        @if (in_array(auth()->user()->role_pengguna, ['peminjam']))
            <a href="{{ route('peminjaman.dashboard') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-gauge group-hover:scale-110 transition-transform duration-200"></i>
                <span>Dashboard</span>
            </a>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['peminjam']))
            <a href="{{ route('peminjaman.barang') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-box group-hover:scale-110 transition-transform duration-200"></i>
                <span>Barang</span>
            </a>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['admin', 'petugas']))
            <a href="{{ route('management.dashboard') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-gauge group-hover:scale-110 transition-transform duration-200"></i>
                <span>Dashboard</span>
            </a>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['admin', 'petugas']))
            <div class="space-y-1">
                <button class="dropdown-btn menu-item group flex items-center justify-between w-full px-3 py-2 rounded-lg transition-all duration-200">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-warehouse group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Inventaris</span>
                    </span>
                    <i class="fa-solid fa-chevron-right dropdown-arrow transition-transform duration-200"></i>
                </button>

                <div class="dropdown-menu ml-4 mt-1 hidden space-y-1">
                    <a href="{{ route('management.inventaris-ruangan.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-door-open group-hover:scale-110 transition-transform duration-200"></i> <span>Ruangan</span></a>
                    <a href="{{ route('management.inventaris-kategori.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-tags group-hover:scale-110 transition-transform duration-200"></i> <span>Kategori</span></a>
                    <a href="{{ route('management.inventaris-barang.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-box group-hover:scale-110 transition-transform duration-200"></i> <span>Barang</span></a>
                    <a href="{{ route('management.inventaris-barang-ruangan.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-object-group group-hover:scale-110 transition-transform duration-200"></i> <span>Berdasarkan Ruangan</span></a>
                </div>
            </div>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['admin', 'petugas']))
            <div class="space-y-1">
                <button class="dropdown-btn menu-item group flex items-center justify-between w-full px-3 py-2 rounded-lg transition-all duration-200">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Kelola Barang</span>
                    </span>
                    <i class="fa-solid fa-chevron-right dropdown-arrow transition-transform duration-200"></i>
                </button>

                <div class="dropdown-menu ml-4 mt-1 hidden space-y-1">
                    <a href="{{ route('management.inventaris-barang.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-box group-hover:scale-110 transition-transform duration-200"></i> <span>Barang</span></a>
                    <a href="{{ route('management.barang-masuk.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-truck group-hover:scale-110 transition-transform duration-200"></i> <span>Barang Masuk</span></a>
                    <a href="{{ route('management.barang-keluar.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-truck-moving group-hover:scale-110 transition-transform duration-200"></i> <span>Barang Keluar</span></a>
                    <a href="{{ route('management.transaksi-perbaikan.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-screwdriver-wrench group-hover:scale-110 transition-transform duration-200"></i> <span>Perbaikan</span></a>
                </div>
            </div>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['admin', 'petugas']))
    <div class="space-y-1">
        <button class="dropdown-btn menu-item group flex items-center justify-between w-full px-3 py-2 rounded-lg transition-all duration-200">
            <span class="flex items-center gap-3">
                <i class="fa-solid fa-file-lines group-hover:scale-110 transition-transform duration-200"></i>
                <span>Laporan</span>
            </span>
            <i class="fa-solid fa-chevron-right dropdown-arrow transition-transform duration-200"></i>
        </button>

            <div class="dropdown-menu ml-4 mt-1 hidden space-y-1">
                <a href="{{ route('management.laporan.barang-masuk') }}"
                    class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-download group-hover:scale-110 transition-transform duration-200"></i>
                    <span>Barang Masuk</span>
                </a>

                <a href="{{ route('management.laporan.barang-keluar') }}"
                    class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-upload group-hover:scale-110 transition-transform duration-200"></i>
                    <span>Barang Keluar</span>
                </a>

                <a href="{{ route('management.laporan.inventaris-barang') }}"
                    class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-box group-hover:scale-110 transition-transform duration-200"></i>
                    <span>Inventaris Barang</span>
                </a>
            </div>
        </div>
    @endif


        @if (in_array(auth()->user()->role_pengguna, ['peminjam']))
            <a href="{{ route('peminjaman.transaksi.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-handshake group-hover:scale-110 transition-transform duration-200"></i>
                <span>Peminjaman Saya</span>
            </a>
        @endif

        @if (in_array(auth()->user()->role_pengguna, ['admin', 'petugas']))
            <a href="{{ route('management.peminjaman.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200"><i class="fa-solid fa-handshake group-hover:scale-110 transition-transform duration-200"></i> <span>Peminjaman</span></a>
        @endif
        @if (auth()->user()->role_pengguna === 'admin')
            <a href="{{ route('management.pengguna.index') }}" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-users group-hover:scale-110 transition-transform duration-200"></i>
                <span>Pengguna</span>
            </a>
            <!-- <form action="{{ route('management.backup.run') }}" method="POST" class="menu-item group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                @csrf
                <input type="checkbox" name="download" class="" id="backup-checkbox">
                <label for="backup-checkbox">Download Backup</label>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-download"></i> Backup Database
                </button>
            </form> -->
        @endif
    </nav>
</aside>

<style>
/* Animasi subtle untuk dropdown menu */
@keyframes dropdown {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-dropdown {
    animation: dropdown 0.2s ease-out forwards;
}

/* Modern hover effect untuk menu items */
.menu-item {
    position: relative;
    overflow: hidden;
}

.menu-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: var(--tw-text-opacity);
    background-color: rgba(255, 255, 255, 0.8);
    transform: scaleY(0);
    transition: transform 0.2s ease;
}

.menu-item:hover::before {
    transform: scaleY(1);
}

.menu-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    padding-left: 1rem;
}

.dropdown-btn.active .dropdown-arrow {
    transform: rotate(90deg);
}
</style>