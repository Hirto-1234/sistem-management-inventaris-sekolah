<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin OneInven')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-utama flex min-h-screen bg-kedua">

    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ml-0 md:ml-64">

        @include('layouts.header')

        <main class="flex-1 p-6">
            @yield('content-admin')
        </main>

        @include('layouts.footer')
    </div>

    {{-- SIDEBAR + DROPDOWN --}}
    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');

        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }

        document.querySelectorAll('.dropdown-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const menu = btn.nextElementSibling;
                const arrow = btn.querySelector('.dropdown-arrow');

                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.add('hidden');
                });

                document.querySelectorAll('.dropdown-arrow').forEach(a => {
                    if (a !== arrow) a.classList.remove('rotate-90');
                });

                menu.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-90');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.dropdown-arrow').forEach(a => a.classList.remove('rotate-90'));
        });
    </script>

    {{-- GLOBAL SWEETALERT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('swal_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('swal_success') }}',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
            @endif

            @if(session('swal_error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('swal_error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1E3A8A'
                });
            @endif

            @if(session('swal_toast'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: '{{ session('swal_toast.icon', 'info') }}',
                    title: '{{ session('swal_toast.message') }}'
                });
            @endif
        });
    </script>

    @stack('scripts')

</body>
</html>