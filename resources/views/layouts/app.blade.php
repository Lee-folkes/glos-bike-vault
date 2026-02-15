<!DOCTYPE html>
<!-- This layout is used for the main app pages that require authentication. 
 It includes the standard navigation and layout for logged-in users. -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Glos Bike Vault</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <!-- Preconnect to Google Fonts for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Load the Outfit and Space Grotesk fonts from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <!-- Load basic icon pack from box icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

</head>
<body>
    @include('partials.nav')
    <main>
        @yield('content') 
</body>
</html>