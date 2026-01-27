<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UNDIP Innovation')</title>

    {{-- fonts (optional) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    @include('partials.navbar')

    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
