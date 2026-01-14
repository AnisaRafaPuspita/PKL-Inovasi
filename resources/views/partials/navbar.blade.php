<header class="bg-[#001349]">
    <div class="mx-auto max-w-[1512px] px-6 py-5 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/Logo 2.png') }}" alt="UNDIP" class="h-[71px] w-auto">
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
