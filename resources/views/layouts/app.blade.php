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
<script>
document.addEventListener("DOMContentLoaded", function () {

    const navInovasi = document.getElementById("nav-inovasi");
    const navInnovator = document.getElementById("nav-innovator");
    const navRanking = document.getElementById("nav-ranking");

    function clearActive() {
        [navInovasi, navInnovator, navRanking].forEach(el => {
            el?.classList.remove("border-b-2","border-white","pb-1");
        });
    }

    function setActiveFromHash() {
        clearActive();

        const hash = window.location.hash;

        if (hash === "#inovasi-section") {
            navInovasi?.classList.add("border-b-2","border-white","pb-1");
        }

        if (hash === "#innovator-section") {
            navInnovator?.classList.add("border-b-2","border-white","pb-1");
        }

        if (hash === "#ranking-section") {
            navRanking?.classList.add("border-b-2","border-white","pb-1");
        }
    }

    // Jalankan saat pertama load
    setActiveFromHash();

    // Jalankan saat hash berubah (TANPA refresh)
    window.addEventListener("hashchange", setActiveFromHash);

});
</script>


<body class="bg-white">
    @include('partials.navbar')

    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
