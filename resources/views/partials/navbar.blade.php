<header class="bg-[#001349]">
    <div class="mx-auto max-w-[1512px] px-6 py-5 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center">
            <div class="h-[76px] px-4 py-1 bg-white rounded-full overflow-hidden flex items-center shadow-sm">
                <img 
                    src="{{ asset('images/LogoDirinovBaru.jpg') }}" 
                    alt="UNDIP"
                    class="h-full w-auto object-contain"
                >
            </div>
        </a>



        <a href="{{ route('admin.login') }}"
            class="inline-flex items-center gap-2
                    rounded-[10px]
                    border-2 border-white
                    px-5 py-2.5
                    text-white font-semibold
                    text-[14px] md:text-[15px]
                    hover:bg-white/10 transition"
            style="font-family: Inter, sans-serif;">

                <img src="{{ asset('images/in.png') }}"
                    alt="Login Icon"
                    class="h-[18px] w-auto">

                Login Admin
            </a>


    </div>
</header>
