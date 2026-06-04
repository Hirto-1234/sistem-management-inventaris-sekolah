<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User OneInven')</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-utama antialiased bg-gray-100 flex min-h-screen">

    @include('layouts.header')

    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ml-0 md:ml-64">

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    {{-- SIDEBAR + DROPDOWN SCRIPT --}}
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

        // Dropdown universal
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

    @stack('scripts')
</body>
</html>
