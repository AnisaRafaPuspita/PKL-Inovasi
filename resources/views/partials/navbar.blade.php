<header class="bg-[#001349] sticky top-0 z-50 shadow-md">
    <div class="mx-auto max-w-[1512px] px-6 py-4 flex items-center justify-between">

        <!-- LEFT : LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-4">
            
            <div class="h-[60px] px-4 py-2 bg-white rounded-full overflow-hidden flex items-center shadow-sm">
                <img 
                    src="{{ asset('images/LogoDirinovBaru.jpg') }}" 
                    alt="Logo Dirinov"
                    class="h-full w-auto object-contain"
                >
            </div>

            <div class="h-[60px] px-4 py-2 bg-white rounded-full overflow-hidden flex items-center shadow-sm">
                <img 
                    src="{{ asset('images/Dikti-Undip.png') }}" 
                    alt="Dikti dan Undip"
                    class="h-full w-auto object-contain"
                >
            </div>

        </a>

        <!-- CENTER : MENU -->
        <nav class="hidden md:flex items-center gap-8 text-white font-medium text-[15px]">

            <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300' }}">
                Beranda
            </a>

            <a href="{{ route('home') }}#inovasi-section"
            id="nav-inovasi"
            class="{{ request()->routeIs('innovations.*') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300' }}">
                Inovasi
            </a>

            <a href="{{ route('home') }}#innovator-section"
            id="nav-innovator"
            class="{{ request()->routeIs('innovators.*') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300' }}">
                Inovator
            </a>

            <a href="{{ route('home') }}#ranking-section"
            id="nav-ranking"
            class="{{ request()->routeIs('rankings.*') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300' }}">
                Ranking
            </a>

            <a href="{{ route('statistik.index') }}"
            class="{{ request()->routeIs('statistik.*') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300' }}">
                Statistik
            </a>

        </nav>

        <!-- RIGHT : LOGIN -->
        <a href="{{ route('admin.login') }}"
            class="inline-flex items-center gap-2
                   rounded-[10px]
                   border-2 border-white
                   px-5 py-2.5
                   text-white font-semibold
                   text-[14px] md:text-[15px]
                   hover:bg-white/10 transition">

            <img src="{{ asset('images/in.png') }}"
                 alt="Login Icon"
                 class="h-[18px] w-auto">

            Login Admin
        </a>

    </div>
</header>