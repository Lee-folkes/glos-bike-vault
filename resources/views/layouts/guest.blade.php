<!DOCTYPE html>
<!-- This layout is used for guest pages like login and registration, 
 which have a different design from the main app layout. -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Glos Bike Vault</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <nav> </nav>
    <main>
        @yield('content') 
</body>
</html>