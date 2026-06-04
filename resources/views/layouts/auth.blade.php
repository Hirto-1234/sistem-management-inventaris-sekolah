<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi OneInven')</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-utama antialiased bg-utama min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        @yield('content-auth')
    </div>

    @stack('scripts')
</body>
</html>
