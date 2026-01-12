<header class="bg-[#001349]">
    <div class="mx-auto max-w-[1512px] px-6 py-5 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/Logo 2.png') }}" alt="UNDIP" class="h-[71px] w-auto">
        </a>

        <a href="{{ route('admin.login') }}"
           class="inline-flex items-center gap-3 rounded-[10px] border-2 border-white px-6 py-3 text-white font-semibold text-[18px] md:text-[26px]"
           style="font-family: Inter, sans-serif;">
            <img src="{{ asset('images/in.png') }}" alt="Login Icon" class="h-[26px] w-auto">

            Login sebagai Admin
        </a>
    </div>
</header>
